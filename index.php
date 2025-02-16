<?php
/**
 * Plugin Name: WP My Product Webspark
 * Plugin URI: https://example.com
 * Description: Функціональністю цього кастомного плагіну є CRUD операції для роботи з продуктами через сторінку My account.
 * Version: 1.0.0
 * Author: Ваше Имя
 * Author URI: https://example.com
 * License: GPL2
 * Text Domain: wp-my-product-webspark
 */


if (!defined('ABSPATH')) {
    exit;
}

function wp_my_product_webspark_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('Цей плагін вимагає встановленого і активованого WooCommerce.', 'wp-my-product-webspark'));
    }
}
register_activation_hook(__FILE__, 'wp_my_product_webspark_check_woocommerce');

function my_custom_plugin_enqueue_styles() {
  wp_enqueue_media(); 
  wp_enqueue_script('custom-media-uploader', plugin_dir_url(__FILE__) . 'assets/js/media-uploader.js', array('jquery'), null, true);

  wp_enqueue_style('my-custom-plugin-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'my_custom_plugin_enqueue_styles');

function allow_users_upload_files() {
  if (!current_user_can('upload_files')) {
      $user = wp_get_current_user();
      $user->add_cap('upload_files');
  }
}
add_action('init', 'allow_users_upload_files');

function filter_user_media_library($query) {
  $user = wp_get_current_user();

  if (!current_user_can('manage_options')) {
      $query['author'] = $user->ID;
  }
  
  return $query;
}
add_filter('ajax_query_attachments_args', 'filter_user_media_library');

function wp_my_product_webspark_init() {
  require plugin_dir_path( __FILE__ ) . 'inc/account-pages.php';
  require plugin_dir_path( __FILE__ ) . 'inc/add-product.php';
}
add_action('plugins_loaded', 'wp_my_product_webspark_init');
