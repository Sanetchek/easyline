<?php
// Custom Post Type: Medical Product
add_action('init', function () {
    register_post_type('med_product', [
        'labels' => [
            'name' => __('Medical Products', 'easyline'),
            'singular_name' => __('Medical Product', 'easyline'),
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'med-products'],
        'menu_icon' => 'dashicons-products',
    ]);
});

// Custom Taxonomy: Category for Med Products
add_action('init', function () {
    register_taxonomy('med_category', ['med_product'], [
        'labels' => [
            'name' => __('Product Categories', 'easyline'),
            'singular_name' => __('Product Category', 'easyline'),
        ],
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'med-category'],
    ]);
});

// Add field for image
add_action('med_category_add_form_fields', function () {
    ?>
        <div class="form-field term-group">
            <label for="med_category_image_id"><?php _e('Category Image', 'easyline'); ?></label>
            <input type="hidden" id="med_category_image_id" name="med_category_image_id" value="" />
            <div id="med_category_image_preview"></div>
            <button type="button" class="button upload_image_button"><?php _e('Upload Image', 'easyline'); ?></button>
            <button type="button" class="button remove_image_button" style="display:none;"><?php _e('Remove Image', 'easyline'); ?></button>
        </div>
        <?php
});

// Edit form
add_action('med_category_edit_form_fields', function ($term) {
    $image_id = get_term_meta($term->term_id, 'med_category_image_id', true);
    ?>
        <tr class="form-field term-group-wrap">
            <th scope="row"><label for="med_category_image_id"><?php _e('Category Image', 'easyline'); ?></label></th>
            <td>
                <input type="hidden" id="med_category_image_id" name="med_category_image_id" value="<?php echo esc_attr($image_id); ?>" />
                <div id="med_category_image_preview">
                    <?php if ($image_id)
                        echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                </div>
                <button type="button" class="button upload_image_button"><?php _e('Upload Image', 'easyline'); ?></button>
                <button type="button" class="button remove_image_button" <?php if (!$image_id)
                    echo 'style="display:none;"'; ?>><?php _e('Remove Image', 'easyline'); ?></button>
            </td>
        </tr>
        <?php
}, 10, 2);

// Save meta
add_action('created_med_category', 'save_med_category_image_id');
add_action('edited_med_category', 'save_med_category_image_id');
function save_med_category_image_id($term_id)
{
    if (isset($_POST['med_category_image_id'])) {
        update_term_meta($term_id, 'med_category_image_id', (int) $_POST['med_category_image_id']);
    }
}

// Add column in list
add_filter('manage_edit-med_category_columns', function ($columns) {
    $columns['image'] = __('Image', 'easyline');
    return $columns;
});
add_filter('manage_med_category_custom_column', function ($content, $column_name, $term_id) {
    if ($column_name === 'image') {
        $image_id = get_term_meta($term_id, 'med_category_image_id', true);
        if ($image_id)
            $content = wp_get_attachment_image($image_id, [50, 0]);
    }
    return $content;
}, 10, 3);

// Add med_category column to med_product posts list
add_filter('manage_med_product_posts_columns', function ($columns) {
    // Insert med_category column after title
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['med_category'] = __('Categories', 'easyline');
        }
    }
    return $new_columns;
});

// Display med_category content in the column
add_action('manage_med_product_posts_custom_column', function ($column_name, $post_id) {
    if ($column_name === 'med_category') {
        $categories = get_the_terms($post_id, 'med_category');
        if ($categories && !is_wp_error($categories)) {
            $category_links = [];
            foreach ($categories as $category) {
                $edit_link = get_edit_term_link($category->term_id, 'med_category');
                $category_links[] = '<a href="' . esc_url($edit_link) . '">' . esc_html($category->name) . '</a>';
            }
            echo implode(', ', $category_links);
        } else {
            echo '<span style="color: #999;">' . __('No categories', 'easyline') . '</span>';
        }
    }
}, 10, 2);

// Make med_category column sortable
add_filter('manage_edit-med_product_sortable_columns', function ($columns) {
    $columns['med_category'] = 'med_category';
    return $columns;
});

// Handle sorting by med_category
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');
    if ($orderby === 'med_category') {
        $query->set('meta_key', 'med_category');
        $query->set('orderby', 'meta_value');
    }
});

// JS + media uploader + color picker
add_action('admin_enqueue_scripts', function ($hook) {
    // For taxonomy pages
    if ('edit-tags.php' === $hook || 'term.php' === $hook) {
        wp_enqueue_media();
    }

    // For post edit pages
    global $post_type;
    if ('med_product' === $post_type && ('post.php' === $hook || 'post-new.php' === $hook)) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // Add color picker initialization script
        wp_add_inline_script('wp-color-picker', "
        jQuery(document).ready(function($) {
            // Initialize WordPress color picker
            $('#mp_title_color').wpColorPicker({
                defaultColor: '#234e32',
                change: function(event, ui) {
                    // Color picker will handle the UI automatically
                },
                clear: function() {
                    // Reset to default color
                    $(this).val('#234e32');
                }
            });
        });
        ");
    }
    wp_add_inline_script('jquery-core', "
    jQuery(document).ready(function($){
        var frame;
        function refreshImage(id, url){
            $('#med_category_image_id').val(id);
            $('#med_category_image_preview').html('<img src=\"'+url+'\" style=\"max-width:150px;height:auto;\" />');
            $('.remove_image_button').show();
        }
        $('.upload_image_button').on('click', function(e){
            e.preventDefault();
            frame = wp.media({
                title: 'Select or Upload Category Image',
                button: { text: 'Use this image' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                refreshImage(attachment.id, attachment.sizes.thumbnail.url);
            });
            frame.open();
        });
        $('.remove_image_button').on('click', function(){
            $('#med_category_image_id').val('');
            $('#med_category_image_preview').html('');
            $(this).hide();
        });
    });
    ");
});

/**
 * Extra meta fields for med_product:
 * - Size (label + value)
 * - YERPA code (label + value)
 * - External link (label + URL) + dropdown of Woo products to auto-fill URL
 */

// Register meta so it's saved safely and available in REST.
add_action('init', function () {
    $fields = [
        'mp_size_label',
        'mp_size_value',
        'mp_code_label',
        'mp_code_value',
        'mp_link_label',
        'mp_link_url',
        'mp_title_color',
        'mp_back_link_url',
    ];
    foreach ($fields as $key) {
        register_post_meta('med_product', $key, [
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'auth_callback'=> function() { return current_user_can('edit_posts'); },
        ]);
    }
});

// Add meta box to med_product editor
add_action('add_meta_boxes', function () {
    add_meta_box(
        'med_product_extra_fields',
        __('Med Product - Extra Fields', 'easyline'),
        'render_med_product_extra_fields_mb',
        'med_product',
        'normal',
        'high'
    );
});

// Render the meta box HTML
function render_med_product_extra_fields_mb(WP_Post $post)
{
    wp_nonce_field('save_med_product_extra_fields', 'med_product_extra_fields_nonce');

    $size_label = get_post_meta($post->ID, 'mp_size_label', true) ?: __('גודל אריזה', 'easyline');
    $size_value = get_post_meta($post->ID, 'mp_size_value', true) ?: '';
    $code_label = get_post_meta($post->ID, 'mp_code_label', true) ?: __('קוד ירפא', 'easyline');
    $code_value = get_post_meta($post->ID, 'mp_code_value', true) ?: '';
    $link_label = get_post_meta($post->ID, 'mp_link_label', true) ?: __('לעמוד המוצר לחץ >', 'easyline');
    $link_url = get_post_meta($post->ID, 'mp_link_url', true) ?: '';
    $title_color = get_post_meta($post->ID, 'mp_title_color', true) ?: '#234e32';
    $back_link_url = get_post_meta($post->ID, 'mp_back_link_url', true) ?: '';

    $woo_products = [];
    if (post_type_exists('product')) {
        $woo_products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 300,
            'orderby' => 'title',
            'order' => 'ASC',
            'fields' => 'ids',
        ]);
    }
    ?>
        <style>
          .mp-grid { display:grid; grid-template-columns: 1fr 2fr; gap:8px 16px; align-items:center; }
          .mp-row { margin-bottom:12px; }
          .mp-row label { font-weight:600; display:inline-block; margin-bottom:4px; }
          .mp-help { font-size:11px; color:#666; margin-top:4px; }
          .full { grid-column: 1 / -1; }
          .color-picker-wrapper { display:flex; align-items:center; gap:10px; }
        </style>

        <div class="mp-row">
          <label><?php esc_html_e('Title Color', 'easyline'); ?></label>
          <div class="color-picker-wrapper">
            <input type="text" name="mp_title_color" id="mp_title_color" value="<?php echo esc_attr($title_color); ?>" class="color-picker-field" />
            <span class="mp-help"><?php esc_html_e('Choose color for the product title', 'easyline'); ?></span>
          </div>
        </div>

        <div class="mp-row">
          <label><?php esc_html_e('Package Size (Label + Value)', 'easyline'); ?></label>
          <div class="mp-grid">
            <input type="text" name="mp_size_label" value="<?php echo esc_attr($size_label); ?>" placeholder="<?php esc_html_e('גודל אריזה', 'easyline'); ?>" />
            <input type="text" name="mp_size_value" value="<?php echo esc_attr($size_value); ?>" placeholder="<?php esc_html_e('1000 מ״ל', 'easyline'); ?>" />
          </div>
        </div>

        <div class="mp-row">
          <label><?php esc_html_e('YERPA Code (Label + Value)', 'easyline'); ?></label>
          <div class="mp-grid">
            <input type="text" name="mp_code_label" value="<?php echo esc_attr($code_label); ?>" placeholder="<?php esc_html_e('קוד ירפא', 'easyline'); ?>" />
            <input type="text" name="mp_code_value" value="<?php echo esc_attr($code_value); ?>" placeholder="<?php esc_html_e('67282', 'easyline'); ?>" />
          </div>
        </div>

        <div class="mp-row">
          <label><?php esc_html_e('External Product Link (Label + URL)', 'easyline'); ?></label>
          <div class="mp-grid">
            <input type="text" name="mp_link_label" value="<?php echo esc_attr($link_label); ?>" placeholder="<?php esc_html_e('לעמוד המוצר לחץ >', 'easyline'); ?>" />
            <input type="url" name="mp_link_url" id="mp_link_url" value="<?php echo esc_url($link_url); ?>"
                   list="mp_link_datalist" placeholder="<?php esc_html_e('https://easyline.co.il/product/...', 'easyline'); ?>">
            <datalist id="mp_link_datalist">
              <?php
              if (!empty($woo_products)) {
                  foreach ($woo_products as $pid) {
                      $title = get_the_title($pid);
                      $url = get_permalink($pid);
                      printf('<option value="%s" label="%s (ID:%d)"></option>', esc_attr($url), esc_attr($title), (int) $pid);
                  }
              }
              ?>
            </datalist>
            <div class="full mp-help">
              <?php esc_html_e('Start typing to see suggestions from Woo products, or paste any URL manually. No extra field needed.', 'easyline'); ?>
            </div>
          </div>
        </div>

        <div class="mp-row">
          <label><?php esc_html_e('Custom "Back to Products" URL', 'easyline'); ?></label>
          <div class="mp-grid">
            <input type="url" name="mp_back_link_url" id="mp_back_link_url" value="<?php echo esc_url($back_link_url); ?>"
                   class="full" placeholder="<?php esc_html_e('https://easyline.co.il/...', 'easyline'); ?>">
            <div class="full mp-help">
              <?php esc_html_e('Optional: If set, this URL will be used for the "Back to Products" link instead of the category link.', 'easyline'); ?>
            </div>
          </div>
        </div>

        <?php
}

// Save handler
add_action('save_post_med_product', function ($post_id, $post) {
    // Basic capability + nonce checks
    if (!isset($_POST['med_product_extra_fields_nonce']) ||
        !wp_verify_nonce($_POST['med_product_extra_fields_nonce'], 'save_med_product_extra_fields')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Sanitize and save
    $map = [
        'mp_size_label' => 'sanitize_text_field',
        'mp_size_value' => 'sanitize_text_field',
        'mp_code_label' => 'sanitize_text_field',
        'mp_code_value' => 'sanitize_text_field',
        'mp_link_label' => 'sanitize_text_field',
        'mp_link_url'   => 'esc_url_raw',
        'mp_title_color' => 'sanitize_hex_color',
        'mp_back_link_url' => 'esc_url_raw',
    ];
    foreach ($map as $key => $cb) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, $cb($_POST[$key]));
        }
    }
}, 10, 2);
