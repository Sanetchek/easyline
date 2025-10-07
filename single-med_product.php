<?php
get_header(); ?>
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
							// Получаем первую категорию для хлебных крошек
							$med_categories = get_the_terms(get_the_ID(), 'med_category');
							$category_link = '';
							if ($med_categories && !is_wp_error($med_categories)) {
								$first_category = $med_categories[0];
								$category_link = get_term_link($first_category);
								?>
								<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
									<a href="<?php echo esc_url($category_link); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
										<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html($first_category->name); ?></span>
									</a>
									>
								</li>
								<?php
							}
							?>
							<li class="current breadcrumbs__item">
								<span><?php _e('איזיליין בקופות החולים', 'easyline'); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>
					<div class="content content-blog">
						<?php
						if (isset($first_category)) {
							$category_image_id = get_term_meta($first_category->term_id, 'med_category_image_id', true);
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
								?>
								<div class="med-product-links">
									<?php if ($mp_link_label && $mp_link_url): ?>
										<a href="<?php echo esc_url($mp_link_url); ?>" class="med-product-link">
											<div class="link-label"><?php echo esc_html($mp_link_label); ?></div>
										</a>
									<?php endif ?>

									<a href="<?php echo esc_url($category_link); ?>" class="med-product-link">
										<div class="link-label"><?php echo __('לחזרה לכל המוצרים לחץ >', 'easyline'); ?></div>
									</a>
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
<?php get_footer(); ?>
