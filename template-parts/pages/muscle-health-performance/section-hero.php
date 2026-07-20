<?php
/**
 * Hero section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_hero')) {
	return;
}

$title    = (string) easyline_mhp_get_field('mhp_hero_title', $post_id);
$subtitle = (string) easyline_mhp_get_field('mhp_hero_subtitle', $post_id);
$text     = (string) easyline_mhp_get_field('mhp_hero_text', $post_id);
$cta      = easyline_mhp_resolve_link(easyline_mhp_get_field('mhp_hero_cta', $post_id), __('Learn more', 'easyline'));
$image_id = (int) easyline_mhp_get_field('mhp_hero_image', $post_id);
$subtitle_parts = array();
preg_match('/^(.*)\s+(meets)\s+(.*)$/i', $subtitle, $subtitle_parts);

if ($title === '' && $subtitle === '' && $text === '' && $cta['url'] === '' && !$image_id) {
	return;
}
?>
<section class="mhp-section mhp-hero" aria-labelledby="mhp-hero-title">
	<div class="mhp-hero__inner">
		<?php if ($title !== '') : ?>
			<h1 id="mhp-hero-title" class="mhp-hero__title" dir="auto"><?php echo esc_html($title); ?></h1>
		<?php endif; ?>

		<?php if ($subtitle !== '') : ?>
			<p class="mhp-hero__subtitle" dir="ltr">
				<?php if (count($subtitle_parts) === 4) : ?>
					<span><?php echo esc_html($subtitle_parts[1]); ?></span>
					<em><?php echo esc_html($subtitle_parts[2]); ?></em>
					<span><?php echo esc_html($subtitle_parts[3]); ?></span>
				<?php else : ?>
					<?php echo esc_html($subtitle); ?>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if ($text !== '') : ?>
			<p class="mhp-hero__text" dir="auto"><?php echo esc_html($text); ?></p>
		<?php endif; ?>

		<?php if ($cta['url'] !== '') : ?>
			<a class="mhp-btn mhp-hero__cta"
				href="<?php echo esc_url($cta['url']); ?>"
				target="<?php echo esc_attr($cta['target']); ?>"
				<?php echo $cta['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
				<span><?php echo esc_html($cta['title']); ?></span>
				<span class="mhp-btn__icon" aria-hidden="true">&laquo;</span>
			</a>
		<?php endif; ?>
	</div>

	<div class="mhp-hero__media">
		<?php if ($image_id) : ?>
			<?php
			echo wp_get_attachment_image(
				$image_id,
				'large',
				false,
				array(
					'class'         => 'mhp-hero__image',
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'decoding'      => 'async',
				)
			);
			?>
		<?php else : ?>
			<img
				class="mhp-hero__image"
				src="<?php echo esc_url(get_template_directory_uri() . '/img/muscle-health-performance-hero.webp'); ?>"
				alt=""
				width="412"
				height="340"
				loading="eager"
				fetchpriority="high"
				decoding="async"
			>
		<?php endif; ?>
	</div>
</section>
