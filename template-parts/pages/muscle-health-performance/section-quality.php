<?php
/**
 * Quality / certifications section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_quality')) {
	return;
}

$title = (string) easyline_mhp_get_field('mhp_quality_title', $post_id);
$text  = (string) easyline_mhp_get_field('mhp_quality_text', $post_id);
$logos = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_quality_logos', $post_id));

if ($title === '' && $text === '' && empty($logos)) {
	return;
}
?>
<section class="mhp-section mhp-quality" aria-labelledby="mhp-quality-title">
	<div class="mhp-quality__inner">
		<div class="mhp-quality__copy">
			<?php if ($title !== '') : ?>
				<h2 id="mhp-quality-title" class="mhp-quality__title" dir="auto"><?php echo esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($text !== '') : ?>
				<p class="mhp-quality__text" dir="auto"><?php echo esc_html($text); ?></p>
			<?php endif; ?>
		</div>

		<?php if (!empty($logos)) : ?>
			<ul class="mhp-quality__logos">
				<?php foreach ($logos as $logo) :
					$image_id = isset($logo['image']) ? (int) $logo['image'] : 0;
					$name     = isset($logo['name']) ? (string) $logo['name'] : '';
					$alt      = isset($logo['alt']) ? (string) $logo['alt'] : '';
					if (!$image_id && $name === '') {
						continue;
					}
					?>
					<li class="mhp-quality__logo">
						<?php if ($image_id) : ?>
							<?php
							echo wp_get_attachment_image(
								$image_id,
								'medium',
								false,
								array(
									'alt'      => $alt,
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
						<?php else : ?>
							<span class="mhp-quality__badge" role="img" aria-label="<?php echo esc_attr($alt ?: $name); ?>">
								<?php echo esc_html($name); ?>
							</span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
