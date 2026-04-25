<?php
/* Template Name: Medical Categories */
get_header();
?>
<main class="content-page med-categories-page">
	<div class="container">
		<div class="row">
			<?php get_sidebar(); ?>
			<div class="col-md-10 col-xs-12">
				<div class="category shop">
					<section class="breadcrumbs">
						<ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
							<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
								<a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
								</a>
								>
							</li>
							<li class="current breadcrumbs__item">
								<span><?php _e('איזיליין בקופות החולים', 'easyline'); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>

					<div class="content">
						<!-- Page Title and Description -->
						<div class="content-top">
							<div class="title-with-image">
                                <h1 class="title"><?php _e('איזיליין בקופות החולים', 'easyline'); ?></h1>
							</div>
							<?php if (have_posts()) : ?>
								<?php while (have_posts()) : the_post(); ?>
									<?php if (get_the_content()) : ?>
										<div class="page-description">
											<?php the_content(); ?>
										</div>
									<?php endif; ?>
								<?php endwhile; ?>
							<?php endif; ?>
						</div>

						<!-- Categories Grid -->
						<div class="products myRecipyProd med-categories-grid">
							<?php
							$args = array(
								'taxonomy' => 'med_category',
								'hide_empty' => true,
								'orderby' => 'name',
								'order' => 'ASC'
							);

							$categories = get_terms($args);

							if (!empty($categories) && !is_wp_error($categories)) {
								foreach ($categories as $category) {
									$category_image_id = get_term_meta($category->term_id, 'med_category_image_id', true);

									// Get products count for this category
									$products_count = $category->count;

									// Get category color (if we want to add color support for categories later)
									$category_color = '#91B122'; // Default color, can be made dynamic later
									?>
									<div class="product-item product-item-shop blog-page category-card">
										<div class="product-item-image">
											<div class="product-item-shop__title" style="background-color: <?php echo esc_attr($category_color); ?>;">
												<a href="<?php echo get_term_link($category); ?>" aria-current="">
													<?php echo esc_html($category->name); ?>
												</a>
											</div>

											<a href="<?php echo get_term_link($category); ?>" class="image-link">
												<?php
												if ($category_image_id) {
													echo liteimage($category_image_id, [
														'thumb' => [0, 260],
														'args'  => [
															'alt' => $category->name,
															'class' => 'image',
															'loading' => 'lazy',
															'decoding' => 'async'
														],
													]);
												} else {
													echo '<img src="' . get_template_directory_uri() . '/img/shop.png" class="image" alt="' . esc_attr($category->name) . '" />';
												}
												?>
											</a>

											<div class="category-info">
												<p class="category-description">
													<?php echo esc_html($category->description); ?>
												</p>
												<p class="products-count">
													<?php
													printf(
														_n('%d product', '%d products', $products_count, 'easyline'),
														$products_count
													);
													?>
												</p>

												<div>
													<a href="<?php echo get_term_link($category); ?>" class="myRecipyBtn">
														<?php echo __('View Products', 'easyline'); ?>
													</a>


												</div>
											</div>
										</div>
									</div>
									<?php
								}
							} else {
								echo '<div class="no-categories">' . __('No medical categories found.', 'easyline') . '</div>';
							}
							?>
						</div>

						<div class="image-big">
							<img src="<?php echo get_template_directory_uri(); ?>/img/shop.png" alt="shop" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<!-- Floating WhatsApp Button (Mobile Only) -->
<div class="floating-whatsapp">
	<a href="https://wa.me/<?php echo get_option('whatsapp_number', '972500000000'); ?>?text=<?php echo urlencode(__('היי! אני מעוניין לקבל מידע על הקטגוריות שלכם', 'easyline')); ?>"
	   target="_blank"
	   rel="noopener noreferrer"
	   aria-label="<?php echo __('Contact via WhatsApp', 'easyline'); ?>">
		<i class="fa fa-whatsapp"></i>
	</a>
</div>

<?php get_footer(); ?>
