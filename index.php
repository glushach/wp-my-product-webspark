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
        wp_die(__('Этот плагин требует установленного и активированного WooCommerce.', 'wp-my-product-webspark'));
    }
}
register_activation_hook(__FILE__, 'wp_my_product_webspark_check_woocommerce');


function wp_my_product_webspark_init() {
    // Здесь будет основной код плагина
}
add_action('plugins_loaded', 'wp_my_product_webspark_init');
