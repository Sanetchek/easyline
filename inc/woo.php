<?php

/**
 * Set free shipping as default shipping method if it is available.
 *
 * Checks if there are any shipping rates available for the cart.
 * If so, loops through them and stops at the first free shipping method.
 * Sets the chosen shipping methods to this free shipping method.
 *
 * @since 1.0.0
 */
function set_default_chosen_shipping_method(){
  if(count(WC()->session->get('shipping_for_package_0')['rates']) > 0){
    foreach(WC()->session->get('shipping_for_package_0')['rates'] as $rate_id =>$rate)
      if($rate->method_id == 'free_shipping'){
        $default_rate_id = array($rate_id);
        break;
      }

    WC()->session->set('chosen_shipping_methods', $default_rate_id);
  }
}
add_action('woocommerce_before_cart', 'set_default_chosen_shipping_method', 5);

/**
 * Enqueue scripts required for the theme.
 *
 * If the theme is using WooCommerce, we need to enqueue the scripts
 * required for the add-to-cart functionality.
 *
 * @since 1.0.0
 */
function theme_enqueue_scripts() {
  if (function_exists('is_woocommerce')) {
    wp_enqueue_script('wc-add-to-cart');
    wp_enqueue_script('wc-cart-fragments');
  }
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

/**
 * Ajax Cart Count Fragment.
 *
 * Returns the updated cart count html as a fragment to be used in the
 * `woocommerce_add_to_cart_fragments` filter.
 *
 * @since 1.0.0
 *
 * @param array $fragments The current fragments.
 * @return array $fragments The updated fragments with the cart count html.
 */
add_filter('woocommerce_add_to_cart_fragments', 'refresh_cart_fragments');
function refresh_cart_fragments($fragments) {
  ob_start();
  ?>
  <span class="cart-count" id="header-cart-count">
    <?php echo WC()->cart->get_cart_contents_count(); ?>
  </span>
  <?php
  $fragments['#header-cart-count'] = ob_get_clean();

  ob_start();
  ?>
  <div id="updated_header_mini_cart">
    <?php woocommerce_mini_cart(); ?>
  </div>
  <?php
  $fragments['#updated_header_mini_cart'] = ob_get_clean();

  return $fragments;
}


/**
 * AJAX handler to add a product to the cart.
 *
 * @since 1.0
 */
function prefix_ajax_add_foobar() {
   $product_id  = intval( $_POST['product_id'] );
  // add code the add the product to your cart
  die();
}
add_action( 'wp_ajax_add_foobar', 'prefix_ajax_add_foobar' );
add_action( 'wp_ajax_nopriv_add_foobar', 'prefix_ajax_add_foobar' );


/**
 * AJAX handler to update the quantity of a cart item.
 *
 * @since 1.0
 */
function set_quantity_custom() {
  global $woocommerce;
  $json = array();
  $cart_item_key = $_POST['id'];
  $value = $_POST['new_count'];
  $new_val = $woocommerce->cart->set_quantity($cart_item_key, $value);
  $json["item"] = $value;
  header('Content-Type: application/json');
  echo json_encode($json, JSON_UNESCAPED_UNICODE);
  die();
}
add_action( 'wp_ajax_set_quantity_custom', 'set_quantity_custom' );
add_action( 'wp_ajax_nopriv_set_quantity_custom', 'set_quantity_custom' );

/**
 * zakaz
 *
 * @description
 *   Handle the contact form sending. If the form was submitted (i.e. the 'name_user' field is set),
 *   it will send an email to the specified email address with the form values.
 *
 * @param void
 *
 * @return void
 */
function zakaz(){
  if(isset($_POST['name_user'])) {
    // $sendto = get_option('admin_email');
    $sendto = 'office@easyline.co.il';
    $subject = "Form";

    // make letter headers
    $headers  = "From: contact@".($_SERVER["HTTP_HOST"])."\r\n";
    $headers .= 'noreply@'.($_SERVER["HTTP_HOST"]).'\r\n';
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html;charset=utf-8 \r\n";
    $msg  = "<html><body style='font-family:Arial,sans-serif;'>";
    $msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>$subject</h2>\r\n";

    // make letter body
    $msg .= "<p><strong>Name: </strong> ".$_POST['name_user']."</p>\r\n";

    if(isset($_POST['mail_user'])){
      $msg .= "<p><strong>Email: </strong> ".$_POST['mail_user']."</p>\r\n";
    }

    $msg .= "</body></html>";

    // send message
    if(@mail($sendto, $subject, $msg, $headers)){
      echo "<p>ok</p>" ; } else { echo "<p>not</p>";
    }
  }
}
add_action('wp_ajax_zakaz', 'zakaz');
add_action('wp_ajax_nopriv_zakaz', 'zakaz');


/**
 * Disable required field for postcode in checkout
 *
 * @param array $fields The billing fields.
 *
 * @return array The modified billing fields.
 */
function custom_override_checkout_fields( $fields ) {
  $fields['billing']['billing_postcode']['required'] = false;
  return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );