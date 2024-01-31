<?php

/**
 * Functions (Front-office)
 */

require_once 'functions.global.php';
require_once 'functions.database.php';
require_once 'functions.page-inspect.php';

function my_dictionary_plugin() {
    createDB_pluginTables();
    fillDictionaryTable();
}

add_action('wp_enqueue_scripts', 'my_dictionary_plugin');

?>