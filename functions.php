<?php
function createMyDictionaryTable() {
    $query_createMyDictionary_table = "CREATE TABLE IF NOT EXISTS ".$GLOBALS['wpdb']->prefix."my_dictionary (
        id int NOT NULL AUTO_INCREMENT,
        post_id int NOT NULL,
        en_US longtext NULL,
        PRIMARY KEY (id)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $createMyDictionary_table = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_createMyDictionary_table));
}

function createMyDictionaryMetaTable() {
    $query_createMyDictionaryMeta_table = "CREATE TABLE IF NOT EXISTS ".$GLOBALS['wpdb']->prefix."my_dictionary_meta (
        meta_id int NOT NULL AUTO_INCREMENT,
        meta_key varchar(255) NULL,
        meta_value longtext NULL,
        PRIMARY KEY (meta_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $createMyDictionaryMeta_table = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_createMyDictionaryMeta_table));
}


function createDB_pluginTables() {
    $getExistingTables = "SELECT * FROM wp_my_dictionary, wp_my_dictionary_meta";
    $existingTables = $GLOBALS['wpdb']->get_results($getExistingTables);
    if (count($existingTables) === 0) {
        createMyDictionaryTable();
        createMyDictionaryMetaTable();
    }
}

function my_dictionary_plugin() {
    createDB_pluginTables();
}

add_action('init', 'my_dictionary_plugin');
?>