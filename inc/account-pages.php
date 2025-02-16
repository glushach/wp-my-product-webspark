<?php

function custom_register_endpoints_pages() {
  if (!get_page_by_title('Add Product')) {
      $page_id = wp_insert_post(array(
          'post_title'   => 'Add Product',
          'post_content' => 'Here you can add your products.',
          'post_status'  => 'publish',
          'post_type'    => 'page',
      ));
      update_option('woocommerce_add_product_page', $page_id);
  }

  if (!get_page_by_title('My Products')) {
      $page_id = wp_insert_post(array(
          'post_title'   => 'My Products',
          'post_content' => 'List of your products.',
          'post_status'  => 'publish',
          'post_type'    => 'page',
      ));
      update_option('woocommerce_my_products_page', $page_id);
  }
}
add_action('init', 'custom_register_endpoints_pages');

function custom_add_account_menu_items($items) {
  $new_items = array(
    'my-products' => __('My Products', 'textdomain'),
    'add-product' => __('Add Product', 'textdomain'),
  );

  $dashboard_items = array_slice($items, 0, 1);
  $remaining_items = array_slice($items, 1);

  $items = array_merge($dashboard_items, $new_items, $remaining_items);

  return $items;
}
add_filter('woocommerce_account_menu_items', 'custom_add_account_menu_items');

function custom_add_endpoints() {
  add_rewrite_endpoint('add-product', EP_ROOT | EP_PAGES);
  add_rewrite_endpoint('my-products', EP_ROOT | EP_PAGES);
}
add_action('init', 'custom_add_endpoints');

function custom_add_product_content() {
  echo '<h2>' . __('Add Product', 'textdomain') . '</h2>';
  echo '<p>' . __('Here you can add your products.', 'textdomain') . '</p>';
}
add_action('woocommerce_account_add-product_endpoint', 'custom_add_product_content');

function custom_my_products_content() {
  echo '<h2>' . __('My Products', 'textdomain') . '</h2>';
  echo '<p>' . __('List of your products.', 'textdomain') . '</p>';
}
add_action('woocommerce_account_my-products_endpoint', 'custom_my_products_content');

