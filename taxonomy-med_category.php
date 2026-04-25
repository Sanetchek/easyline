<?php
get_header();
?>
<main class="content-page med-category">
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
							<?php
							$page_id = get_page_id_med_products();
							$page_link = get_permalink($page_id);
							if ($page_link) {
								$page_title = get_the_title($page_id);
								?>
								<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
									<a href="<?php echo esc_url($page_link); ?>" class="breadcrumbs__link" itemscope=""
										itemtype="http://schema.org/Thing" itemprop="item">
										<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html($page_title); ?></span>
									</a>
									>
								</li>
								<?php
							}
							?>
							<?php
							$current_term = get_queried_object();
							$term_name = $current_term->name;
							?>
							<li class="current breadcrumbs__item">
								<span><?php echo esc_html($term_name); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>
					<div class="content">
						<div class="content-top">
							<div class="title-with-image">
								<h1 class="title"><?php _e('איזיליין בקופות החולים', 'easyline'); ?></h1>
								<?php
								$current_term = get_queried_object();
								$term_image_id = get_term_meta($current_term->term_id, 'med_category_image_id', true);
								if ($term_image_id) {
									echo '<div class="med-category-image">';
									echo liteimage($term_image_id, [
										'thumb' => [0, 80],
										'args'  => [
											'alt' => $current_term->name,
											'class' => 'med-category-thumbnail',
											'loading' => 'lazy',
											'decoding' => 'async'
										],
									]);
									echo '</div>';
								}
								?>
							</div>
							<?php
							$term_description = term_description();
							if ( ! empty( $term_description ) ) {
								echo '<div class="category-description">' . $term_description . '</div>';
							}
							?>
						</div>
						<div class="products myRecipyProd">
							<?php
							$current_term = get_queried_object();
							$term_id = $current_term->term_id;

							$params = array(
								'post_type' => 'med_product',
								'tax_query' => array(
									array(
										'taxonomy' => 'med_category',
										'field'    => 'term_id',
										'terms'    => $term_id,
									),
								),
								'posts_per_page' => 12,
								'orderby' => 'date',
								'order' => 'DESC',
								'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
							);

							$med_products_query = new WP_Query( $params );

							if( $med_products_query->have_posts() ){
								while( $med_products_query->have_posts() ){
									$med_products_query->the_post(); ?>
									<div class="product-item product-item-shop blog-page">
										<?php
										$title_color = get_post_meta(get_the_ID(), 'mp_title_color', true) ?: '#234e32';
										?>
										<div class="product-item-image">
											<div class="product-item-shop__title" style="background-color: <?php echo esc_attr($title_color); ?>;">
												<a href="<?php echo add_query_arg('cat', $current_term->term_id, get_permalink()); ?>" aria-current=""><?php the_title(); ?></a>
											</div>
											<a href="<?php echo add_query_arg('cat', $current_term->term_id, get_permalink()); ?>" class="image-link">
												<?php
												$img_id = get_post_thumbnail_id();
												if ($img_id) {
													echo liteimage($img_id, [
														'thumb' => [0, 260],
														'args'  => [
															'alt' => get_the_title(),
															'class' => 'image',
															'loading' => 'lazy',
															'decoding' => 'async'
														],
													]);
												} else {
													echo '<img src="' . get_template_directory_uri() . '/img/product.png" class="image" alt="' . get_the_title() . '" />';
												}
												?>
											</a>
											<div>
												<a href="<?php echo add_query_arg('cat', $current_term->term_id, get_permalink()); ?>" class="myRecipyBtn"><?php echo __('לפרטים נוספים>', 'easyline'); ?></a>
											</div>
										</div>
									</div>
									<?php
								}

								$total_pages = $med_products_query->max_num_pages;
								if ($total_pages > 1) {
									echo '<div class="pagination-wrapper">';
									echo paginate_links(array(
										'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
										'format' => '?paged=%#%',
										'current' => max(1, get_query_var('paged')),
										'total' => $total_pages,
										'prev_text' => __('« הקודם', 'easyline'),
										'next_text' => __('הבא »', 'easyline'),
									));
									echo '</div>';
								}

								wp_reset_postdata();
							} else {
								echo '<div class="no-products">' . __('לא נמצאו מוצרים בקטגוריה זו.', 'easyline') . '</div>';
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
