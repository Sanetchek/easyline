<?php
/**
 * Template Name: Blog
 */
get_header();

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$blog_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => false,
	)
);
?>
<main class="content-page">
	<div class="container">
		<div class="row">
			<?php get_sidebar(); ?>
			<div class="col-md-10 col-xs-12">
				<div class="products-page">
					<div class="content">
						<h1><?php the_title(); ?></h1>
						<?php
						if ($blog_query->have_posts()) :
							while ($blog_query->have_posts()) :
								$blog_query->the_post();
								?>
								<h2><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
								<p><?php echo esc_html(easyline_get_post_list_excerpt_plain(get_the_ID())); ?></p>
								<?php
							endwhile;
							wp_reset_postdata();

							if ($blog_query->max_num_pages > 1) :
								?>
								<nav class="pagination" aria-label="<?php echo esc_attr__('Blog pagination', 'easyline'); ?>">
									<?php
									echo wp_kses_post(
										paginate_links(
											array(
												'total'   => (int) $blog_query->max_num_pages,
												'current' => $paged,
												'type'    => 'list',
											)
										)
									);
									?>
								</nav>
								<?php
							endif;
						endif;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
