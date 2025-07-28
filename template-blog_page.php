<?php
/**
 * Template Name: Blog_page
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
						<div class="blog-wrap">
							<?php
							// $slug = get_queried_object();
								$params = array(
									'post_type' => 'post',
									'cat' => 53,
									'posts_per_page' => -1,
									'orderby' => 'date',
									'order' => 'DESC',
								);
								$the_query = new WP_Query( $params );

							if( $the_query->have_posts() ) :
								while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
									<section class="blog-page">

										<div class="blog-content">

											<div class="blog-title">
												<a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
											</div>

											<a href="<?php echo get_the_permalink(); ?>" class="image-link">
												<?php the_post_thumbnail('medium', ['class' => 'image']); ?>
											</a>

											<?php
												$text = wp_strip_all_tags( get_the_content() );
												$content = str_words_count($text, $counttext = 30, $sep = ' ');
											?>
											<div class="blog-desc"><?php echo $content ?></div>

											<div class="blog-more">
												<a href="<?php echo get_the_permalink($value->ID); ?>" class="more"><?php echo __('לפרטים נוספים>', 'easyline'); ?></a>
											</div>

										</div>

									</section>
									<?php
								endwhile;
							endif;

							wp_reset_postdata();
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
