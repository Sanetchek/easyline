<?php
/**
 * Knowledge / articles section.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}

$post_id = get_the_ID();
if (!easyline_mhp_section_enabled($post_id, 'mhp_knowledge')) {
	return;
}

$title     = (string) easyline_mhp_get_field('mhp_knowledge_title', $post_id);
$cta       = easyline_mhp_resolve_link(easyline_mhp_get_field('mhp_knowledge_cta', $post_id), __('See all articles', 'easyline'));
$posts     = easyline_mhp_normalize_rows(easyline_mhp_get_field('mhp_knowledge_posts', $post_id));
$read_more = (string) easyline_mhp_get_field('mhp_knowledge_read_more', $post_id);
if ($read_more === '') {
	$defaults  = easyline_mhp_default_content();
	$read_more = isset($defaults['mhp_knowledge_read_more']) && $defaults['mhp_knowledge_read_more'] !== ''
		? (string) $defaults['mhp_knowledge_read_more']
		: __('Read more', 'easyline');
}

if ($title === '' && empty($posts)) {
	return;
}
?>
<section class="mhp-section mhp-knowledge" aria-labelledby="mhp-knowledge-title">
	<div class="mhp-knowledge__header">
		<?php if ($title !== '') : ?>
			<h2 id="mhp-knowledge-title" class="mhp-section__title mhp-knowledge__title" dir="auto"><?php echo esc_html($title); ?></h2>
		<?php endif; ?>

		<?php if ($cta['url'] !== '') : ?>
			<a class="mhp-knowledge__all"
				href="<?php echo esc_url($cta['url']); ?>"
				target="<?php echo esc_attr($cta['target']); ?>"
				<?php echo $cta['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
				<?php echo esc_html($cta['title']); ?>
				<span aria-hidden="true">&gt;</span>
			</a>
		<?php endif; ?>
	</div>

	<?php if (!empty($posts)) : ?>
		<ul class="mhp-knowledge__grid">
			<?php foreach ($posts as $article_id) :
				$article_id = (int) $article_id;
				if ($article_id <= 0 || get_post_status($article_id) !== 'publish') {
					continue;
				}

				$permalink    = get_permalink($article_id);
				$date         = get_the_date('d.m.Y', $article_id);
				$has_image    = has_post_thumbnail($article_id);
				$card_classes = $has_image ? 'mhp-knowledge__card' : 'mhp-knowledge__card mhp-knowledge__card--text-only';
				?>
				<li class="<?php echo esc_attr($card_classes); ?>">
					<?php if ($has_image) : ?>
						<div class="mhp-knowledge__media">
							<?php
							echo get_the_post_thumbnail(
								$article_id,
								'medium',
								array(
									'class'    => 'mhp-knowledge__image',
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
						</div>
					<?php endif; ?>

					<div class="mhp-knowledge__content">
						<?php if ($date) : ?>
							<time class="mhp-knowledge__date" datetime="<?php echo esc_attr(get_the_date('c', $article_id)); ?>">
								<?php echo esc_html($date); ?>
							</time>
						<?php endif; ?>

						<h3 class="mhp-knowledge__card-title" dir="auto">
							<a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html(get_the_title($article_id)); ?></a>
						</h3>

						<a class="mhp-knowledge__more" href="<?php echo esc_url($permalink); ?>">
							<?php echo esc_html($read_more); ?>
							<span aria-hidden="true">&gt;</span>
						</a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
