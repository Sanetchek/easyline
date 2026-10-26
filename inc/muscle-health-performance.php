<?php
/**
 * Muscle Health & Performance page: helpers and ACF field group.
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Resolve an ACF image field value to a URL.
 *
 * @param mixed  $image Image array, attachment ID, or URL string.
 * @param string $size  Image size when resolving attachment IDs.
 * @return string
 */
function easyline_get_acf_image_url($image, $size = 'full') {
	if (is_array($image)) {
		if (!empty($image['ID'])) {
			$url = wp_get_attachment_image_url((int) $image['ID'], $size);
			if ($url) {
				return $url;
			}
		}

		if (!empty($image['url']) && is_string($image['url'])) {
			return $image['url'];
		}

		return '';
	}

	if (is_numeric($image)) {
		$url = wp_get_attachment_image_url((int) $image, $size);
		return $url ? $url : '';
	}

	if (is_string($image) && filter_var($image, FILTER_VALIDATE_URL)) {
		return $image;
	}

	return '';
}

/**
 * Normalize ACF repeater / relationship values to an array.
 *
 * @param mixed $value Raw ACF value.
 * @return array
 */
function easyline_mhp_normalize_rows($value) {
	return is_array($value) ? $value : array();
}

/**
 * Render a trusted decorative line icon.
 *
 * @param string $icon_key Icon key from easyline_mhp_icon_choices().
 * @return string
 */
function easyline_mhp_get_icon_svg($icon_key) {
	$paths = array(
		'running'       => '<circle cx="28" cy="10" r="5"/><path d="M24 18l9 5 7 9M24 18l-7 9-9 2M31 23l-5 12-11 9M26 35l10 9"/>',
		'stretch'       => '<circle cx="27" cy="9" r="5"/><path d="M24 17l-3 15m3-15l12 6 8-5M21 32l-12 12m12-12 15 12M20 23 9 17"/>',
		'dumbbell'      => '<path d="M4 22v12m6-16v20m5-13h26m-26 6h26m5-13v20m6-16v12"/>',
		'scale'         => '<path d="M10 17h36l-3 29H13L10 17Z"/><path d="M19 17a9 9 0 0 1 18 0M28 17l5-6"/>',
		'muscle'        => '<path d="M10 42c5-9 6-18 8-28l7 5-2 10c6-4 11-4 16-1 5 4 7 11 5 18H16c-3 0-5-1-6-4Z"/><path d="M23 29c4 1 7 3 9 7"/>',
		'care'          => '<circle cx="28" cy="13" r="8"/><path d="M14 48c1-12 6-19 14-19s13 7 14 19M19 34l9 8 9-8"/><path d="M28 31v11"/>',
		'scoop'         => '<path d="M8 27h27a10 10 0 0 1-10 10H18A10 10 0 0 1 8 27Z"/><path d="M35 30l14 10M15 25c0-7 4-12 11-12s11 5 11 12"/>',
		'molecule'      => '<circle cx="28" cy="28" r="6"/><circle cx="10" cy="15" r="5"/><circle cx="46" cy="14" r="5"/><circle cx="13" cy="45" r="5"/><circle cx="44" cy="44" r="5"/><path d="M15 18l8 7m10-1 8-7M23 33l-7 8m17-8 7 8"/>',
		'network'       => '<circle cx="28" cy="9" r="5"/><circle cx="10" cy="30" r="5"/><circle cx="46" cy="30" r="5"/><circle cx="19" cy="48" r="5"/><circle cx="38" cy="48" r="5"/><circle cx="28" cy="29" r="6"/><path d="M28 14v9M15 30h7m12 0h7M25 35l-4 8m10-8 5 8"/>',
		'shield'        => '<path d="M28 5 45 12v13c0 12-7 21-17 27-10-6-17-15-17-27V12L28 5Z"/><path d="M28 17v20m-8-10h16"/>',
		'syringe'       => '<path d="m15 40 22-22m-15-5 21 21M34 11l11 11M10 45l7-2-5-5-2 7Z"/><path d="m25 30 6 6"/>',
		'stomach'       => '<path d="M30 7v17c0 5 3 8 8 8 5 0 8-3 8-8 5 9 2 23-11 26-15 3-25-6-25-18 0-8 4-13 11-17v10c0 5 4 8 9 8"/>',
		'healthy-aging' => '<circle cx="28" cy="12" r="7"/><path d="M15 50c0-12 5-21 13-21s13 9 13 21M20 31l8 10 8-10"/><path d="M28 40v10m-5-30c3 2 7 2 10 0"/>',
	);

	if (!isset($paths[$icon_key])) {
		return '';
	}

	return '<svg viewBox="0 0 56 56" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths[$icon_key] . '</g></svg>';
}

/**
 * Whether a Muscle Health section should render.
 *
 * @param int    $post_id Page ID.
 * @param string $prefix  Field name prefix, e.g. mhp_hero.
 * @return bool
 */
function easyline_mhp_section_enabled($post_id, $prefix) {
	$enabled = get_field($prefix . '_enabled', $post_id);
	if ($enabled === null || $enabled === '') {
		return true;
	}

	return (bool) $enabled;
}

/**
 * Resolve ACF link field to url/title/target.
 *
 * @param mixed  $link     ACF link array or URL string.
 * @param string $fallback Fallback label.
 * @return array{url:string,title:string,target:string}
 */
function easyline_mhp_resolve_link($link, $fallback = '') {
	if (is_array($link)) {
		return array(
			'url'    => isset($link['url']) ? (string) $link['url'] : '',
			'title'  => isset($link['title']) && $link['title'] !== '' ? (string) $link['title'] : $fallback,
			'target' => !empty($link['target']) ? (string) $link['target'] : '_self',
		);
	}

	if (is_string($link) && $link !== '') {
		return array(
			'url'    => $link,
			'title'  => $fallback,
			'target' => '_self',
		);
	}

	return array(
		'url'    => '',
		'title'  => $fallback,
		'target' => '_self',
	);
}

/**
 * Build product feature lines from short description.
 *
 * @param WC_Product $product Product instance.
 * @return array<int,string>
 */
function easyline_mhp_product_feature_lines($product) {
	$raw = $product->get_short_description();
	if ($raw === '') {
		$identity = strtolower((string) preg_replace('/[^a-z0-9]/i', '', $product->get_name() . $product->get_slug()));
		$defaults = array(
			'easywhey' => array(
				'חלבון מי גבינה איכותי בלבד',
				'פורמולה נקייה',
				'עשיר באופן טבעי',
				'ערך ביולוגי גבוה לספיגה מיטבית',
				'מתאים להשלמת צריכת החלבון היומית',
				'מתאים לאחר אימון או בין ארוחות',
			),
			'easywhay' => array(
				'חלבון מי גבינה איכותי בלבד',
				'פורמולה נקייה',
				'עשיר באופן טבעי',
				'ערך ביולוגי גבוה לספיגה מיטבית',
				'מתאים להשלמת צריכת החלבון היומית',
				'מתאים לאחר אימון או בין ארוחות',
			),
			'promuss' => array(
				'23 גרם חלבון מי גבינה',
				'HMB',
				'ארגינין',
				'גלוטמין',
				'ויטמינים ומינרלים',
			),
		);

		foreach ($defaults as $product_key => $features) {
			if (strpos($identity, $product_key) !== false) {
				return $features;
			}
		}

		return array();
	}

	$plain = wp_strip_all_tags(
		str_ireplace(
			array('<br>', '<br/>', '<br />', '</li>', '</p>'),
			"\n",
			$raw
		)
	);
	$lines = preg_split('/\r\n|\r|\n/', $plain);
	if (!is_array($lines)) {
		return array();
	}

	$features = array();
	foreach ($lines as $line) {
		$line = trim((string) $line);
		$line = preg_replace('/^[\x{2022}\-\x{2013}\x{2014}*]+/u', '', $line);
		$line = trim((string) $line);
		if ($line !== '') {
			$features[] = $line;
		}
	}

	return $features;
}

/**
 * Get the concise product name used in card CTA labels.
 *
 * Product titles commonly append a size or variant after a dash.
 *
 * @param string $product_name Full WooCommerce product name.
 * @return string
 */
function easyline_mhp_product_short_name($product_name) {
	$parts = preg_split('/\s+[-–—]\s+/u', trim((string) $product_name), 2);
	if (!is_array($parts) || empty($parts[0])) {
		return trim((string) $product_name);
	}

	return trim((string) $parts[0]);
}

/**
 * Find the EasyWhey and ProMuss products shown in the design.
 *
 * @return array
 */
function easyline_mhp_default_product_ids() {
	static $product_ids = null;

	if (is_array($product_ids)) {
		return $product_ids;
	}

	global $wpdb;

	$product_ids = array();
	$titles      = array('EasyWhey', 'EasyWhay', 'ProMuss');

	foreach ($titles as $title) {
		$found_id = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' AND post_title = %s LIMIT 1",
				$title
			)
		);
		if ($found_id > 0) {
			$product_ids[] = $found_id;
		}
	}

	return array_slice(array_values(array_unique($product_ids)), 0, 2);
}

/**
 * Build starter rows for the product card repeater.
 *
 * @return array
 */
function easyline_mhp_default_solution_rows() {
	$rows = array();

	foreach (easyline_mhp_default_product_ids() as $product_id) {
		$rows[] = array(
			'product'  => (int) $product_id,
			'features' => array(),
		);
	}

	return $rows;
}

/**
 * Get the latest articles for the initial Knowledge section.
 *
 * @return array
 */
function easyline_mhp_default_article_ids() {
	return array_map('intval', get_posts(array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'fields'         => 'ids',
	)));
}

/**
 * Initial field values based on the approved page design.
 *
 * Values are used only while the corresponding post meta does not exist.
 * Saving the page turns them into ordinary editable ACF values.
 *
 * @return array
 */
function easyline_mhp_default_content() {
	static $defaults = null;

	if (is_array($defaults)) {
		return $defaults;
	}

	$articles_url = function_exists('easyline_get_blog_index_url')
		? easyline_get_blog_index_url()
		: home_url('/blog/');
	if (!$articles_url) {
		$articles_url = home_url('/blog/');
	}

	$defaults = array(
		'mhp_hero_title'    => 'EasyLine Muscle Health & Performance',
		'mhp_hero_subtitle' => 'Clinical Nutrition meets Sports Nutrition',
		'mhp_hero_text'     => 'החיבור המדויק בין תזונה קלינית לתזונת ספורט, לשמירה על בריאות השריר, כוח ותפקוד בכל שלב בחיים.',
		'mhp_hero_cta'      => array(
			'url'    => '#mhp-solutions',
			'title'  => 'לפתרונות שלנו',
			'target' => '',
		),
		'mhp_why_title'     => 'למה',
		'mhp_why_items'     => array(
			array('icon' => 0, 'icon_key' => 'running', 'title' => 'שמירה על אורח חיים פעיל'),
			array('icon' => 0, 'icon_key' => 'stretch', 'title' => 'חיזוק הגוף לאורך זמן'),
			array('icon' => 0, 'icon_key' => 'dumbbell', 'title' => 'תפקוד יומיומי מיטבי'),
			array('icon' => 0, 'icon_key' => 'scale', 'title' => 'שמירה על משקל גוף תקין'),
			array('icon' => 0, 'icon_key' => 'muscle', 'title' => 'מניעת אובדן מסת שריר'),
			array('icon' => 0, 'icon_key' => 'care', 'title' => 'התאוששות ובריאות'),
		),
		'mhp_science_title' => 'המדע שמאחורי בריאות השריר',
		'mhp_science_items' => array(
			array(
				'icon'  => 0,
				'icon_key' => 'scoop',
				'title' => 'Whey Protein',
				'text'  => 'חלבון מי גבינה איכותי בעל ערך ביולוגי גבוה, התומך בבניית השריר ובהתאוששות.',
			),
			array(
				'icon'  => 0,
				'icon_key' => 'molecule',
				'title' => 'Leucine',
				'text'  => 'חומצת אמינו חיונית הממלאת תפקיד מרכזי בהפעלת תהליך בניית חלבון השריר.',
			),
			array(
				'icon'  => 0,
				'icon_key' => 'network',
				'title' => 'BCAA',
				'text'  => 'חומצות אמינו מסועפות התומכות בשמירה על מסת השריר ובהתאוששות.',
			),
			array(
				'icon'  => 0,
				'icon_key' => 'shield',
				'title' => 'HMB',
				'text'  => 'מטבוליט פעיל של לאוצין המסייע בשמירה על מסת השריר, הכוח והתפקוד.',
			),
		),
		'mhp_audience_title' => 'למי זה מתאים?',
		'mhp_audience_items' => array(
			array('icon' => 0, 'icon_key' => 'running', 'title' => 'ספורטאים'),
			array('icon' => 0, 'icon_key' => 'dumbbell', 'title' => 'מתאמנים'),
			array('icon' => 0, 'icon_key' => 'stretch', 'title' => 'שיקום'),
			array('icon' => 0, 'icon_key' => 'syringe', 'title' => 'מטופלים רפואיים'),
			array('icon' => 0, 'icon_key' => 'stomach', 'title' => 'בריאטריה'),
			array('icon' => 0, 'icon_key' => 'healthy-aging', 'title' => 'Healthy Aging'),
		),
		'mhp_solutions_title'     => 'הפתרונות שלנו',
		'mhp_solutions_items'     => easyline_mhp_default_solution_rows(),
		'mhp_solutions_cta_label' => 'לפרטים',
		'mhp_quality_title'       => 'איכות שאפשר לסמוך עליה',
		'mhp_quality_text'        => 'מיוצר בישראל תחת הסטנדרטים המחמירים ביותר לאיכות ובטיחות.',
		'mhp_quality_logos'       => array(
			array('image' => 0, 'name' => 'GMP', 'alt' => 'GMP'),
			array('image' => 0, 'name' => 'HACCP', 'alt' => 'HACCP'),
			array('image' => 0, 'name' => 'ISO 9001:2015', 'alt' => 'ISO 9001:2015 Certified'),
		),
		'mhp_knowledge_title'     => 'הידע שמאחורי',
		'mhp_knowledge_cta'       => array(
			'url'    => $articles_url,
			'title'  => 'לכל המאמרים',
			'target' => '',
		),
		'mhp_knowledge_posts'     => easyline_mhp_default_article_ids(),
		'mhp_knowledge_read_more' => 'קרא עוד',
		'mhp_faq_title'           => 'שאלות נפוצות',
		'mhp_faq_items'           => array(
			array(
				'question' => 'למי מיועדים פתרונות Muscle Health & Performance?',
				'answer'   => '<p>הפתרונות מיועדים לאנשים המעוניינים לתמוך בבריאות השריר, בכוח ובהתאוששות. במצב רפואי או תזונתי מיוחד מומלץ להתייעץ עם איש מקצוע.</p>',
			),
			array(
				'question' => 'האם ניתן להשתמש במוצרים על בסיס יומי?',
				'answer'   => '<p>ניתן לשלב את המוצרים בתפריט היומי בהתאם להוראות השימוש ולצרכים האישיים.</p>',
			),
			array(
				'question' => 'מתי מומלץ לצרוך חלבון?',
				'answer'   => '<p>אפשר לצרוך חלבון לאורך היום, לאחר פעילות או כחלק מארוחה, בהתאם לכמות החלבון היומית הנדרשת.</p>',
			),
			array(
				'question' => 'האם המוצרים כשרים?',
				'answer'   => '<p>יש לבדוק את סימון הכשרות המעודכן על גבי אריזת המוצר.</p>',
			),
			array(
				'question' => 'האם אפשר לשלב את המוצרים עם תזונה רגילה?',
				'answer'   => '<p>כן. המוצרים נועדו להשתלב כחלק מתזונה מאוזנת ולא להחליף ייעוץ תזונתי אישי.</p>',
			),
		),
	);

	return $defaults;
}

/**
 * Map default field names to their stable local ACF keys.
 *
 * @return array
 */
function easyline_mhp_default_field_keys() {
	return array(
		'mhp_hero_title'             => 'field_easyline_mhp_hero_title',
		'mhp_hero_subtitle'          => 'field_easyline_mhp_hero_subtitle',
		'mhp_hero_text'              => 'field_easyline_mhp_hero_text',
		'mhp_hero_cta'               => 'field_easyline_mhp_hero_cta',
		'mhp_why_title'              => 'field_easyline_mhp_why_title',
		'mhp_why_items'              => 'field_easyline_mhp_why_items',
		'mhp_science_title'          => 'field_easyline_mhp_science_title',
		'mhp_science_items'          => 'field_easyline_mhp_science_items',
		'mhp_audience_title'         => 'field_easyline_mhp_audience_title',
		'mhp_audience_items'         => 'field_easyline_mhp_audience_items',
		'mhp_solutions_title'        => 'field_easyline_mhp_solutions_title',
		'mhp_solutions_items'        => 'field_easyline_mhp_solutions_items',
		'mhp_solutions_cta_label'    => 'field_easyline_mhp_solutions_cta_label',
		'mhp_quality_title'          => 'field_easyline_mhp_quality_title',
		'mhp_quality_text'           => 'field_easyline_mhp_quality_text',
		'mhp_quality_logos'          => 'field_easyline_mhp_quality_logos',
		'mhp_knowledge_title'        => 'field_easyline_mhp_knowledge_title',
		'mhp_knowledge_cta'          => 'field_easyline_mhp_knowledge_cta',
		'mhp_knowledge_posts'        => 'field_easyline_mhp_knowledge_posts',
		'mhp_knowledge_read_more'    => 'field_easyline_mhp_knowledge_read_more',
		'mhp_faq_title'              => 'field_easyline_mhp_faq_title',
		'mhp_faq_items'              => 'field_easyline_mhp_faq_items',
	);
}

/**
 * Persist the initial design content for a page that has not been populated.
 *
 * @param int $post_id Page ID.
 * @return void
 */
function easyline_mhp_seed_page_defaults($post_id) {
	if (!function_exists('update_field') || get_page_template_slug($post_id) !== 'template-muscle-health-performance.php') {
		return;
	}

	$defaults   = easyline_mhp_default_content();
	$field_keys = easyline_mhp_default_field_keys();

	foreach ($defaults as $field_name => $default_value) {
		if (metadata_exists('post', $post_id, $field_name) || !isset($field_keys[$field_name])) {
			continue;
		}

		update_field($field_keys[$field_name], $default_value, $post_id);
	}
}

/**
 * Seed the page after its template is assigned and saved.
 *
 * @param int     $post_id Page ID.
 * @param WP_Post $post    Page post object.
 * @param bool    $update  Whether this is an existing post.
 * @return void
 */
function easyline_mhp_seed_page_on_save($post_id, $post, $update) {
	unset($update);

	if (
		(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		|| wp_is_post_revision($post_id)
		|| !$post instanceof WP_Post
		|| !current_user_can('edit_post', $post_id)
	) {
		return;
	}

	easyline_mhp_seed_page_defaults($post_id);
}
add_action('save_post_page', 'easyline_mhp_seed_page_on_save', 20, 3);

/**
 * Seed pages created before the starter content was introduced.
 *
 * @return void
 */
function easyline_mhp_seed_existing_pages() {
	if (
		!current_user_can('edit_pages')
		|| !function_exists('update_field')
		|| get_option('easyline_mhp_defaults_seeded_v1')
	) {
		return;
	}

	$page_ids = get_posts(array(
		'post_type'      => 'page',
		'post_status'    => array('publish', 'draft', 'private', 'pending'),
		'posts_per_page' => -1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'template-muscle-health-performance.php',
		'fields'         => 'ids',
	));

	foreach ($page_ids as $page_id) {
		easyline_mhp_seed_page_defaults((int) $page_id);
	}

	update_option('easyline_mhp_defaults_seeded_v1', 1, false);
}
add_action('admin_init', 'easyline_mhp_seed_existing_pages');

/**
 * Read a page field with the design value as a pre-save fallback.
 *
 * @param string $field_name ACF field name.
 * @param int    $post_id    Page ID.
 * @return mixed
 */
function easyline_mhp_get_field($field_name, $post_id) {
	$value = get_field($field_name, $post_id);
	if (metadata_exists('post', $post_id, $field_name)) {
		return $value;
	}

	$defaults = easyline_mhp_default_content();
	return array_key_exists($field_name, $defaults) ? $defaults[$field_name] : $value;
}

/**
 * Build tab + enabled toggle fields for a section.
 *
 * @param string $key_base Field key base without field_ prefix segment.
 * @param string $label    Tab label.
 * @param string $name     Field name prefix.
 * @return array
 */
function easyline_mhp_section_tab_fields($key_base, $label, $name) {
	return array(
		array(
			'key'   => 'field_easyline_mhp_' . $key_base . '_tab',
			'label' => $label,
			'type'  => 'tab',
			'placement' => 'top',
			'endpoint'  => 0,
		),
		array(
			'key'           => 'field_easyline_mhp_' . $key_base . '_enabled',
			'label'         => __('Show section', 'easyline'),
			'name'          => $name . '_enabled',
			'type'          => 'true_false',
			'default_value' => 1,
			'ui'            => 1,
		),
	);
}

/**
 * Register Muscle Health & Performance ACF fields.
 */
function easyline_mhp_register_acf_fields() {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	$fields = array();

	// Hero.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('hero', __('Hero', 'easyline'), 'mhp_hero'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_hero_title',
		'label' => __('Title', 'easyline'),
		'name'  => 'mhp_hero_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_hero_subtitle',
		'label' => __('Subtitle', 'easyline'),
		'name'  => 'mhp_hero_subtitle',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_hero_text',
		'label' => __('Description', 'easyline'),
		'name'  => 'mhp_hero_text',
		'type'  => 'textarea',
		'rows'  => 4,
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_hero_cta',
		'label' => __('CTA button', 'easyline'),
		'name'  => 'mhp_hero_cta',
		'type'  => 'link',
		'return_format' => 'array',
	);
	$fields[] = array(
		'key'           => 'field_easyline_mhp_hero_image',
		'label'         => __('Hero image', 'easyline'),
		'name'          => 'mhp_hero_image',
		'type'          => 'image',
		'instructions'  => __('Recommended landscape image, at least 820 × 680 px.', 'easyline'),
		'return_format' => 'id',
		'preview_size'  => 'medium',
		'library'       => 'all',
		'mime_types'    => 'jpg,jpeg,png,webp',
	);

	// Why.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('why', __('Why', 'easyline'), 'mhp_why'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_why_title',
		'label' => __('Section title', 'easyline'),
		'name'  => 'mhp_why_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'          => 'field_easyline_mhp_why_items',
		'label'        => __('Items', 'easyline'),
		'name'         => 'mhp_why_items',
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => __('Add item', 'easyline'),
		'sub_fields'   => array(
			array(
				'key'           => 'field_easyline_mhp_why_item_icon',
				'label'         => __('Icon', 'easyline'),
				'name'          => 'icon',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
				'mime_types'    => 'jpg,jpeg,png,webp,svg',
			),
			array(
				'key'   => 'field_easyline_mhp_why_item_title',
				'label' => __('Title', 'easyline'),
				'name'  => 'title',
				'type'  => 'text',
			),
		),
	);

	// Science.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('science', __('Science', 'easyline'), 'mhp_science'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_science_title',
		'label' => __('Section title', 'easyline'),
		'name'  => 'mhp_science_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'          => 'field_easyline_mhp_science_items',
		'label'        => __('Items', 'easyline'),
		'name'         => 'mhp_science_items',
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => __('Add item', 'easyline'),
		'sub_fields'   => array(
			array(
				'key'           => 'field_easyline_mhp_science_item_icon',
				'label'         => __('Icon', 'easyline'),
				'name'          => 'icon',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
				'mime_types'    => 'jpg,jpeg,png,webp,svg',
			),
			array(
				'key'   => 'field_easyline_mhp_science_item_title',
				'label' => __('Title', 'easyline'),
				'name'  => 'title',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_easyline_mhp_science_item_text',
				'label' => __('Description', 'easyline'),
				'name'  => 'text',
				'type'  => 'textarea',
				'rows'  => 4,
			),
		),
	);

	// Audience.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('audience', __('Audience', 'easyline'), 'mhp_audience'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_audience_title',
		'label' => __('Section title', 'easyline'),
		'name'  => 'mhp_audience_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'          => 'field_easyline_mhp_audience_items',
		'label'        => __('Items', 'easyline'),
		'name'         => 'mhp_audience_items',
		'type'         => 'repeater',
		'layout'       => 'table',
		'button_label' => __('Add item', 'easyline'),
		'sub_fields'   => array(
			array(
				'key'           => 'field_easyline_mhp_audience_item_icon',
				'label'         => __('Icon', 'easyline'),
				'name'          => 'icon',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
				'mime_types'    => 'jpg,jpeg,png,webp,svg',
			),
			array(
				'key'   => 'field_easyline_mhp_audience_item_title',
				'label' => __('Label', 'easyline'),
				'name'  => 'title',
				'type'  => 'text',
			),
		),
	);

	// Solutions.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('solutions', __('Solutions', 'easyline'), 'mhp_solutions'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_solutions_title',
		'label' => __('Section title', 'easyline'),
		'name'  => 'mhp_solutions_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'          => 'field_easyline_mhp_solutions_items',
		'label'        => __('Product cards', 'easyline'),
		'name'         => 'mhp_solutions_items',
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => __('Add product card', 'easyline'),
		'min'          => 0,
		'max'          => 4,
		'sub_fields'   => array(
			array(
				'key'           => 'field_easyline_mhp_solutions_product',
				'label'         => __('Product', 'easyline'),
				'name'          => 'product',
				'type'          => 'post_object',
				'post_type'     => array('product'),
				'return_format' => 'id',
				'ui'            => 1,
				'required'      => 1,
			),
			array(
				'key'          => 'field_easyline_mhp_solutions_features',
				'label'        => __('Product features', 'easyline'),
				'name'         => 'features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => __('Add feature', 'easyline'),
				'sub_fields'   => array(
					array(
						'key'      => 'field_easyline_mhp_solutions_feature_text',
						'label'    => __('Feature', 'easyline'),
						'name'     => 'text',
						'type'     => 'text',
						'required' => 1,
					),
				),
			),
		),
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_solutions_cta_label',
		'label' => __('Product CTA label', 'easyline'),
		'name'  => 'mhp_solutions_cta_label',
		'type'  => 'text',
		'instructions' => __('Used on product cards. Falls back to a translated default.', 'easyline'),
	);

	// Quality.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('quality', __('Quality', 'easyline'), 'mhp_quality'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_quality_title',
		'label' => __('Title', 'easyline'),
		'name'  => 'mhp_quality_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_quality_text',
		'label' => __('Text', 'easyline'),
		'name'  => 'mhp_quality_text',
		'type'  => 'textarea',
		'rows'  => 3,
	);
	$fields[] = array(
		'key'          => 'field_easyline_mhp_quality_logos',
		'label'        => __('Certification logos', 'easyline'),
		'name'         => 'mhp_quality_logos',
		'type'         => 'repeater',
		'layout'       => 'table',
		'button_label' => __('Add logo', 'easyline'),
		'sub_fields'   => array(
			array(
				'key'           => 'field_easyline_mhp_quality_logo_image',
				'label'         => __('Image', 'easyline'),
				'name'          => 'image',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
				'mime_types'    => 'jpg,jpeg,png,webp,svg',
			),
			array(
				'key'   => 'field_easyline_mhp_quality_logo_name',
				'label' => __('Certification name', 'easyline'),
				'name'  => 'name',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_easyline_mhp_quality_logo_alt',
				'label' => __('Alt text', 'easyline'),
				'name'  => 'alt',
				'type'  => 'text',
			),
		),
	);

	// Knowledge.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('knowledge', __('Knowledge', 'easyline'), 'mhp_knowledge'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_knowledge_title',
		'label' => __('Section title', 'easyline'),
		'name'  => 'mhp_knowledge_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_knowledge_cta',
		'label' => __('See all articles link', 'easyline'),
		'name'  => 'mhp_knowledge_cta',
		'type'  => 'link',
		'return_format' => 'array',
	);
	$fields[] = array(
		'key'           => 'field_easyline_mhp_knowledge_posts',
		'label'         => __('Articles', 'easyline'),
		'name'          => 'mhp_knowledge_posts',
		'type'          => 'relationship',
		'post_type'     => array('post'),
		'filters'       => array('search', 'taxonomy'),
		'return_format' => 'id',
		'min'           => 0,
		'max'           => 0,
	);
	$fields[] = array(
		'key'   => 'field_easyline_mhp_knowledge_read_more',
		'label' => __('Read more label', 'easyline'),
		'name'  => 'mhp_knowledge_read_more',
		'type'  => 'text',
	);

	// FAQ.
	$fields = array_merge($fields, easyline_mhp_section_tab_fields('faq', __('FAQ', 'easyline'), 'mhp_faq'));
	$fields[] = array(
		'key'   => 'field_easyline_mhp_faq_title',
		'label' => __('Section title', 'easyline'),
		'name'  => 'mhp_faq_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'          => 'field_easyline_mhp_faq_items',
		'label'        => __('Questions', 'easyline'),
		'name'         => 'mhp_faq_items',
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => __('Add question', 'easyline'),
		'sub_fields'   => array(
			array(
				'key'   => 'field_easyline_mhp_faq_item_question',
				'label' => __('Question', 'easyline'),
				'name'  => 'question',
				'type'  => 'text',
			),
			array(
				'key'          => 'field_easyline_mhp_faq_item_answer',
				'label'        => __('Answer', 'easyline'),
				'name'         => 'answer',
				'type'         => 'wysiwyg',
				'tabs'         => 'all',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
		),
	);

	acf_add_local_field_group(array(
		'key'    => 'group_easyline_muscle_health_performance',
		'title'  => __('Muscle Health & Performance', 'easyline'),
		'fields' => $fields,
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'template-muscle-health-performance.php',
				),
			),
		),
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
		'show_in_rest'          => 0,
	));
}
add_action('acf/init', 'easyline_mhp_register_acf_fields');
