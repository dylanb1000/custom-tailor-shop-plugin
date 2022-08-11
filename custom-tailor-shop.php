<?php

/**
 * Custom Tailor Shop Plugin Main
 *
 * @package   CustomTailorShop
 * @author    Dylan Bui
 *
 * @wordpress-plugin
 * Plugin Name: Custom Tailor Shop
 * Description: Custom plugin to incorporate a system for tailor shops.
 * Version:     1.0
 * Author:      Dylan Bui
 */

ob_clean();
ob_start();
// Abort if this file is accessed directly.
if (!defined('ABSPATH')) {
  exit;
}


//LOADING THE BASE CLASS 
if (!class_exists('WP_List_Table')) {
  require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

//LOAD THE CHILD CLASS
require dirname(__FILE__) . '/includes/list-table.php';

add_action('admin_menu', 'add_menu_items');

// Include page shortcodes
require dirname(__FILE__) . '/includes/individual-form.php';
require dirname(__FILE__) . '/includes/invoice-query-page.php';
require dirname(__FILE__) . '/includes/invoice-search.php';
require dirname(__FILE__) . '/includes/new-order.php';
require dirname(__FILE__) . '/includes/new-order-processing.php';
require dirname(__FILE__) . '/includes/update-data.php';

//REGISTER ADMIN PAGE
function add_menu_items()
{
  add_menu_page(
    __('Custom Tailor Shop Menu'), // Page title.
    __('Custom Tailor Shop'),        // Menu title.
    'activate_plugins',                                         // Capability.
    'list_test',                                             // Menu slug.
    'render_list_page',                                       // Callback function.
    dirname(plugin_dir_url(__FILE__)) . '/custom-tailor-shop/images/icon.png'
  );
}


//CALLBACK TO RENDER ADMIN PAGE
function render_list_page()
{
  // Create an instance of our package class.
  $list_table = new List_Table();

  // Fetch, prepare, sort, and filter our data.
  $list_table->prepare_items();

  // Include the view markup.
  include dirname(__FILE__) . '/views/page.php';
}



//create database on activation
register_activation_hook(__FILE__, 'my_plugin_create_db');
function my_plugin_create_db()
{
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'tailor_shop_data';

  $sql = "CREATE TABLE $table_name (
		invoice_number varchar(255) NOT NULL,
    meta_key varchar(255) NOT NULL,
    meta_value varchar(255) NOT NULL
	) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

//delete database when plugin is deleted(needs to be edited)
register_deactivation_hook(__FILE__, 'my_plugin_remove_database');
function my_plugin_remove_database()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'tailor_shop_data';
  $sql = "DROP TABLE IF EXISTS $table_name";
  $wpdb->query($sql);
  delete_option("my_plugin_db_version");
}

//Logout Redirect
add_action('wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout()
{
  wp_redirect(get_site_url() . '/custom-login.php');
  exit();
}

//Custom Roles
remove_role('subscriber');
remove_role('editor');
remove_role('contributor');
remove_role('author');
add_role('employee', 'Employee');
add_role('admin_no_view', "Admin_No_View");
$role = get_role('employee');
$role->add_cap('level_0');
$adrole = get_role('admin_no_view');
$role->add_cap('level_0');

//Remove Admin Bar for other users
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}

//User Restriction
add_action('wp', 'wpse69369_is_correct_user');
function wpse69369_is_correct_user()
{
  global $post;
  $roles = wp_get_current_user()->roles;
  $postname = $post->post_name;
  //total access
  if (in_array('administrator', $roles)) {
  } else if (in_array('admin_no_view', $roles)) {
  } else if ($postname == "invoice-query" && in_array('employee', $roles)) {
  } else if ($postname == "invoice-page" && in_array('employee', $roles)) {
  } else if ($postname == "update-data" && in_array('employee', $roles)) {
  } else if ($postname == "home") {
  } else {
    wp_safe_redirect(site_url());
    exit;
  }
}
