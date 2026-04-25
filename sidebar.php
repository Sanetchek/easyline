<div class="col-md-2 col-xs-12 sidebar">
	<nav class="right-menu" aria-label="<?= __('MEnu Right', 'easyline') ?>">
		<ul>
			<?php  $arg2 = array( 'echo' => false,
				'container'  => false ,
				'theme_location' => 'primary',
				'menu_class' => false,
					'items_wrap' => '%3$s',
				// 'depth'           => 1,
				// 'fallback_cb'=> 'fallbackmenu'
			)   ?>
			<?php echo preg_replace(array( '<ul>', '/menu-item /U'), array('ul class=menu-wrap-top','menu-wrap-top__item '), wp_nav_menu( $arg2)); ?>
		</ul>
	</nav>

	<?php if (!is_user_logged_in()) : ?>
		<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="btn-cart">
			<img class="icon" src="<?php echo get_template_directory_uri(); ?>/img/cart-black.png" alt="cart" />
			<span class="text"><?php _e('לקוח חדש? ', 'easyline') ?> <br/><?php _e('לחץ כאן ', 'easyline') ?></span>
		</a>
	<?php endif; ?>

	<?php $show_mini_cart = false; ?>
	<?php if ($show_mini_cart) : ?>
		<div class="basket-right">
			<div class="widget_shopping_cart_content">
			<?php woocommerce_mini_cart(); ?>
			</div>
		</div>
	<?php endif ?>

	<?php $banners = get_field("banners", 'options'); ?>

	<?php if ($banners) : ?>
		<div class="banners">
			<?php foreach ($banners as $key => $value) { ?>
				<?php if ($value["link"]) : ?>
					<a href="<?php echo $value["link"]; ?>" class="banner-right">
						<img src="<?php echo $value["banner"]['url']; ?>" alt="banner" />
					</a>
				<?php endif ?>
			<?php } ?>
		</div>
	<?php endif ?>
</div>
