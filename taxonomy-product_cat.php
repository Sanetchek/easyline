<?php
get_header();

$term     = get_queried_object();
$paged    = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$per_page = 24;

$products_query = new WP_Query(
	array(
		'post_type'              => 'product',
		'post_status'            => 'publish',
		'posts_per_page'         => $per_page,
		'paged'                  => $paged,
		'meta_key'               => '_stock_status',
		'orderby'                => array(
			'meta_value' => 'ASC',
			'date'       => 'DESC',
		),
		'tax_query'              => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => isset($term->slug) ? $term->slug : '',
			),
		),
		'no_found_rows'          => false,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	)
);
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
								<a href="<?php echo esc_url(home_url()); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html(get_the_title((int) get_option('page_on_front'))); ?></span>
								</a> >
							</li>

							<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
								<a href="<?php echo esc_url(get_the_permalink(42)); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
									<span class="breadcrumbs__name" itemprop="name"><?php echo esc_html(get_the_title(42)); ?></span>
								</a> >
							</li>

							<li class="current breadcrumbs__item">
								<span><?php single_term_title(); ?></span>
							</li>
						</ul><!-- .breadcrumbs -->
					</section>

					<div class="content">
						<div class="content-top">
							<h1 class="title"><?php echo esc_html__('החנות שלנו ', 'easyline'); ?></h1>
							<div class="subtitle"><?php single_term_title(); ?></div>
						</div>

						<div class="products">
							<?php
							if ($products_query->have_posts()) :
								while ($products_query->have_posts()) :
									$products_query->the_post();
									$product_id = get_the_ID();
									$_product   = function_exists('wc_get_product') ? wc_get_product($product_id) : null;
									if (!$_product) {
										continue;
									}
									?>
									<div class="product-item">
										<?php if ($_product->is_on_sale()) : ?>
											<span class="sale_message"><?php echo esc_html__('מבצע ', 'easyline'); ?></span>
										<?php endif; ?>

										<a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="product-item-image">
											<span class="title"><?php echo esc_html(get_the_title($product_id)); ?><br/>
												<?php if ($_product->is_on_sale()) : ?>
													<span class="price-hide"><del><?php echo esc_html((string) $_product->get_regular_price()); ?> ₪</del></span>
													<span class="price-hide"><?php echo esc_html((string) $_product->get_sale_price()); ?> ₪</span>
												<?php else : ?>
													<span class="price-hide"><?php echo esc_html((string) $_product->get_regular_price()); ?> ₪</span>
												<?php endif; ?>

												<?php if (!$_product->is_in_stock()) : ?>
													<span class="out-of-stock-message"><?php echo esc_html__('אזל מהמלאי', 'easyline'); ?></span>
												<?php endif; ?>
											</span>
											<div class="product-image-wrap"><?php echo get_the_post_thumbnail($product_id); ?></div>
										</a>

										<div class="button-wrap">
											<a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="product-btn"><?php echo esc_html__('לפרטים>', 'easyline'); ?></a>
											<?php show_add_to_cart_form($product_id, $_product->is_in_stock(), false); ?>
										</div>
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
							endif;
							?>
						</div>

						<?php if ($products_query->max_num_pages > 1) : ?>
							<nav class="pagination" aria-label="<?php echo esc_attr__('Product pagination', 'easyline'); ?>">
								<?php
								echo wp_kses_post(
									paginate_links(
										array(
											'total'   => (int) $products_query->max_num_pages,
											'current' => $paged,
											'type'    => 'list',
										)
									)
								);
								?>
							</nav>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
