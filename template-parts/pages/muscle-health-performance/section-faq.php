<?php
/**
 * FAQ section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_faq')) {
	return;
}

$title = (string) easyline_mhp_get_field('mhp_faq_title', $post_id);
$items = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_faq_items', $post_id));

if ($title === '' && empty($items)) {
	return;
}
?>
<section class="mhp-section mhp-faq" aria-labelledby="mhp-faq-title">
	<?php if ($title !== '') : ?>
		<h2 id="mhp-faq-title" class="mhp-section__title" dir="auto"><?php echo esc_html($title); ?></h2>
	<?php endif; ?>

	<?php if (!empty($items)) : ?>
		<div class="faq-list mhp-faq__list">
			<?php foreach ($items as $index => $item) :
				$question = isset($item['question']) ? (string) $item['question'] : '';
				$answer   = isset($item['answer']) ? (string) $item['answer'] : '';
				if ($question === '') {
					continue;
				}

				$panel_id  = 'mhp-faq-panel-' . (int) $index;
				$button_id = 'mhp-faq-btn-' . (int) $index;
				?>
				<div class="faq-list-item mhp-faq__item">
					<button
						type="button"
						class="faq-list-item__title mhp-faq__question"
						id="<?php echo esc_attr($button_id); ?>"
						aria-expanded="false"
						aria-controls="<?php echo esc_attr($panel_id); ?>"
					>
						<span class="slide-icon mhp-faq__icon" aria-hidden="true">
							<span class="">+</span>
							<span class="hide">-</span>
						</span>
						<span class="mhp-faq__question-text" dir="auto"><?php echo esc_html($question); ?></span>
					</button>
					<div
						id="<?php echo esc_attr($panel_id); ?>"
						class="faq-list-item__content mhp-faq__answer"
						hidden
						role="region"
						aria-labelledby="<?php echo esc_attr($button_id); ?>"
					>
						<?php echo wp_kses_post($answer); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
