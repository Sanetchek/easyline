<?php get_header(); ?>
<main class="content-page">
	<div class="container">
		<div class="row">
			<?php get_sidebar(); ?>
			<div class="col-md-10 col-xs-12">
				<div class="category">
					<section class="breadcrumbs">
								<ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
									<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
										<a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
											<span class="breadcrumbs__name" itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
										</a>
										>
									</li>
									<li class="current breadcrumbs__item">
										<span><?php the_title(); ?></span>
									</li>
								</ul><!-- .breadcrumbs -->
					</section>
					<div class="content">
						<div class="content-top">
							<h1 class="title"><?php the_title(); ?></h1>
						</div>
            <?php the_post(); the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
