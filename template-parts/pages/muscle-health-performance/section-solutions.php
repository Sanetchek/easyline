<?php
/**
 * Solutions section (WooCommerce products).
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_solutions')) {
	return;
}

$title         = (string) easyline_mhp_get_field('mhp_solutions_title', $post_id);
$product_cards = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_solutions_items', $post_id));
$cta_label     = trim((string) easyline_mhp_get_field('mhp_solutions_cta_label', $post_id));

if (empty($product_cards)) {
	$legacy_products = get_post_meta($post_id, 'mhp_solutions_products', true);
	$product_cards   = easyline_mhp_normalize_rows($legacy_products);
}

if ($cta_label === '') {
	$cta_label = __('Learn more', 'easyline');
}

if ($title === '' && empty($product_cards)) {
	return;
}

if (!function_exists('wc_get_product')) {
	return;
}
?>
<section id="mhp-solutions" class="mhp-section mhp-solutions" aria-labelledby="mhp-solutions-title">
	<?php if ($title !== '') : ?>
		<h2 id="mhp-solutions-title" class="mhp-section__title" dir="auto"><?php echo esc_html($title); ?></h2>
	<?php endif; ?>

	<?php if (!empty($product_cards)) : ?>
		<ul class="mhp-solutions__grid">
			<?php foreach ($product_cards as $card) :
				$is_legacy   = !is_array($card);
				$product_ref = $is_legacy ? $card : (isset($card['product']) ? $card['product'] : 0);
				$product_id  = is_object($product_ref) && isset($product_ref->ID) ? (int) $product_ref->ID : (int) $product_ref;
				$product     = wc_get_product($product_id);
				if (!$product) {
					continue;
				}

				$features = array();
				if (!$is_legacy) {
					$feature_rows = isset($card['features']) ? $card['features'] : array();
					foreach (easyline_mhp_normalize_rows($feature_rows) as $feature_row) {
						$feature = is_array($feature_row) && isset($feature_row['text']) ? (string) $feature_row['text'] : (string) $feature_row;
						$feature = trim($feature);
						if ($feature !== '') {
							$features[] = $feature;
						}
					}
				} else {
					$features = easyline_mhp_product_feature_lines($product);
				}

				$permalink    = get_permalink($product_id);
				$image_id     = $product->get_image_id();
				$product_name = $product->get_name();
				$cta_name     = easyline_mhp_product_short_name($product_name);
				/* translators: %1$s: CTA action, %2$s: product name. */
				$button_label = sprintf(_x('%1$s %2$s', 'product CTA label', 'easyline'), $cta_label, $cta_name);
				$body_class   = empty($features) ? ' mhp-solutions__body--media-only' : '';
				?>
				<li class="mhp-solutions__card">
					<h3 class="mhp-solutions__card-title" dir="auto"><?php echo esc_html($product_name); ?></h3>

					<div class="mhp-solutions__body<?php echo esc_attr($body_class); ?>">
						<?php if ($image_id) : ?>
							<div class="mhp-solutions__media">
								<?php
								echo wp_get_attachment_image(
									$image_id,
									'medium',
									false,
									array(
										'class'    => 'mhp-solutions__image',
										'alt'      => $product_name,
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
								?>
							</div>
						<?php endif; ?>

						<?php if (!empty($features)) : ?>
							<ul class="mhp-solutions__features">
								<?php foreach ($features as $feature) : ?>
									<li>
										<span class="mhp-solutions__check" aria-hidden="true">
											<svg width="14" height="14" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
												<circle cx="9" cy="9" r="8" stroke="currentColor" stroke-width="1.5"/>
												<path d="M5 9.2L7.6 11.8L13 6.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</span>
										<span><?php echo esc_html($feature); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

					<a class="mhp-btn mhp-solutions__cta" href="<?php echo esc_url($permalink); ?>">
						<?php echo esc_html($button_label); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
