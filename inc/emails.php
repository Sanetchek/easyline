<?php

/**
 * This function is called when the user submits the form from the business page.
 * The function gets the data from the form, creates an email message with the data
 * and sends the email to the administrator.
 *
 * @since 1.0.0
 */
function business_order_callback(){
  $message = "";
  $data = $_POST['data'];
  $lang = get_locale();
  $curent_lang = $lang == 'he_IL' ? heb_fields() : eng_fields();
  $direction = $lang == 'he_IL' ? 'dir="rtl" ' : '';

  $product_units = "";
  $product_cartons = "";

  foreach ($data as $key => $value) {
    if (strpos($key, 'product_units') !== false) {
      $product_units .= '<p>' . stripcslashes($value) . '</p>';
    }
  }

  foreach ($data as $key => $value) {
    if (strpos($key, 'product_cartons') !== false) {
      $product_cartons .= '<p>' . stripcslashes($value) . '</p>';
    }
  }

  if ($data) {
    $message .= '
    <!DOCTYPE html>
    <html ' . $direction . 'lang="'. $lang .'">
    <head>
      <meta charset="UTF-8">
      <title>Letter</title>
    </head>
    <body>
      <p><strong>' . $curent_lang['name'] . ':</strong> ' . stripcslashes($data['name']) . '</p>
      <p><strong>' . $curent_lang['id_card'] . ':</strong> ' . $data['id_card'] . '</p>
      <p><strong>' . $curent_lang['customer'] . ':</strong> ' . stripcslashes($data['customer']) . '</p>
      <p><strong>' . $curent_lang['office_phone'] . ':</strong> ' . $data['office_phone'] . '</p>
      <p><strong>' . $curent_lang['mobile_phone'] . ':</strong> ' . $data['mobile_phone'] . '</p>
      <p><strong>' . $curent_lang['email'] . ':</strong> ' . $data['email'] . '</p>
      <p><strong>' . $curent_lang['address'] . ':</strong> ' . $data['address'] . '</p>
      <p><strong>' . $curent_lang['city'] . ':</strong> ' . $data['city'] . '</p>
      <p><strong>' . $curent_lang['comment'] . ':</strong> ' . stripcslashes($data['comment']) . '</p>
      <hr>';

    if (strlen($product_units) > 15){
      $message .= '<h4>' . $curent_lang['units'] . '</h4>' . $product_units;
    }
    if (strlen($product_cartons) > 40){
      $message .= '<h4>' . $curent_lang['cartons'] . '</h4>' . $product_cartons;
    }

    $message .= '</body>
    </html>';
  }

  $headers = array(
    'From: EasyLine <easyline@easyline.co.il>',
    'content-type: text/html',
    'Cc: admin@easyline.co.il',
  );
  wp_mail('orders@easyline.co.il', $curent_lang['title'], $message, $headers);
  // wp_mail('roma19.05.94@gmail.com', $curent_lang['title'], $message, $headers);
  echo(wp_mail(sanitize_email($data['email']), $curent_lang['title'], $message, $headers));

  wp_die();
}
add_action( 'wp_ajax_business_order', 'business_order_callback' );
add_action( 'wp_ajax_nopriv_business_order', 'business_order_callback' );

/**
 * Returns an associative array of strings that are used in the business order form
 * in English.
 *
 * @return array
 */
function eng_fields(){
  return array(
    'name'          => 'Name of the institution',
    'id_card'       => 'H.P. number',
    'address'       => 'Address / Street',
    'city'          => 'City',
    'office_phone'  => 'Office Phone',
    'mobile_phone'  => 'Mobile Phone',
    'email'         => 'Email',
    'customer'      => 'Name of customer (full name)',
    'comment'       => 'Comments to order',
    'units'         => 'Products to order by units',
    'cartons'       => 'Products to order by cartons',
    'term_block'    => '<p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our&nbsp; <a href="https://easyline.co.il/en/terms-and-conditions/" class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.</p>',
    'term_label'    => '<span class="woocommerce-terms-and-conditions-checkbox-text">I have read and agree to the website <a href="https://easyline.co.il/en/terms-and-conditions/" class="woocommerce-terms-and-conditions-link" target="_blank">terms and conditions</a></span>',
    'submit'        => 'Submit',
    'title'         => 'Order form for institutional customers',
    'thanks'        => 'Thank you! Your request has been sent and will be processed shortly',
    'no_correct'    => 'Your ID number is a not correct',
  );
};

/**
 * Returns an associative array of strings that are used in the business order form
 * in Hebrew.
 *
 * @return array
 */
function heb_fields(){
  return array(
    'name'          => 'שם המוסד',
    'id_card'       => 'מספר ח.פ.',
    'address'       => 'כתובת למשלוח סחורה',
    'city'          => 'ישוב',
    'office_phone'  => 'טלפון משרד ',
    'mobile_phone'  => 'טלפון נייד ',
    'email'         => 'דוא"ל',
    'customer'      => 'שם המזמין (שם מלא)',
    'comment'       => 'הערות להזמנה',
    'units'         => 'מוצרי אבקות',
    'cartons'       => 'מוצרים נוזליים - הזמנה לפי קרטונים',
    'term_block'    => '<p>הנתונים האישיים שלך ישמשו לעיבוד ההזמנה, עבור תמיכה במידת הצורך, לשמירה על חוויית קניה איכותית באתר ולמטרות אחרות המתוארות ב-&nbsp;<a href="https://easyline.co.il/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%95%d7%aa%d7%a0%d7%90%d7%99-%d7%a9%d7%99%d7%9e%d7%95%d7%a9/" class="woocommerce-privacy-policy-link" target="_blank">מדיניות פרטיות</a> שלנו.</p>',
    'term_label'    => '<span class="woocommerce-terms-and-conditions-checkbox-text">קראתי ואני מסכים לאתר <a href="https://easyline.co.il/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%95%d7%aa%d7%a0%d7%90%d7%99-%d7%a9%d7%99%d7%9e%d7%95%d7%a9/" class="woocommerce-terms-and-conditions-link" target="_blank">תנאי שימוש</a></span>',
    'submit'        => 'שלח',
    'title'         => 'טופס הזמנה ללקוחות מוסדיים',
    'thanks'        => 'תודה! הזמנתך התקבלה וניצור עמך קשר בהקדם',
    'no_correct'    => 'מספר הח.פ. שלך אינו נכון',
  );
};