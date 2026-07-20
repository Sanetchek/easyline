<?php
/**
 * Muscle Health & Performance page content.
 *
 * @package easyline
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="muscle-health">
	<section class="breadcrumbs">
		<ul class="list-unstyled breadcrumbs__list" itemscope itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumbs__link" itemprop="item">
					<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html(get_the_title((int) get_option('page_on_front'))); ?></span>
				</a>
				>
			</li>
			<li class="current breadcrumbs__item">
				<span><?php echo esc_html(get_the_title()); ?></span>
			</li>
		</ul>
	</section>

	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'hero'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'why'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'science'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'audience'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'solutions'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'quality'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'knowledge'); ?>
	<?php get_template_part('template-parts/pages/muscle-health-performance/section', 'faq'); ?>
</div>
