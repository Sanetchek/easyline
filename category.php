<?php

/**

 * Template Name: Shop

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
							<h1 class="title"><?php single_term_title(); ?></h1>
						</div>

						<div class="products">
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
										<div class="product-item product-item-shop">
											<div class="product-item-image">
												<div class="product-item-shop__title">
													<a href="<?php echo get_the_permalink($value->ID); ?>"><?php echo get_the_title($value->ID); ?></a>
												</div>
												<div class="product-item-shop__desc"><?php echo get_the_excerpt($value->ID); ?></div>
												<a href="<?php echo get_the_permalink($value->ID); ?>" class="image-link">
													<img src="<?php echo get_the_post_thumbnail($value->ID); ?>" class="image" alt="image" />
												</a>
												<div>
													<a href="<?php echo get_the_permalink($value->ID); ?>" class="more"><?php echo __('לפרטים נוספים> ', 'easyline'); ?></a>
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

