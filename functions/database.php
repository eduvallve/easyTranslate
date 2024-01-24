<?php

$wp_my_dictionary = $GLOBALS['wpdb']->prefix."my_dictionary";
$wp_my_dictionary_meta = $GLOBALS['wpdb']->prefix."my_dictionary_meta";
$get_locale = str_replace("_", "-",get_locale());

function createMyDictionaryTable() {
    $table = $GLOBALS['wp_my_dictionary'];
    $query_createMyDictionary_table = "CREATE TABLE IF NOT EXISTS {$table} (
        id int NOT NULL AUTO_INCREMENT,
        post_id int NOT NULL,
        ".get_locale()." longtext NULL,
        PRIMARY KEY (id)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $createMyDictionary_table = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_createMyDictionary_table));
}

function createMyDictionaryMetaTable() {
    $tableMeta = $GLOBALS['wp_my_dictionary_meta'];
    $query_createMyDictionaryMeta_table = "CREATE TABLE IF NOT EXISTS {$tableMeta} (
        meta_id int NOT NULL AUTO_INCREMENT,
        meta_key varchar(255) NULL,
        meta_value longtext NULL,
        PRIMARY KEY (meta_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $createMyDictionaryMeta_table = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_createMyDictionaryMeta_table));
}


function createDB_pluginTables() {
    $table = $GLOBALS['wp_my_dictionary'];
    $tableMeta = $GLOBALS['wp_my_dictionary_meta'];
    $getExistingTables = "SELECT * FROM {$table}, {$tableMeta}";
    $existingTables = $GLOBALS['wpdb']->get_results($getExistingTables);
    if (count($existingTables) === 0) {
        createMyDictionaryTable();
        createMyDictionaryMetaTable();
    }
}

function saveDefaultLanguage() {
    $tableMeta = $GLOBALS['wp_my_dictionary_meta'];
    $query_saveDefaultLanguage = "INSERT INTO {$tableMeta} (meta_key, meta_value) VALUES ('defaultLanguage', '".$GLOBALS['get_locale']."')";
    $saveDefaultLanguage = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_saveDefaultLanguage));
}

function getDefaultLanguage() {
    $query_getDefaultLanguage = "SELECT meta_value FROM ".$GLOBALS['wp_my_dictionary_meta']." WHERE meta_key = 'defaultLanguage'";
    $getDefaultLanguage = $GLOBALS['wpdb']->get_results($query_getDefaultLanguage);
    if (count($getDefaultLanguage) === 0) {
        saveDefaultLanguage();
        return $GLOBALS['get_locale'];
    } else {
        return implode(array_column($getDefaultLanguage, 'meta_value'));
    }
}

?>