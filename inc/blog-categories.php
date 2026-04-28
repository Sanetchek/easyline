<?php

/**
 * Returns all blog category terms dynamically.
 *
 * @return array
 */
function easyline_get_blog_category_terms($args = array()) {
	$args = wp_parse_args($args, array(
		'include' => array(),
	));

	$query_args = array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	);

	if (is_array($args['include']) && !empty($args['include'])) {
		$include_ids = array_values(array_unique(array_filter(array_map('absint', $args['include']))));
		if (!empty($include_ids)) {
			$query_args['include'] = $include_ids;
		}
	}

	$terms = get_categories($query_args);

	if (is_wp_error($terms)) {
		return array();
	}

	return $terms;
}

/**
 * Returns blog index page ID using the assigned template.
 *
 * @return int
 */
function easyline_get_blog_index_page_id() {
	static $page_id = null;

	if (null !== $page_id) {
		return $page_id;
	}

	$pages = get_pages(array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'template-blog_page.php',
		'number'     => 1,
	));

	$page_id = !empty($pages) ? (int) $pages[0]->ID : 0;
	return $page_id;
}

/**
 * Returns blog index URL with homepage fallback.
 *
 * @return string
 */
function easyline_get_blog_index_url() {
	$blog_index_page_id = easyline_get_blog_index_page_id();

	if ($blog_index_page_id) {
		return get_permalink($blog_index_page_id);
	}

	return home_url('/');
}

/**
 * Plain-text excerpt for cards: no automatic "…" / "..." suffix.
 *
 * @param int $post_id Post ID.
 * @param int $num_words Max words when building from content.
 * @return string
 */
function easyline_get_post_list_excerpt_plain($post_id, $num_words = 40) {
	$post = get_post($post_id);
	if (!$post) {
		return '';
	}

	if ($post->post_excerpt !== '') {
		$text = wp_strip_all_tags($post->post_excerpt);
	} else {
		$text = wp_strip_all_tags($post->post_content);
		$text = wp_trim_words($text, (int) $num_words, '');
	}

	// WPML-style language markers inside content (e.g. "text[en:]more")
	$text = preg_replace('/\[[a-z]{2}:\]/iu', '', $text);
	// Literal bracket ellipsis from imports / old templates
	$text = preg_replace('/\s*\[\.\.\.\]\s*/u', ' ', $text);
	$text = str_replace(array('[...]', '[…]'), '', $text);
	$text = preg_replace('/\s*(\.{3,}|…)\s*$/u', '', $text);
	$text = preg_replace('/\s{2,}/u', ' ', $text);

	return trim($text);
}

/**
 * Returns selected category IDs for the blog page template.
 *
 * @param int $page_id Page ID.
 * @return int[]
 */
function easyline_get_blog_page_visible_category_ids($page_id = 0) {
	$page_id = absint($page_id);
	if (!$page_id || !function_exists('get_field')) {
		return array();
	}

	$selected = get_field('blog_visible_categories', $page_id);
	if (empty($selected)) {
		return array();
	}

	if (!is_array($selected)) {
		$selected = array($selected);
	}

	$ids = array();
	foreach ($selected as $item) {
		if (is_numeric($item)) {
			$ids[] = absint($item);
			continue;
		}

		if ($item instanceof WP_Term) {
			$ids[] = absint($item->term_id);
			continue;
		}

		if (is_array($item) && isset($item['term_id'])) {
			$ids[] = absint($item['term_id']);
		}
	}

	return array_values(array_unique(array_filter($ids)));
}

/**
 * Returns blog category image URL with fallback.
 *
 * @param int    $term_id Category term ID.
 * @param string $size Image size.
 * @return string
 */
function easyline_get_blog_category_image_url($term_id, $size = 'medium') {
	$image = function_exists('get_field') ? get_field('blog_category_image', 'category_' . $term_id) : '';

	if (is_array($image) && !empty($image['sizes'][$size])) {
		return $image['sizes'][$size];
	}

	if (is_array($image) && !empty($image['url'])) {
		return $image['url'];
	}

	if (is_numeric($image)) {
		$image_url = wp_get_attachment_image_url((int) $image, $size);
		if ($image_url) {
			return $image_url;
		}
	}

	if (is_string($image) && !empty($image)) {
		return $image;
	}

	return get_template_directory_uri() . '/img/shop.png';
}

add_action('acf/init', function() {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_easyline_blog_category',
		'title' => 'Blog Category',
		'fields' => array(
			array(
				'key' => 'field_easyline_blog_category_image',
				'label' => 'Category Image',
				'name' => 'blog_category_image',
				'type' => 'image',
				'instructions' => 'Used on the blog categories index page.',
				'required' => 0,
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
				'mime_types' => 'jpg,jpeg,png,webp',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'category',
				),
			),
		),
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'active' => true,
		'show_in_rest' => 0,
	));

	acf_add_local_field_group(array(
		'key' => 'group_easyline_blog_page_settings',
		'title' => 'Blog Page Settings',
		'fields' => array(
			array(
				'key' => 'field_easyline_blog_visible_categories',
				'label' => 'Visible Categories',
				'name' => 'blog_visible_categories',
				'type' => 'taxonomy',
				'instructions' => 'If empty, all categories are shown on the blog page template.',
				'required' => 0,
				'taxonomy' => 'category',
				'field_type' => 'checkbox',
				'allow_null' => 1,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'multiple' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
				array(
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'template-blog_page.php',
				),
			),
		),
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'active' => true,
		'show_in_rest' => 0,
	));
});
