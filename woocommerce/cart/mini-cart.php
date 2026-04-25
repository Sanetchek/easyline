<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 50.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>
	<div class="title"><?php if (is_user_logged_in()){
		$user_info = get_userdata(get_current_user_id());
		$first_name = $user_info->first_name;
		$last_name = $user_info->last_name;
		echo $first_name .' '. $last_name;
} else { echo __('מחובר: לקוח אורח', 'easyline'); }?></div>
	<div class="basket-right-content" id="test2">
		<div class="info">
			<?php echo WC()->cart->get_cart_contents_count(); ?> <?php _e('מוצרים בסל ', 'easyline') ?><br/>
			<div class="price"><?php _e('סה״כ ', 'easyline') ?> <?php echo WC()->cart->get_cart_subtotal(); ?></div>
		</div>
		<ul class="list">
			<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<li class="list-item">
							<div class="list-item-image">
								<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
								?>
								<?php if ( empty( $product_permalink ) ) : ?>
									<div class="image">
										<?php echo $thumbnail; ?>
									</div>
								<?php else : ?>
									<div class="image">
										<a href="<?php echo esc_url( $product_permalink ); ?>">
											<?php echo $thumbnail; ?>
										</a>
									</div>
								<?php endif; ?>
							</div>

							<div class="list-item-content">
								<div class="list-item-info">
									<a href="" class="list-item-info__title"><?php echo get_the_title($product_id); ?></a><br />
									<span class="count-title"><?php _e('כמות: ', 'easyline') ?> </span><span
										class="count"><?php echo $cart_item['quantity']; ?></span>
								</div>

								<div class="btn-quantity ajax-update-cart">
									<input type="hidden" name="" data-id="<?php echo $cart_item_key; ?>"
										value="<?php echo $cart_item['quantity']; ?>">
									<a href="#" class="plus">
										<img src="<?php echo get_template_directory_uri(); ?>/img/plus.png" alt="plus" />
									</a>

									<a href="#" class="minus">
										<img src="<?php echo get_template_directory_uri(); ?>/img/minus.png" alt="minus" />
									</a>
								</div>

								<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
							</div>
						</li>
						<?php
					}
				}

				do_action( 'woocommerce_mini_cart_contents' );
			?>
			</li>
		</ul>
	</div>
	<div class="basket-right-footer">
		<?php global $woocommerce; ?>
		<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="product-btn"><?php _e('לקופה ', 'easyline') ?></a>
	</div>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
