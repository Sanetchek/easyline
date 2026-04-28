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
								$page_id = get_the_ID();
								$selected_category_ids = easyline_get_blog_page_visible_category_ids($page_id);
								$blog_categories = easyline_get_blog_category_terms(array(
									'include' => $selected_category_ids,
								));

								if (!empty($blog_categories)) :
									foreach ($blog_categories as $blog_category) :
										$category_link = get_category_link($blog_category->term_id);
										$category_image = easyline_get_blog_category_image_url($blog_category->term_id, 'medium');
										?>
										<section class="blog-page blog-category-page">
											<div class="blog-content blog-category-content">
												<div class="blog-title">
													<a href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($blog_category->name); ?></a>
												</div>

												<a href="<?php echo esc_url($category_link); ?>" class="image-link">
													<img src="<?php echo esc_url($category_image); ?>" class="image" alt="<?php echo esc_attr($blog_category->name); ?>" />
												</a>
											</div>
										</section>
										<?php
									endforeach;
								else :
									?>
									<div class="blog-empty"><?php echo esc_html__('לא נמצאו קטגוריות מאמרים להצגה.', 'easyline'); ?></div>
									<?php
								endif;
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
