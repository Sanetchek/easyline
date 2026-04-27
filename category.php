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
								$slug = get_queried_object();
								$params = array(
									'post_type' => 'post',
									'posts_per_page' => -1,
									'orderby' => 'menu_order',
									'order' => 'ASC',
									'tax_query' => array(
										array(
											'taxonomy' => 'category',
											'field' => 'slug',
											'terms' => $slug->slug
										)
									)
								);

								$recent_posts_array = get_posts( $params );

								if( $recent_posts_array ){
									foreach( $recent_posts_array as $value ){ ?>
										<div class="product-item product-item-shop blog-cat-post">
											<div class="product-item-image">
												<div class="product-item-shop__title">
													<a href="<?php echo get_the_permalink($value->ID); ?>"><?php echo get_the_title($value->ID); ?></a>
												</div>
												<?php
												$thumb_url = get_the_post_thumbnail_url($value->ID, 'large');
												if (!$thumb_url) {
													$thumb_url = get_template_directory_uri() . '/img/shop.png';
												}
												?>
												<a href="<?php echo esc_url(get_the_permalink($value->ID)); ?>" class="image-link">
													<img src="<?php echo esc_url($thumb_url); ?>" class="image" alt="<?php echo esc_attr(get_the_title($value->ID)); ?>" />
												</a>
												<div class="product-item-shop__desc" dir="auto"><?php echo esc_html(easyline_get_post_list_excerpt_plain($value->ID)); ?></div>
												<div class="product-item-shop__more-wrap">
													<a href="<?php echo esc_url(get_the_permalink($value->ID)); ?>" class="more"><?php echo esc_html__('לפרטים נוספים', 'easyline'); ?></a>
												</div>
											</div>
										</div>
										<?php
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

