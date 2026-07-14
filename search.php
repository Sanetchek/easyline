<?php
get_header(); ?>
<main class="content-page">
	<div class="container">
		<div class="row">
		  <?php get_sidebar(); ?>
			<div class="col-md-10  col-xs-12">
				<div class="category">
					<div class="content">
						<div class="content-top">
							<h1 class="title"><?php _e('חיפוש ', 'easyline') ?></h1>
						</div>
						<?php if ($_GET["s"]){ ?>
							<div id="loop" class="row bg_white" >
							<?php    if (have_posts()) : ?>
							<?php  while(have_posts()) : the_post(); ?>
								<?php $search_excerpt = easyline_get_post_list_excerpt_plain(get_the_ID()); ?>
								<div class="post">
									 <div class="search">
									 <h3><a href="<?php  the_permalink() ?>"><?php the_title(); ?></a></h3>
									 <?php if ($search_excerpt !== '') : ?>
									 <p><?php echo esc_html($search_excerpt); ?></p>
									 <?php endif; ?>
								</div>
								</div>
								<?php endwhile; ?>
								</div>
								<div id="pagination" class="more_reviw">
								 <?php next_posts_link(); ?>
							</div>
							<?php	else : ?>
						 	<div class="not_information" >
							 	<div class="wrap_title clearfix">
								<?php _e('דבר לא נמצא ', 'easyline') ?>
								</div>
							</div>
						<?php
							endif;
						?>
					<?php   wp_reset_query();      ?>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
