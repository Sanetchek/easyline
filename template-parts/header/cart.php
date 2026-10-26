<div class="cart">
  <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/')); ?>" class="cart-icon">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cart.png'); ?>" alt="cart" />
    <span class="cart-count" id="header-cart-count">
      <?php
      if (function_exists('easyline_has_woocommerce_session') && easyline_has_woocommerce_session() && function_exists('WC') && WC()->cart) {
        echo esc_html((string) WC()->cart->get_cart_contents_count());
      } else {
        echo '0';
      }
      ?>
    </span>
  </a>

  <div class="cart-header">
    <div id="updated_header_mini_cart">
      <?php
      if (function_exists('easyline_has_woocommerce_session') && easyline_has_woocommerce_session() && function_exists('woocommerce_mini_cart')) {
        woocommerce_mini_cart();
      }
      ?>
    </div>
  </div>
</div>
