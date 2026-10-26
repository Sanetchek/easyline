<?php
/**
 * Template Name: Blog recipes page
 */
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
							<li class="current breadcrumbs__item">
								<span><?php echo the_title(); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>
					<div class="content">
						<div class="content-top">
							<h1 class="title"><?php the_title(); ?></h1>
						</div>
						<div class="products myRecipyProd">
							<?php
							$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
							$recipes_query = new WP_Query(
								array(
									'post_type'      => 'post',
									'post_status'    => 'publish',
									'cat'            => 300,
									'posts_per_page' => 12,
									'paged'          => $paged,
									'orderby'        => 'date',
									'order'          => 'DESC',
								)
							);
							if ($recipes_query->have_posts()) {
								while ($recipes_query->have_posts()) {
									$recipes_query->the_post();
									$value_id = get_the_ID();
									?>
									<div class="product-item product-item-shop blog-page">
										<div class="product-item-image">
											<div class="product-item-shop__title">
												<a href="<?php echo esc_url(get_permalink($value_id)); ?>"><?php echo esc_html(get_the_title($value_id)); ?></a>
											</div>
											<a href="<?php echo esc_url(get_permalink($value_id)); ?>" class="image-link">
												<?php
												$img_id = get_post_thumbnail_id($value_id);
												if ($img_id) {
													echo wp_get_attachment_image(
														$img_id,
														'medium',
														false,
														array(
															'alt'      => get_the_title($value_id),
															'class'    => 'image',
															'loading'  => 'lazy',
															'decoding' => 'async',
														)
													);
												} else {
													echo '<img src="' . esc_url(get_template_directory_uri() . '/img/product.png') . '" class="image" alt="' . esc_attr(get_the_title($value_id)) . '" />';
												}
												?>
											</a>
											<div>
												<a href="<?php echo esc_url(get_permalink($value_id)); ?>" class="myRecipyBtn"><?php echo esc_html__('לפרטים נוספים>', 'easyline'); ?></a>
											</div>
										</div>
									</div>
									<?php
								}
								wp_reset_postdata();

								if ($recipes_query->max_num_pages > 1) {
									echo '<nav class="pagination">';
									echo wp_kses_post(
										paginate_links(
											array(
												'total'   => (int) $recipes_query->max_num_pages,
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
