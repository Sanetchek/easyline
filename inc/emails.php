<?php

/**
 * Business order form AJAX callback.
 *
 * @since 1.0.0
 */
function business_order_callback() {
	check_ajax_referer('easyline_ajax', 'nonce');

	if (!function_exists('easyline_ajax_rate_limit') || !easyline_ajax_rate_limit('business_order', 5, 600)) {
		wp_send_json_error(array('message' => 'rate_limited'), 429);
	}

	$raw = isset($_POST['data']) && is_array($_POST['data']) ? wp_unslash($_POST['data']) : array();
	if ($raw === array()) {
		wp_send_json_error(array('message' => 'invalid_params'), 400);
	}

	$data = array();
	foreach ($raw as $key => $value) {
		$safe_key = sanitize_key((string) $key);
		if ($safe_key === '' || is_array($value)) {
			continue;
		}
		// Cap payload size to reduce abuse via oversized fields.
		$data[$safe_key] = sanitize_text_field(substr((string) $value, 0, 500));
	}

	if (empty($data['name']) || empty($data['email']) || !is_email($data['email'])) {
		wp_send_json_error(array('message' => 'invalid_params'), 400);
	}

	$lang         = get_locale();
	$curent_lang  = $lang === 'he_IL' ? heb_fields() : eng_fields();
	$direction    = $lang === 'he_IL' ? 'dir="rtl" ' : '';
	$product_units = '';
	$product_cartons = '';

	foreach ($data as $key => $value) {
		if (strpos($key, 'product_units') !== false) {
			$product_units .= '<p>' . esc_html($value) . '</p>';
		}
		if (strpos($key, 'product_cartons') !== false) {
			$product_cartons .= '<p>' . esc_html($value) . '</p>';
		}
	}

	$message  = '<!DOCTYPE html><html ' . $direction . 'lang="' . esc_attr($lang) . '"><head><meta charset="UTF-8"><title>Letter</title></head><body>';
	$message .= '<p><strong>' . esc_html($curent_lang['name']) . ':</strong> ' . esc_html($data['name'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['id_card']) . ':</strong> ' . esc_html($data['id_card'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['customer']) . ':</strong> ' . esc_html($data['customer'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['office_phone']) . ':</strong> ' . esc_html($data['office_phone'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['mobile_phone']) . ':</strong> ' . esc_html($data['mobile_phone'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['email']) . ':</strong> ' . esc_html($data['email'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['address']) . ':</strong> ' . esc_html($data['address'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['city']) . ':</strong> ' . esc_html($data['city'] ?? '') . '</p>';
	$message .= '<p><strong>' . esc_html($curent_lang['comment']) . ':</strong> ' . esc_html($data['comment'] ?? '') . '</p><hr>';

	if (strlen($product_units) > 15) {
		$message .= '<h4>' . esc_html($curent_lang['units']) . '</h4>' . $product_units;
	}
	if (strlen($product_cartons) > 40) {
		$message .= '<h4>' . esc_html($curent_lang['cartons']) . '</h4>' . $product_cartons;
	}
	$message .= '</body></html>';

	$headers = array(
		'From: EasyLine <easyline@easyline.co.il>',
		'Content-Type: text/html; charset=UTF-8',
		'Cc: admin@easyline.co.il',
	);

	$customer_email = isset($data['email']) ? sanitize_email($data['email']) : '';

	wp_mail('orders@easyline.co.il', $curent_lang['title'], $message, $headers);

	$customer_sent = false;
	if ($customer_email !== '' && is_email($customer_email)) {
		$customer_sent = wp_mail($customer_email, $curent_lang['title'], $message, $headers);
	}

	wp_send_json_success(array('sent' => (bool) $customer_sent));
}
add_action('wp_ajax_business_order', 'business_order_callback');
add_action('wp_ajax_nopriv_business_order', 'business_order_callback');

/**
 * English labels for the business order form.
 *
 * @return array
 */
function eng_fields() {
	return array(
		'name'         => 'Name of the institution',
		'id_card'      => 'H.P. number',
		'address'      => 'Address / Street',
		'city'         => 'City',
		'office_phone' => 'Office Phone',
		'mobile_phone' => 'Mobile Phone',
		'email'        => 'Email',
		'customer'     => 'Name of customer (full name)',
		'comment'      => 'Comments to order',
		'units'        => 'Products to order by units',
		'cartons'      => 'Products to order by cartons',
		'term_block'   => '<p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our&nbsp; <a href="https://easyline.co.il/en/terms-and-conditions/" class="woocommerce-privacy-policy-link" target="_blank" rel="noopener noreferrer">privacy policy</a>.</p>',
		'term_label'   => '<span class="woocommerce-terms-and-conditions-checkbox-text">I have read and agree to the website <a href="https://easyline.co.il/en/terms-and-conditions/" class="woocommerce-terms-and-conditions-link" target="_blank" rel="noopener noreferrer">terms and conditions</a></span>',
		'submit'       => 'Submit',
		'title'        => 'Order form for institutional customers',
		'thanks'       => 'Thank you! Your request has been sent and will be processed shortly',
		'no_correct'   => 'Your ID number is a not correct',
	);
}

/**
 * Hebrew labels for the business order form.
 *
 * @return array
 */
function heb_fields() {
	return array(
		'name'         => 'שם המוסד',
		'id_card'      => 'מספר ח.פ.',
		'address'      => 'כתובת למשלוח סחורה',
		'city'         => 'ישוב',
		'office_phone' => 'טלפון משרד ',
		'mobile_phone' => 'טלפון נייד ',
		'email'        => 'דוא"ל',
		'customer'     => 'שם המזמין (שם מלא)',
		'comment'      => 'הערות להזמנה',
		'units'        => 'מוצרי אבקות',
		'cartons'      => 'מוצרים נוזליים - הזמנה לפי קרטונים',
		'term_block'   => '<p>הנתונים האישיים שלך ישמשו לעיבוד ההזמנה, עבור תמיכה במידת הצורך, לשמירה על חוויית קניה איכותית באתר ולמטרות אחרות המתוארות ב-&nbsp;<a href="https://easyline.co.il/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%95%d7%aa%d7%a0%d7%90%d7%99-%d7%a9%d7%99%d7%9e%d7%95%d7%a9/" class="woocommerce-privacy-policy-link" target="_blank" rel="noopener noreferrer">מדיניות פרטיות</a> שלנו.</p>',
		'term_label'   => '<span class="woocommerce-terms-and-conditions-checkbox-text">קראתי ואני מסכים לאתר <a href="https://easyline.co.il/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%95%d7%aa%d7%a0%d7%90%d7%99-%d7%a9%d7%99%d7%9e%d7%95%d7%a9/" class="woocommerce-terms-and-conditions-link" target="_blank" rel="noopener noreferrer">תנאי שימוש</a></span>',
		'submit'       => 'שלח',
		'title'        => 'טופס הזמנה ללקוחות מוסדיים',
		'thanks'       => 'תודה! הזמנתך התקבלה וניצור עמך קשר בהקדם',
		'no_correct'   => 'מספר הח.פ. שלך אינו נכון',
	);
}
