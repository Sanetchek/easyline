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
								</a> >
							</li>

							<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
								<a href="<?php echo get_the_permalink(42); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name"><?php echo get_the_title(42); ?></span>
								</a> >
							</li>

							<li class="current breadcrumbs__item">
								<span><?php single_term_title(); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>

					<div class="content">
						<div class="content-top">
							<h1 class="title"><?php echo __('החנות שלנו ', 'easyline'); ?></h1>
							<div class="subtitle"><?php single_term_title(); ?></div>
						</div>

						<div class="products">
							<?php
								$slug = get_queried_object();
								$params = array(
									'post_type' => 'product',
									'posts_per_page' => -1,
									'orderby' => 'date',
									'order' => 'DESC',
									'tax_query' => array(
										array(
											'taxonomy' => 'product_cat',
											'field' => 'slug',
											'terms' => $slug->slug,
										),
									),
									'meta_query' => array(
										'relation' => 'OR',
										// In-stock products
										array(
											'key' => '_stock_status',
											'value' => 'instock',
										),
										// Out-of-stock products
										array(
											'key' => '_stock_status',
											'value' => 'outofstock',
										),
									),
								);

								// First get all products
								$all_products = get_posts($params);

								// Split into in-stock and out-of-stock
								$in_stock = [];
								$out_of_stock = [];

								foreach ($all_products as $product) {
									$stock_status = get_post_meta($product->ID, '_stock_status', true);
									if ($stock_status === 'instock') {
										$in_stock[] = $product;
									} else {
										$out_of_stock[] = $product;
									}
								}

								// Merge arrays: in-stock first, then out-of-stock
								$recent_posts_array = array_merge($in_stock, $out_of_stock);

								foreach ($recent_posts_array as $key => $value) {
									$_product = new WC_Product($value->ID);
									?>

									<div class="product-item">
											<?php if ($_product->is_on_sale()) : ?>
													<span class="sale_message"><?php echo __('מבצע ', 'easyline'); ?></span>
											<?php endif; ?>

											<a href="<?php echo get_the_permalink($value->ID); ?>" class="product-item-image">
													<span class="title"><?php echo get_the_title($value->ID); ?><br/>
															<?php if ($_product->is_on_sale()) : ?>
																	<span class="price-hide"><del><?php echo $_product->get_regular_price(); ?> ₪</del></span>
																	<span class="price-hide"><?php echo $_product->get_sale_price(); ?> ₪</span>
															<?php else: ?>
																	<span class="price-hide"><?php echo $_product->get_regular_price(); ?> ₪</span>
															<?php endif; ?>

															<?php if (!$_product->is_in_stock()) : ?>
																	<span class="out-of-stock-message"><?php echo __('אזל מהמלאי', 'easyline'); ?></span>
															<?php endif; ?>
													</span>
													<div class="product-image-wrap"><?php echo get_the_post_thumbnail($value->ID); ?></div>
											</a>

											<div class="button-wrap">
													<a href="<?php echo get_the_permalink($value->ID); ?>" class="product-btn"><?php echo __('לפרטים>', 'easyline'); ?></a>

													<?php $show_wishlist_btn = false; ?>
													<?php if ($show_wishlist_btn) : ?>
														<div class="two-button">
																<a href="<?php echo get_the_permalink($value->ID); ?>" rel="nofollow" data-product-id="<?php echo $value->ID; ?>" data-product-type="<?php echo $product_type; ?>" class="add_wishlist <?php echo $link_classes; ?>">
																		<i class="fa fa-star" aria-hidden="true"></i>
																</a>
														</div>
													<?php endif ?>

													<?php show_add_to_cart_form($value->ID, $_product->is_in_stock(), false); ?>
											</div>
									</div>
								<?php } ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>

