<?php

/**
 * Admin page (back-office)
 */

require_once "admin.dashboard.php";

add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
  createDB_pluginTables();
  add_menu_page(
        'My Dictionary',
        'My Dictionary',
        'manage_options',
        'my-dictionary',
        'mydictionary_admin_page',
        'dashicons-translation',
        81
    );
}

function my_enqueue() {
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'js/admin.js');
    wp_enqueue_style('my_custom_style', plugin_dir_url(__FILE__) . 'css/admin.css');
}

add_action('admin_enqueue_scripts', 'my_enqueue');

?>
