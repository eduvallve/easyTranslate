<?php

$cfg = array(
    'table' => $GLOBALS['wpdb']->prefix."my_dictionary",
    'tableMeta' => $GLOBALS['wpdb']->prefix."my_dictionary_meta",
    'get_locale' => str_replace("_", "-",get_locale())
);

/**
 * Create empty tables
 */

function createMyDictionaryTable() {
    $table = $GLOBALS['cfg']['table'];
    $query_createMyDictionary_table = "CREATE TABLE IF NOT EXISTS $table (
        id int NOT NULL AUTO_INCREMENT,
        post_id int NOT NULL,
        track_language varchar(10) NOT NULL,
        ".get_locale()." longtext NULL,
        PRIMARY KEY (id)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $createMyDictionary_table = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_createMyDictionary_table));
}

function createMyDictionaryMetaTable() {
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $query_createMyDictionaryMeta_table = "CREATE TABLE IF NOT EXISTS $tableMeta (
        meta_id int NOT NULL AUTO_INCREMENT,
        meta_key varchar(255) NULL,
        meta_value longtext NULL,
        PRIMARY KEY (meta_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $createMyDictionaryMeta_table = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_createMyDictionaryMeta_table));
}

function createDB_pluginTables() {
    showFunctionFired('createDB_pluginTables()');
    $table = $GLOBALS['cfg']['table'];
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $getExistingTables = "SELECT * FROM $table, $tableMeta";
    $existingTables = $GLOBALS['wpdb']->get_results($getExistingTables);
    if (count($existingTables) === 0) {
        createMyDictionaryTable();
        createMyDictionaryMetaTable();
    }
}

/**
 * Fill default values for wp_my_dictionary_meta
 */

function saveDefaultLanguage($defaultLanguage) {
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $query_saveDefaultLanguage = "INSERT INTO $tableMeta (meta_key, meta_value) VALUES ('defaultLanguage', '$defaultLanguage')";
    $saveDefaultLanguage = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_saveDefaultLanguage));
}

function saveFirstSupportedLanguage($language) {
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $query_savefirstSupportedLanguage = "INSERT INTO $tableMeta (meta_key, meta_value) VALUES ('supportedLanguages', '$language')";
    $savefirstSupportedLanguage = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_savefirstSupportedLanguage));
}

function saveDefaultPostType($defaultPostTypes) {
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $query_saveDefaultPostType = "INSERT INTO $tableMeta (meta_key, meta_value) VALUES ('supportedPostTypes', '$defaultPostTypes')";
    $saveDefaultPostType = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_saveDefaultPostType));
}

/**
 * Get values from fundamental fields in wp_my_dictionary_meta
 */

function getDefaultLanguage() {
    if ( isset($GLOBALS['cfg']['defaultLanguage']) ) {
        return $GLOBALS['cfg']['defaultLanguage'];
    } else {
        showFunctionFired('getDefaultLanguage()');
        $tableMeta = $GLOBALS['cfg']['tableMeta'];
        $query_getDefaultLanguage = "SELECT meta_value FROM $tableMeta WHERE meta_key = 'defaultLanguage'";
        $getDefaultLanguage = $GLOBALS['wpdb']->get_results($query_getDefaultLanguage);
        if (count($getDefaultLanguage) === 0) {
            $defaultLanguage = $GLOBALS['cfg']['get_locale'];
            saveDefaultLanguage($defaultLanguage);
        } else {
            $defaultLanguage = implode(array_column($getDefaultLanguage, 'meta_value'));
        }
        $GLOBALS['cfg']['defaultLanguage'] = $defaultLanguage;
        return $defaultLanguage;
    }
}

function getSupportedLanguages() {
    if ( isset($GLOBALS['cfg']['supportedLanguages']) ) {
        return $GLOBALS['cfg']['supportedLanguages'];
    } else {
        showFunctionFired('getSupportedLanguages()');
        $tableMeta = $GLOBALS['cfg']['tableMeta'];
        $query_getSupportedLanguages = "SELECT meta_value FROM $tableMeta WHERE meta_key = 'supportedLanguages'";
        $getSupportedLanguages = $GLOBALS['wpdb']->get_results($query_getSupportedLanguages);
        if (count($getSupportedLanguages) === 0) {
            $firsSupportedLanguage = $GLOBALS['cfg']['get_locale'];
            saveFirstSupportedLanguage($firsSupportedLanguage);
            $GLOBALS['cfg']['supportedLanguages'] = [$firsSupportedLanguage];
            return [$firsSupportedLanguage];
        } else {
            $supportedLanguages = explode(",",implode(array_column($getSupportedLanguages, 'meta_value')));
            $GLOBALS['cfg']['supportedLanguages'] = $supportedLanguages;
            return $supportedLanguages;
        }
    }
}

function getColumnLanguages() {
    if ( isset($GLOBALS['cfg']['columnLanguages']) ) {
        return $GLOBALS['cfg']['columnLanguages'];
    } else {
        showFunctionFired('getColumnLanguages()');
        $table = $GLOBALS['cfg']['table'];
        $query_getColumnLanguages = "SELECT column_name AS column_languages FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$table' AND column_name NOT LIKE '%id%'";
        $getColumnLanguages = $GLOBALS['wpdb']->get_results($query_getColumnLanguages);

        $columnLanguages = array_map(function($lang) {
            return str_replace("_", "-",$lang);
        }, array_column($getColumnLanguages, 'column_languages'));

        $GLOBALS['cfg']['columnLanguages'] = $columnLanguages;
        return $columnLanguages;
    }
}

function getSupportedPostTypes() {
    if ( isset($GLOBALS['cfg']['supportedPostTypes']) ) {
        return $GLOBALS['cfg']['supportedPostTypes'];
    } else {
        showFunctionFired('getSupportedPostTypes()');
        $tableMeta = $GLOBALS['cfg']['tableMeta'];
        $query_getSupportedPostTypes = "SELECT meta_value FROM $tableMeta WHERE meta_key = 'supportedPostTypes'";
        $getSupportedPostTypes = $GLOBALS['wpdb']->get_results($query_getSupportedPostTypes);
        if (count($getSupportedPostTypes) === 0) {
            $defaultPostTypes = "page,post";
            saveDefaultPostType($defaultPostTypes);
            $GLOBALS['cfg']['supportedPostTypes'] = $defaultPostTypes;
            return [$defaultPostTypes];
        } else {
            $postTypes = explode(",",implode(array_column($getSupportedPostTypes, 'meta_value')));
            $GLOBALS['cfg']['supportedPostTypes'] = $postTypes;
            return $postTypes;
        }
    }
}

/**
 * Alter main table when a new language is set
 */

function addLanguageColumn($lang, $table) {
    $table = $GLOBALS['cfg']['table'];
    $query_addLanguageColumn = "ALTER TABLE $table ADD COLUMN $lang longtext";
    $addLanguageColumn = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_addLanguageColumn));
}

function addNewDictionaryColumns() {
    $table = $GLOBALS['cfg']['table'];
    $supportedLanguages = getSupportedLanguages();
    $columnLanguages = getColumnLanguages();
    $missingColumnLanguages = array_diff($supportedLanguages, $columnLanguages);
    foreach ($missingColumnLanguages as $missingLanguage) {
        $lang = convertLanguageCodesForDB($missingLanguage);
        addLanguagecolumn($lang, $table);
    }
}

?>