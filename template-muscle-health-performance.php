<?php
/**
 * Template Name: Muscle Health & Performance
 */

get_header();
?>
<main class="content-page muscle-health-page">
	<div class="container">
		<div class="row">
			<?php get_sidebar(); ?>
			<div class="col-md-10 col-xs-12">
				<?php get_template_part('template-parts/pages/muscle-health-performance/content'); ?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
