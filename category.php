<?php
get_header();
?>

<main class="content-page">
	<div class="container">
		<div class="row">
	  <?php get_sidebar(); ?>
			<div class="col-md-10  col-xs-12">
				<div class="category shop">
					<section class="breadcrumbs">
						<ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
							<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
								<a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
								</a>
								>
							</li>
							<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
								<a href="<?php echo esc_url(easyline_get_blog_index_url()); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name">
										<?php
											$blog_page_id = easyline_get_blog_index_page_id();
											echo esc_html($blog_page_id ? get_the_title($blog_page_id) : __('מאמרים בנושא תזונה', 'easyline'));
										?>
									</span>
								</a>
								>
							</li>

							<li class="current breadcrumbs__item">
								<span><?php single_term_title(); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>

					<div class="content">
						<div class="content-top">
							<h1 class="title"><?php single_term_title(); ?></h1>
						</div>

						<div class="products blog-cat-archive">
							<?php
								$slug  = get_queried_object();
								$paged = max(1, (int) get_query_var('paged'));
								$cat_query = new WP_Query(
									array(
										'post_type'      => 'post',
										'post_status'    => 'publish',
										'posts_per_page' => 12,
										'paged'          => $paged,
										'orderby'        => 'menu_order',
										'order'          => 'ASC',
										'tax_query'      => array(
											array(
												'taxonomy' => 'category',
												'field'    => 'slug',
												'terms'    => isset($slug->slug) ? $slug->slug : '',
											),
										),
									)
								);

								if ($cat_query->have_posts()) {
									while ($cat_query->have_posts()) {
										$cat_query->the_post();
										$value_id = get_the_ID();
										?>
										<div class="product-item product-item-shop blog-cat-post">
											<div class="product-item-image">
												<div class="product-item-shop__title">
													<a href="<?php echo esc_url(get_permalink($value_id)); ?>"><?php echo esc_html(get_the_title($value_id)); ?></a>
												</div>
												<?php
												$thumb_url = get_the_post_thumbnail_url($value_id, 'large');
												if (!$thumb_url) {
													$thumb_url = get_template_directory_uri() . '/img/shop.png';
												}
												?>
												<a href="<?php echo esc_url(get_permalink($value_id)); ?>" class="image-link">
													<img src="<?php echo esc_url($thumb_url); ?>" class="image" alt="<?php echo esc_attr(get_the_title($value_id)); ?>" />
												</a>
												<div class="product-item-shop__desc" dir="auto"><?php echo esc_html(easyline_get_post_list_excerpt_plain($value_id)); ?></div>
												<div class="product-item-shop__more-wrap">
													<a href="<?php echo esc_url(get_permalink($value_id)); ?>" class="more"><?php echo esc_html__('לפרטים נוספים', 'easyline'); ?></a>
												</div>
											</div>
										</div>
										<?php
									}
									wp_reset_postdata();

									if ($cat_query->max_num_pages > 1) {
										echo '<nav class="pagination">';
										echo wp_kses_post(
											paginate_links(
												array(
													'total'   => (int) $cat_query->max_num_pages,
													'current' => $paged,
													'type'    => 'list',
												)
											)
										);
										echo '</nav>';
									}
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

<?php get_footer(); ?>

