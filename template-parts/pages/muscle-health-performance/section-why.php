<?php
/**
 * Why section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_why')) {
	return;
}

$title = (string) easyline_mhp_get_field('mhp_why_title', $post_id);
$items = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_why_items', $post_id));

if ($title === '' && empty($items)) {
	return;
}
?>
<section class="mhp-section mhp-why" aria-labelledby="mhp-why-title">
	<?php if ($title !== '') : ?>
		<h2 id="mhp-why-title" class="mhp-section__title" dir="auto"><?php echo esc_html($title); ?></h2>
	<?php endif; ?>

	<?php if (!empty($items)) : ?>
		<ul class="mhp-why__grid">
			<?php foreach ($items as $item) :
				$item_title = isset($item['title']) ? (string) $item['title'] : '';
				$icon_id    = isset($item['icon']) ? (int) $item['icon'] : 0;
				$icon_key   = isset($item['icon_key']) ? (string) $item['icon_key'] : '';
				$icon_svg   = easyline_mhp_get_icon_svg($icon_key);
				if ($item_title === '' && !$icon_id && $icon_svg === '') {
					continue;
				}
				?>
				<li class="mhp-why__card">
					<?php if ($icon_id || $icon_svg !== '') : ?>
						<div class="mhp-why__icon">
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
						<p class="mhp-why__card-title" dir="auto"><?php echo esc_html($item_title); ?></p>
					<?php endif; ?>

					<span class="mhp-why__check" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
							<path d="M16.5711 17.1454C14.0611 19.6054 10.4011 20.4954 7.03106 19.5054C5.55106 19.0654 4.19106 18.2854 3.08106 17.2154C1.05106 15.2654 -0.038937 12.5954 0.001063 9.77543C0.031063 8.03543 0.491058 6.36546 1.37106 4.86546C2.85106 2.33546 5.31106 0.635417 8.20106 0.135417C9.41106 -0.0745831 10.5811 -0.034566 11.7811 0.215434C14.5711 0.795434 16.9611 2.57542 18.3311 5.07542C19.1011 6.47542 19.5311 8.03542 19.5811 9.63542C19.5811 9.88542 19.5911 10.1155 19.5811 10.3655C19.4811 12.9355 18.4111 15.3654 16.5711 17.1554V17.1454ZM4.85106 10.9054L7.62106 13.6654C7.95106 13.9954 8.57106 13.9654 8.88106 13.6454L11.1311 11.3655L13.6111 8.83543L14.8311 7.57542C15.0811 7.31542 15.1411 6.91543 15.0611 6.58543C14.9811 6.23543 14.7211 5.97541 14.4211 5.87541C14.0811 5.76541 13.7011 5.83543 13.4511 6.08543L12.2311 7.32542L8.85106 10.7754L8.23106 11.4154L6.35106 9.53544C6.16106 9.34544 5.94106 9.19546 5.67106 9.17546C5.28106 9.13546 4.92106 9.31545 4.72106 9.66545C4.52106 10.0154 4.52106 10.5754 4.85106 10.8954V10.9054Z" fill="#7BA040"/>
							<path d="M4.85107 10.9055C4.52107 10.5755 4.51107 10.0355 4.72107 9.6755C4.93107 9.3155 5.28107 9.14545 5.67107 9.18545C5.94107 9.21545 6.16107 9.3555 6.35107 9.5455L8.23107 11.4255L8.85107 10.7855L12.2311 7.33547L13.4511 6.09548C13.7011 5.84548 14.0811 5.76546 14.4211 5.88546C14.7211 5.98546 14.9711 6.23548 15.0611 6.59548C15.1411 6.92548 15.0811 7.32547 14.8311 7.58547L13.6111 8.84548L11.1311 11.3755L8.88107 13.6555C8.56107 13.9755 7.95106 14.0055 7.62106 13.6755L4.85107 10.9155V10.9055Z" fill="white"/>
						</svg>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
