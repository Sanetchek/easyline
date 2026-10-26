<?php

/**
 * Whether the request already has a WooCommerce cart/session cookie.
 *
 * Avoids bootstrapping WC()->cart for anonymous guests (keeps page cache viable).
 *
 * @return bool
 */
function easyline_has_woocommerce_session(): bool {
	if (is_user_logged_in()) {
		return true;
	}

	if (!empty($_COOKIE['woocommerce_items_in_cart']) || !empty($_COOKIE['woocommerce_cart_hash'])) {
		return true;
	}

	foreach (array_keys($_COOKIE) as $cookie_name) {
		if (is_string($cookie_name) && strpos($cookie_name, 'wp_woocommerce_session_') === 0) {
			return true;
		}
	}

	return false;
}

/**
 * Simple IP-based AJAX rate limit.
 *
 * @param string $action Action key.
 * @param int    $max    Max hits in window.
 * @param int    $window Window in seconds.
 * @return bool True when request is allowed.
 */
function easyline_ajax_rate_limit(string $action, int $max = 8, int $window = 300): bool {
	$ip  = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
	$key = 'easyline_rl_' . md5($action . '|' . $ip);
	$count = (int) get_transient($key);

	if ($count >= $max) {
		return false;
	}

	set_transient($key, $count + 1, $window);

	return true;
}

/**
 * Build header cart HTML fragments after cart mutation.
 *
 * @return array<string, string>
 */
function easyline_get_cart_fragments_html(): array {
	$fragments = array();

	if (!function_exists('WC') || !WC()->cart) {
		return $fragments;
	}

	ob_start();
	?>
	<span class="cart-count" id="header-cart-count">
		<?php echo esc_html((string) WC()->cart->get_cart_contents_count()); ?>
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
 * Set free shipping as default shipping method if it is available.
 *
 * @since 1.0.0
 */
function set_default_chosen_shipping_method() {
	$default_rate_id  = null;
	$shipping_package = WC()->session->get('shipping_for_package_0');
	$rates            = (is_array($shipping_package) && isset($shipping_package['rates']) && is_array($shipping_package['rates']))
		? $shipping_package['rates']
		: array();

	if (count($rates) > 0) {
		foreach ($rates as $rate_id => $rate) {
			if ($rate->method_id == 'free_shipping') {
				$default_rate_id = array($rate_id);
				break;
			}
		}

		if ($default_rate_id !== null) {
			WC()->session->set('chosen_shipping_methods', $default_rate_id);
		}
	}
}
add_action('woocommerce_before_cart', 'set_default_chosen_shipping_method', 5);

/**
 * Enqueue scripts required for WooCommerce pages.
 *
 * @since 1.0.0
 */
function theme_enqueue_scripts() {
	if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
		wp_enqueue_script('wc-add-to-cart');
		wp_enqueue_script('wc-cart-fragments');
	}
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

/**
 * Ajax Cart Count Fragment.
 *
 * @param array $fragments The current fragments.
 * @return array
 */
add_filter('woocommerce_add_to_cart_fragments', 'refresh_cart_fragments');
function refresh_cart_fragments($fragments) {
	foreach (easyline_get_cart_fragments_html() as $selector => $html) {
		$fragments[$selector] = $html;
	}

	return $fragments;
}

/**
 * AJAX handler to update the quantity of a cart item.
 *
 * @since 1.0
 */
function set_quantity_custom() {
	check_ajax_referer('easyline_ajax', 'nonce');

	if (!easyline_ajax_rate_limit('set_quantity_custom', 60, 60)) {
		wp_send_json_error(array('message' => 'rate_limited'), 429);
	}

	if (!function_exists('WC') || !WC()->cart) {
		wp_send_json_error(array('message' => 'cart_unavailable'), 400);
	}

	$cart_item_key = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
	$value         = isset($_POST['new_count']) ? absint($_POST['new_count']) : 0;

	// WooCommerce cart keys are 32-char hex hashes.
	if ($cart_item_key === '' || !preg_match('/^[a-f0-9]{32}$/', $cart_item_key) || $value < 1 || $value > 999) {
		wp_send_json_error(array('message' => 'invalid_params'), 400);
	}

	$cart_contents = WC()->cart->get_cart();
	if (!isset($cart_contents[$cart_item_key])) {
		wp_send_json_error(array('message' => 'item_not_in_cart'), 400);
	}

	WC()->cart->set_quantity($cart_item_key, $value);

	wp_send_json_success(
		array(
			'item'      => $value,
			'fragments' => easyline_get_cart_fragments_html(),
		)
	);
}
add_action('wp_ajax_set_quantity_custom', 'set_quantity_custom');
add_action('wp_ajax_nopriv_set_quantity_custom', 'set_quantity_custom');

/**
 * Contact form AJAX handler.
 *
 * @return void
 */
function zakaz() {
	check_ajax_referer('easyline_ajax', 'nonce');

	if (!easyline_ajax_rate_limit('zakaz', 5, 600)) {
		wp_send_json_error(array('message' => 'rate_limited'), 429);
	}

	$name  = isset($_POST['name_user']) ? sanitize_text_field(wp_unslash($_POST['name_user'])) : '';
	$email = isset($_POST['mail_user']) ? sanitize_email(wp_unslash($_POST['mail_user'])) : '';

	if ($name === '') {
		wp_send_json_error(array('message' => 'invalid_params'), 400);
	}

	$sendto  = 'office@easyline.co.il';
	$subject = 'Form';

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: EasyLine <contact@easyline.co.il>',
	);
	if ($email !== '' && is_email($email)) {
		$headers[] = 'Reply-To: ' . $email;
	}

	$msg  = '<html><body style="font-family:Arial,sans-serif;">';
	$msg .= '<h2 style="font-weight:bold;border-bottom:1px dotted #ccc;">' . esc_html($subject) . '</h2>';
	$msg .= '<p><strong>Name: </strong> ' . esc_html($name) . '</p>';
	if ($email !== '') {
		$msg .= '<p><strong>Email: </strong> ' . esc_html($email) . '</p>';
	}
	$msg .= '</body></html>';

	$sent = wp_mail($sendto, $subject, $msg, $headers);

	if ($sent) {
		wp_send_json_success(array('message' => 'ok'));
	}

	wp_send_json_error(array('message' => 'not'), 500);
}
add_action('wp_ajax_zakaz', 'zakaz');
add_action('wp_ajax_nopriv_zakaz', 'zakaz');

/**
 * Disable required field for postcode in checkout.
 *
 * @param array $fields The billing fields.
 * @return array
 */
function custom_override_checkout_fields($fields) {
	$fields['billing']['billing_postcode']['required'] = false;
	return $fields;
}
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
