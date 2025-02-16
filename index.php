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
  wp_enqueue_style('my-custom-plugin-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'my_custom_plugin_enqueue_styles');

function wp_my_product_webspark_init() {
  require 'inc/account-pages.php';
}
add_action('plugins_loaded', 'wp_my_product_webspark_init');
