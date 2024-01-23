<?php

require_once 'functions/database.php';
require_once 'functions/page-inspect.php';

function my_dictionary_plugin() {
    createDB_pluginTables();
    fillDictionaryTable();
}

add_action('wp_enqueue_scripts', 'my_dictionary_plugin');


/**
 * My Dictionary - Admin page
 */

include 'admin/admin.php';

add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	add_menu_page(
        'My Dictionary Dashboard',
        'My Dictionary',
        'manage_options',
        'my-dictionary',
        'mydictionary_admin_page',
        'dashicons-translation',
        81
    );
}

function my_enqueue() {
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'admin/js/admin.js');
}

add_action('admin_enqueue_scripts', 'my_enqueue');

?>