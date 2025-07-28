<?php
/**
 * Template Name: Index
 */
get_header(); ?>
<main class="content-page">
	<div class="container">
		<div class="row">
			<?php get_sidebar(); ?>

			<div class="col-md-10 col-xs-12">
				<?php // echo do_shortcode('[rev_slider alias="home"]'); ?>
				<div class="home-banner-slider">
					<?php
					$slider_big = get_field("slider_big");

					foreach ($slider_big as $key => $slides) { ?>
						<?php

						// echo '<pre>';
						// print_r($slides);
						// echo '</pre>';
							$link = $slides['link'];
							$image = wp_is_mobile() && $slides['mobile_image'] ? $slides['mobile_image'] : $slides['image']['sizes']['large'];
						?>

						<div class="home-banner" style="background-image:url(<?= $image ?>);background-repeat:no-repeat;background-size:cover;" aria-label="Banner Image <?= $key ?>">
							<?php if ($link) : ?>
								<a href="<?= $link ?>" class="home-banner__link"></a>
							<?php endif; ?>
						</div>
					<?php	} ?>
				</div>

				<div class="category shop category-shop-slider visible-xs-block">
					<div class="content">
						<div class="products">
							<?php
							$categories = get_categories( array(
								'type'         => 'product',
								'child_of'     => '',
								'parent'       => '',
								'orderby' 		 => 'date',
								'order' 			 => 'DESC',
								'exclude'      => '',
								'include'      => '',
								'number'       => 10,
								'taxonomy'     => 'product_cat',
								'pad_counts'   => false,
								// полный список параметров смотрите в описании функции http://wp-kama.ru/function/get_terms
							) );
							if( $categories ){
								foreach( $categories as $cat ){ ?>
									<div class="product-item product-item-shop">
										<div class="product-item-image">
											<div class="product-item-shop__title">
												<a href="<?php echo get_category_link($cat->term_id); ?>"><?php echo $cat->name; ?></a>
											</div>
										 <?php $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
										 $image = wp_get_attachment_url( $thumbnail_id );  ?>
											<a href="<?php echo get_category_link($cat->term_id); ?>" class="image-link"><img src="<?php echo $image; ?>" class="image" alt="image" /></a>
											<div>
												<a href="<?php echo get_category_link($cat->term_id); ?>" class="more"><?php _e('לפרטים נוספים ', 'easyline') ?>></a>
											</div>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
				<section class="slider-wrap">
					<div class="col-md-5">
						<div class="row">
							<div class="slide__image slider-image">
								<img src="/wp-content/uploads/2018/06/slider.png" alt="slider" />
							</div>
						</div>
					</div>
					<div class="slider">
						<?php $slider = get_field("slider");
						foreach ($slider as $key => $slide) { ?>
						<div class="slide">
							<div class="row">
								<div class="col-sm-11">
									<div class="desc"><?php echo $slide["text"]; ?></div>
								</div>

							</div>
						</div>
						<?php } ?>
					</div>
				</section>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
