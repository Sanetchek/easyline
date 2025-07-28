<?php

/**
 * Break content on N words
 *
 * @param [type] $text
 * @param integer $counttext
 * @param string $sep
 * @return void
 */
function str_words_count($text, $counttext = 30, $sep = ' ') {
	$words = explode($sep, $text);

	if ( count($words) > $counttext )
		$text = join($sep, array_slice($words, 0, $counttext));

	return $text;
}

/**
 * Add blcss button to the TinyMCE editor buttons array.
 *
 * @param array $buttons The existing TinyMCE buttons array.
 * @return array The modified TinyMCE buttons array with the blcss button added.
 */
function add_button($buttons){
  array_push($buttons,"blcss");
  return $buttons;
}

/**
 * Add the blcss TinyMCE plugin to the TinyMCE plugins array.
 *
 * @param array $plugin_array The existing TinyMCE plugins array.
 * @return array The modified TinyMCE plugins array with the blcss plugin added.
 */
function add_plugin($plugin_array) {
  $plugin_array['blist'] = get_bloginfo('template_url').'/js/customcodes.js';
  return $plugin_array;
}

/**
 * Output the add to cart form for a given product ID.
 *
 * @param int $product_id The product ID.
 * @param bool $is_product_instock Whether the product is in stock.
 * @param bool $show_quantity Whether to show the quantity input (default: true).
 *
 * @return void
 */
function show_add_to_cart_form($product_id, $is_product_instock, $show_quantity = true) {
  $product = wc_get_product($product_id);
  if (!$product || !$product->is_type('simple')) {
    return; // AJAX only works automatically with simple products
  }

  ob_start();
  ?>
  <form class="cart add-to-cart-wrap" method="post" enctype="multipart/form-data">
    <div class="btn-wrap">
      <button type="submit"
              name="add-to-cart"
              value="<?php echo esc_attr($product_id); ?>"
              class="button product-btn add-to-cart ajax_add_to_cart add_to_cart_button"
              data-product_id="<?php echo esc_attr($product_id); ?>"
              data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
              data-quantity="1"
              <?php echo !$is_product_instock ? 'disabled' : ''; ?>>
        <?php _e('הוסף לסל ', 'easyline'); ?>
        <img src="<?php echo get_template_directory_uri(); ?>/img/cart-green.png" alt="cart">
      </button>
    </div>

    <?php if ($show_quantity) : ?>
      <div class="quantity">
        <a href="javascript:void(0);" class="plus">
          <img src="<?php echo get_template_directory_uri(); ?>/img/plus.png" alt="plus" />
        </a>

        <input type="number"
               class="input-text qty text"
               step="1" min="1"
               name="quantity"
               value="1"
               title="Qty"
               size="4"
               pattern="[0-9]*"
               inputmode="numeric">

        <a href="javascript:void(0);" class="minus">
          <img src="<?php echo get_template_directory_uri(); ?>/img/minus.png" alt="minus" />
        </a>
      </div>
    <?php else : ?>
      <input type="hidden" name="quantity" value="1">
    <?php endif; ?>
  </form>
  <?php
  echo ob_get_clean();
}

/**
 * Output the phone number with a link to call.
 *
 * @return void
 */
function show_phone_number() {
  $phone = get_field('phone', 'options');
  $href = 'tel:' . str_replace("-", "", $phone);
  echo '<div class="phone">';
  echo '<span>' . __('טל. ', 'easyline') . '</span>';
  echo '<a href="' . $href . '">' . $phone . '</a>';
  echo '</div>';
}

/**
 * Outputs the HTML for a toggle menu button in the header.
 *
 * The toggle menu is displayed as a sandwich icon and is only visible
 * on extra small and small screen sizes.
 *
 * @return void
 */
function easyline_header_toggle_menu() {
  ?>
  <div class="toggle_mnu visible-xs-inline-block visible-sm-inline-block">
    <span class="sandwich">
      <span class="sw-topper"></span>
      <span class="sw-bottom"></span>
      <span class="sw-footer"></span>
    </span>
  </div>
  <?php
}

/**
 * Gets the user's name. If the user is not logged in, returns an empty string.
 *
 * @return string The user's name.
 */
function easyline_get_user_name() {
  if ( ! is_user_logged_in() ) {
    return ''; // Optional: return 'Login' or nothing if not logged in
  }

  $current_user = wp_get_current_user();
  $first_name = $current_user->user_firstname;
  $display_name = $current_user->display_name;

  return esc_html( $first_name ? $first_name : $display_name );
}