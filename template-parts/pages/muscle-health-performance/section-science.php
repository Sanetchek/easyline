<?php
/**
 * Science section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_science')) {
	return;
}

$title = (string) easyline_mhp_get_field('mhp_science_title', $post_id);
$items = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_science_items', $post_id));

if ($title === '' && empty($items)) {
	return;
}
?>
<section class="mhp-section mhp-science" aria-labelledby="mhp-science-title">
	<?php if ($title !== '') : ?>
		<h2 id="mhp-science-title" class="mhp-section__title" dir="auto"><?php echo esc_html($title); ?></h2>
	<?php endif; ?>

	<?php if (!empty($items)) : ?>
		<ul class="mhp-science__grid">
			<?php foreach ($items as $item) :
				$item_title = isset($item['title']) ? (string) $item['title'] : '';
				$item_text  = isset($item['text']) ? (string) $item['text'] : '';
				$icon_id    = isset($item['icon']) ? (int) $item['icon'] : 0;
				$icon_key   = isset($item['icon_key']) ? (string) $item['icon_key'] : '';
				$icon_svg   = easyline_mhp_get_icon_svg($icon_key);

				if ($item_title === '' && $item_text === '' && !$icon_id && $icon_svg === '') {
					continue;
				}
				?>
				<li class="mhp-science__card">
					<?php if ($icon_id || $icon_svg !== '') : ?>
						<div class="mhp-science__icon">
							<?php if ($icon_id) : ?>
								<?php
								echo wp_get_attachment_image(
									$icon_id,
									'medium',
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
						<h3 class="mhp-science__card-title" dir="auto"><?php echo esc_html($item_title); ?></h3>
					<?php endif; ?>

					<?php if ($item_text !== '') : ?>
						<p class="mhp-science__card-text" dir="auto"><?php echo esc_html($item_text); ?></p>
					<?php endif; ?>

					<span class="mhp-science__plus" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
							<path d="M19.42 9.71002C19.42 15.07 15.07 19.42 9.71 19.42C4.35 19.42 0 15.07 0 9.71002C0 4.35002 4.35 0 9.71 0C15.07 0 19.42 4.35002 19.42 9.71002ZM18.52 9.70001C18.52 4.83001 14.57 0.890015 9.71 0.890015C4.85 0.890015 0.900002 4.84001 0.900002 9.70001C0.900002 14.56 4.85 18.51 9.71 18.51C14.57 18.51 18.52 14.56 18.52 9.70001Z" fill="#426927"/>
							<path d="M10.34 14.02C10.34 14.37 10.01 14.57 9.69 14.56C9.39 14.56 9.05 14.37 9.05 14.04V10.19H5.41C5.04 10.19 4.76 9.92997 4.79 9.53997C4.77 9.23997 4.99 8.90001 5.33 8.90001H9.05V5.14C9.05 4.77 9.34 4.51997 9.7 4.53997C10.01 4.51997 10.34 4.75002 10.34 5.09002V8.90001H14.08C14.41 8.90001 14.62 9.23997 14.6 9.53997C14.62 9.83997 14.41 10.17 14.08 10.17H10.35V14L10.34 14.02Z" fill="#426927"/>
						</svg>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
