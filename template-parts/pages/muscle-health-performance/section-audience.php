<?php
/**
 * Audience section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_audience')) {
	return;
}

$title = (string) easyline_mhp_get_field('mhp_audience_title', $post_id);
$items = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_audience_items', $post_id));

if ($title === '' && empty($items)) {
	return;
}
?>
<section class="mhp-section mhp-audience" aria-labelledby="mhp-audience-title">
	<?php if ($title !== '') : ?>
		<h2 id="mhp-audience-title" class="mhp-section__title" dir="auto"><?php echo esc_html($title); ?></h2>
	<?php endif; ?>

	<?php if (!empty($items)) : ?>
		<ul class="mhp-audience__grid">
			<?php foreach ($items as $item) :
				$item_title = isset($item['title']) ? (string) $item['title'] : '';
				$icon_id    = isset($item['icon']) ? (int) $item['icon'] : 0;
				$icon_key   = isset($item['icon_key']) ? (string) $item['icon_key'] : '';
				$icon_svg   = easyline_mhp_get_icon_svg($icon_key);
				if ($item_title === '' && !$icon_id && $icon_svg === '') {
					continue;
				}
				?>
				<li class="mhp-audience__item">
					<?php if ($icon_id || $icon_svg !== '') : ?>
						<div class="mhp-audience__icon">
							<?php if ($icon_id) : ?>
								<?php
								echo wp_get_attachment_image(
									$icon_id,
									'thumbnail',
									false,
									array(
										'alt'      => $item_title !== '' ? $item_title : '',
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
								?>
							<?php else : ?>
								<?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static trusted SVG map. ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ($item_title !== '') : ?>
						<p class="mhp-audience__label" dir="auto"><?php echo esc_html($item_title); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
