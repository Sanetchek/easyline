<?php
/**
 */
get_header(); ?>
<main class="content-page">
	<div class="container">
		<div class="row">
			  <?php get_sidebar(); ?>
			<div class="col-md-10 col-xs-12">
				<div class="category">
					<div class="content">
						<div class="content-top">
							<h1 class="title">404</h1>
						</div>
						<p><?php _e('אתה נמצא כאן מפני שהזנת כתובת דף שאינה קיימת עוד או הועברה לכתובת אחרת ', 'easyline') ?></p>
						<a href="<?php echo home_url(); ?>" class="btn_customer"><?php _e('נסה לחפש כאן ', 'easyline') ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
