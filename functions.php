<?php
// Handle WhatsApp number option
if (!get_option('whatsapp_number')) {
    add_option('whatsapp_number', '972500000000'); // Default Israeli number
}

// Add WhatsApp number settings to WordPress admin
add_action('admin_menu', function() {
    add_options_page(
        'WhatsApp Settings',
        'WhatsApp Number',
        'manage_options',
        'whatsapp-settings',
        'whatsapp_settings_page'
    );
});

function whatsapp_settings_page() {
    if (isset($_POST['save_whatsapp_settings'])) {
        if (!wp_verify_nonce($_POST['whatsapp_nonce'], 'save_whatsapp_settings')) {
            wp_die('Security check failed');
        }

        $whatsapp_number = sanitize_text_field($_POST['whatsapp_number']);
        update_option('whatsapp_number', $whatsapp_number);
        echo '<div class="notice notice-success"><p>WhatsApp number saved successfully!</p></div>';
    }

    $current_number = get_option('whatsapp_number', '972500000000');
    ?>
    <div class="wrap">
        <h1>WhatsApp Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('save_whatsapp_settings', 'whatsapp_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="whatsapp_number"><?php _e('WhatsApp Number', 'easyline'); ?></label>
                    </th>
                    <td>
                        <input type="text"
                               id="whatsapp_number"
                               name="whatsapp_number"
                               value="<?php echo esc_attr($current_number); ?>"
                               class="regular-text"
                               placeholder="972500000000" />
                        <p class="description">
                            <?php _e('Enter WhatsApp number without + sign (e.g., 972500000000 for Israel number +972-50-0000000)', 'easyline'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings', 'primary', 'save_whatsapp_settings'); ?>
        </form>
    </div>
    <?php
}

// Theme Setup
function theme_setup() {
	load_theme_textdomain('easyline', get_template_directory() . '/lang');

	add_theme_support('post-thumbnails');
	add_theme_support('menus');
	add_theme_support('woocommerce');
	add_theme_support('title-tag');

	register_nav_menus(array(
		'primary'   => __('Primary Menu', 'easyline'),
		'primary2'  => __('Primary2 Menu', 'easyline'),
		'primary3'  => __('Primary3 Menu', 'easyline'),
		'primary4'  => __('Primary4 Menu', 'easyline'),
		'header'    => __('Header Menu', 'easyline'),
	));

	if (function_exists('add_image_size')) {
		add_image_size('custom', 100, 100);
	}
}
add_action('after_setup_theme', 'theme_setup');

// Init-related: Register taxonomy, enable excerpts, disable unwanted features
function theme_init_cleanup() {
	// Add excerpt support to pages
	add_post_type_support('page', 'excerpt');

	// Disable XML-RPC
	add_filter('xmlrpc_enabled', '__return_false');

	// Remove unused head elements
	remove_action('wp_head', 'wp_shortlink_wp_head', 10);
	remove_action('template_redirect', 'wp_shortlink_header', 11);
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_excerpt', 'wpautop');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

	// Register Custom Taxonomy
	register_taxonomy('taxonomy2', array('sale'), array(
		'labels' => array(
			'name' => 'Genres',
			'singular_name' => 'Genre',
			'search_items' => 'Search Genres',
			'all_items' => 'All Genres',
			'edit_item' => 'Edit Genre',
			'update_item' => 'Update Genre',
			'add_new_item' => 'Add New Genre',
			'new_item_name' => 'New Genre Name',
			'menu_name' => 'Genre',
		),
		'public' => true,
		'hierarchical' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'rewrite' => true,
	));
}
add_action('init', 'theme_init_cleanup');

// Scripts & Styles
function theme_enqueue_assets() {
	// Styles
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style('magnific', get_template_directory_uri() . '/css/magnific-popup.css');
	wp_enqueue_style('slick', get_template_directory_uri() . '/css/slick.css');
	wp_enqueue_style('slick-theme', get_template_directory_uri() . '/css/slick-theme.css');
	wp_enqueue_style('style', get_template_directory_uri() . '/css/style.css');
	wp_enqueue_style('awesome', get_template_directory_uri() . '/css/font-awesome.min.css');

	// Scripts
	wp_enqueue_script('app', get_template_directory_uri() . '/js/app.js', array('jquery'), null, true);
	wp_enqueue_script('magnific', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array('jquery'), null, true);
	wp_enqueue_script('hammer', get_template_directory_uri() . '/js/hammer.min.js', array('jquery'), null, true);
	wp_enqueue_script('mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), null, true);
	wp_enqueue_script('zoom2', get_template_directory_uri() . '/js/ap-image-zoom.js', array('jquery'), null, true);
	wp_enqueue_script('commonjs', get_template_directory_uri() . '/js/common.js', array('jquery'), null, true);

	// Google Maps (only for contact template)
	if (is_page_template('template-contact.php')) {
		wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDrAZjN-RkZB0XRSnjrxvmkX7v3r96n8vk', array(), null, true);
	}

	// Dequeue WP block styles
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-rtl-css');

	// AJAX endpoint
  	wp_localize_script( 'app', 'admin_ajax', array(
		'url' => admin_url('admin-ajax.php'),
	));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets');

// Remove wp-embed script from footer
function theme_remove_wp_embed() {
	wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'theme_remove_wp_embed');

// ACF Options Page
if (function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' => 'Theme Options',
		'menu_title' => 'Theme Options',
		'position' => 2,
		'icon_url' => get_template_directory_uri() . '/img/logo-short.png',
	));
}

function get_page_id_med_products() {
	$page_id = 6660;
	return $page_id;
}

/** Custom Code */
require_once(get_template_directory() . '/inc/custom-code.php');

/** Emails */
require_once(get_template_directory() . '/inc/emails.php');

/** Embed Code */
require_once(get_template_directory() . '/inc/embed-code.php');

/** WooCommerce */
require_once(get_template_directory() . '/inc/woo.php');

/** Medical Post */
require_once(get_template_directory() . '/inc/medical-post.php');
