<?php global $woocommerce; ?>

<div class="cart">
  <a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="cart-icon">
    <img src="<?php echo get_template_directory_uri(); ?>/img/cart.png" alt="cart" />
    <span class="cart-count" id="header-cart-count">
      <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
  </a>

  <div class="cart-header">
    <div id="updated_header_mini_cart"><?php woocommerce_mini_cart(); ?></div>
  </div>
</div>