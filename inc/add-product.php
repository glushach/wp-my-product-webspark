<?php

function custom_add_product_form() {
  ?>
  <form method="POST" enctype="multipart/form-data" class="form-add-product">
    <div class="form-wrap">
      <label for="product_title"><?php _e('Product Title', 'textdomain'); ?></label><br>
      <input type="text" name="product_title" required>
    </div>
    <div class="form-wrap">
      <label for="product_price"><?php _e('Product Price', 'textdomain'); ?></label><br>
      <input type="number" name="product_price" required>
    </div>
    <div class="form-wrap">
      <label for="product_quantity"><?php _e('Product Quantity', 'textdomain'); ?></label><br>
      <input type="number" name="product_quantity" required>
    </div>
    <div class="form-wrap">
      <label for="product_description"><?php _e('Product Description', 'textdomain'); ?></label><br>
      <textarea name="product_description"></textarea>
    </div>
    <div class="form-wrap">
      <label for="product_image"><?php _e('Product Image', 'textdomain'); ?></label><br>
      <input type="text" name="product_image_url" id="product_image_url" readonly>
      <button type="button" id="upload_image_button"><?php _e('Upload Image', 'textdomain'); ?></button>
    </div>
    <button type="submit" name="submit_product"><?php _e('Add Product', 'textdomain'); ?></button>
  </form>
  <?php

  if (isset($_POST['submit_product'])) {
    custom_save_product();
  }
}
add_action('woocommerce_account_add-product_endpoint', 'custom_add_product_form');


function custom_save_product() {
  if (
      !isset($_POST['product_title']) ||
      !isset($_POST['product_price']) ||
      !isset($_POST['product_quantity']) ||
      !isset($_POST['product_description'])
  ) {
      return;
  }

  $product_title = sanitize_text_field($_POST['product_title']);
  $product_price = floatval($_POST['product_price']);
  $product_quantity = intval($_POST['product_quantity']);
  $product_description = wp_kses_post($_POST['product_description']);

  if (!empty($_POST['product_image_url'])) {
    $image_url = esc_url($_POST['product_image_url']);

    if ( ! function_exists( 'media_handle_sideload' ) ) {
      require_once(ABSPATH . 'wp-admin/includes/media.php');
    }

    if ( ! function_exists( 'download_url' ) ) {
      require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    if ( ! function_exists( 'wp_read_image_metadata' ) ) {
      require_once(ABSPATH . 'wp-admin/includes/image.php');
    }

    $tmp_file = download_url($image_url);

    if (is_wp_error($tmp_file)) {
      echo '<p>' . __('Error downloading image.', 'textdomain') . '</p>';
      return;
    }

    $file_array = array(
      'name' => basename($image_url),
      'tmp_name' => $tmp_file
    );

    $image_id = media_handle_sideload($file_array, 0);

    if (is_wp_error($image_id)) {
      echo '<p>' . __('Error uploading image.', 'textdomain') . '</p>';
      return;
    }
  }

  $post_id = wp_insert_post(array(
      'post_title'   => $product_title,
      'post_content' => $product_description,
      'post_status'  => 'pending',
      'post_type'    => 'product',
  ));

  update_post_meta($post_id, '_regular_price', $product_price);
  update_post_meta($post_id, '_price', $product_price);
  update_post_meta($post_id, '_stock', $product_quantity);

  if (isset($image_id)) {
      update_post_meta($post_id, '_thumbnail_id', $image_id);
  }

  echo '<p class="info-user">' . __('Product added successfully and is pending review.', 'textdomain') . '</p>';
}


