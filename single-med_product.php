<?php
get_header();

$med_categories = get_the_terms(get_the_ID(), 'med_category');
$selected_category = null;

if ($med_categories && !is_wp_error($med_categories)) {
	error_log('Post ID: ' . get_the_ID());
	error_log('Categories for this post:');
	foreach ($med_categories as $category) {
		error_log('- ' . $category->name . ' (ID: ' . $category->term_id . ', Slug: ' . $category->slug . ')');
	}
}

if ($med_categories && !is_wp_error($med_categories)) {
	if (isset($_GET['cat'])) {
		$from_category_id = intval($_GET['cat']);
		foreach ($med_categories as $category) {
			if ($category->term_id === $from_category_id) {
				$selected_category = $category;
				error_log('Selected category by URL parameter: ' . $category->name . ' (ID: ' . $category->term_id . ')');
				set_transient('med_product_from_category_' . get_the_ID(), $category->term_id, 24 * HOUR_IN_SECONDS);
				break;
			}
		}
	}

	if (!$selected_category) {
		$transient_category_id = get_transient('med_product_from_category_' . get_the_ID());
		if ($transient_category_id) {
			foreach ($med_categories as $category) {
				if ($category->term_id === intval($transient_category_id)) {
					$selected_category = $category;
					error_log('Selected category by transient: ' . $category->name . ' (ID: ' . $category->term_id . ')');
					break;
				}
			}
		}
	}

	if (!$selected_category) {
		$referrer = wp_get_referer();
		if ($referrer) {
			error_log('Referrer: ' . $referrer);
			foreach ($med_categories as $category) {
				$category_url = get_term_link($category);
				error_log('Checking category: ' . $category->name . ' (ID: ' . $category->term_id . ') URL: ' . $category_url);
				if ($category_url && strpos($referrer, $category_url) !== false) {
					$selected_category = $category;
					error_log('Selected category by referrer: ' . $category->name . ' (ID: ' . $category->term_id . ')');
					// Сохраняем в transient
					set_transient('med_product_from_category_' . get_the_ID(), $category->term_id, 24 * HOUR_IN_SECONDS);
					break;
				}
			}
		}
	}

	if (!$selected_category) {
		$selected_category = $med_categories[0];
		error_log('Using first category: ' . $selected_category->name . ' (ID: ' . $selected_category->term_id . ')');
	}
}

?>
<main class="content-page med-product">
	<div class="container">
		<div class="row">
			<?php get_sidebar(); ?>
			<div class="col-md-10 col-xs-12">
				<div class="category">
					<section class="breadcrumbs">
						<ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
							<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
								<a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
								</a>
								>
							</li>
							<?php
							$page_id = get_page_id_med_products();
							$page_link = get_permalink($page_id);
							if ($page_link) {
								$page_title = get_the_title($page_id);
								?>
								<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
									<a href="<?php echo esc_url($page_link); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
										<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html($page_title); ?></span>
									</a>
									>
								</li>
								<?php
							}
							?>
							<?php
							if ($selected_category) {
								$category_link = get_term_link($selected_category);
								if ($category_link && !is_wp_error($category_link)) {
									?>
									<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
										<a href="<?php echo esc_url($category_link); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
											<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html($selected_category->name); ?></span>
										</a>
										>
									</li>
									<?php
								}
							}
							?>
							<li class="current breadcrumbs__item">
								<span><?php the_title(); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>
					<div class="content content-blog">
						<?php
						$first_category = $selected_category;
						$category_image_id = null;

						if ($first_category) {
							$category_image_id = get_term_meta($first_category->term_id, 'med_category_image_id', true);
						}

						if ($first_category) {
							?>
							<div class="content-top">
								<div class="title-with-image">
									<h1 class="title"><?php _e('איזיליין בקופות החולים', 'easyline'); ?></h1>
									<?php
									if ($category_image_id) {
										echo '<div class="med-category-image">';
										echo liteimage($category_image_id, [
											'thumb' => [0, 80],
											'args' => [
												'alt' => $first_category->name,
												'class' => 'med-category-thumbnail',
												'loading' => 'lazy',
												'decoding' => 'async'
											],
										]);
										echo '</div>';
									}
									?>
								</div>
							</div>
							<?php
						}
						?>
						<?php
						$title_color = get_post_meta(get_the_ID(), 'mp_title_color', true) ?: '#234e32';
						?>
						<div class="content-top med-product-title" style="background-color: <?php echo esc_attr($title_color); ?>;">
							<h1 class="title full-width"><?php the_title(); ?></h1>
						</div>

						<div class="single-content">
							<div class="text-block">
								<div class="med-product-info">
									<?php
									$mp_size_label = get_post_meta(get_the_ID(), 'mp_size_label', true);
									$mp_size_value = get_post_meta(get_the_ID(), 'mp_size_value', true);
									?>
									<?php if ($mp_size_label && $mp_size_value): ?>
										<div class="med-product-size">
											<div class="size-label"><?php echo esc_html($mp_size_label); ?></div>
											<span>-</span>
											<div class="size-value"><?php echo esc_html($mp_size_value); ?></div>
										</div>
									<?php endif ?>

									<?php
									$mp_code_label = get_post_meta(get_the_ID(), 'mp_code_label', true);
									$mp_code_value = get_post_meta(get_the_ID(), 'mp_code_value', true);
									?>
									<?php if ($mp_code_label && $mp_code_value): ?>
										<div class="med-product-code">
											<div class="code-label"><?php echo esc_html($mp_code_label); ?></div>
											<span>-</span>
											<div class="code-value"><?php echo esc_html($mp_code_value); ?></div>
										</div>
									<?php endif ?>
								</div>

								<?php the_post(); the_content(); ?>

							<?php
							$mp_link_label = get_post_meta(get_the_ID(), 'mp_link_label', true);
							$mp_link_url = get_post_meta(get_the_ID(), 'mp_link_url', true);
							$mp_back_link_url = get_post_meta(get_the_ID(), 'mp_back_link_url', true);

							$back_url = null;
							if ($mp_back_link_url) {
								$back_url = $mp_back_link_url;
							} elseif ($selected_category) {
								$back_url = get_term_link($selected_category);
							} else {
								$back_url = $page_link;
							}
							?>
							<div class="med-product-links">
								<?php if ($mp_link_label && $mp_link_url): ?>
									<a href="<?php echo esc_url($mp_link_url); ?>" class="med-product-link">
										<div class="link-label"><?php echo esc_html($mp_link_label); ?></div>
									</a>
								<?php endif ?>

								<?php if ($back_url): ?>
									<a href="<?php echo esc_url($back_url); ?>" class="med-product-link">
										<div class="link-label"><?php echo __('לחזרה לכל המוצרים לחץ >', 'easyline'); ?></div>
									</a>
								<?php endif ?>
							</div>
							</div>

							<div class="image-block">
								<?php $image_id = get_post_thumbnail_id(); ?>
								<?php if ($image_id) : ?>
									<?php $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: __('Image', 'easyline'); ?>
									<?php echo liteimage($image_id, [
										'thumb' => [0, 391],
										'args' => ['alt' => $alt]
									]); ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script defer>
document.addEventListener('DOMContentLoaded', function() {
	// Скрываем параметр 'cat' из URL если он есть
	if (window.location.search.includes('cat=')) {
		console.log('Found cat parameter in URL:', window.location.search);
		// Создаем новый URL без параметра cat
		const url = new URL(window.location);
		url.searchParams.delete('cat');

		// Заменяем URL в браузере без перезагрузки страницы
		window.history.replaceState({}, document.title, url.pathname + url.search);
		console.log('URL cleaned:', url.pathname + url.search);
	}
});
</script>

<?php get_footer(); ?>
