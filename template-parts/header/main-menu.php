<nav class="main-menu" aria-label="<?= _e('Main Menu', 'easyline') ?>">
  <ul>
    <?php if (is_user_logged_in()) : ?>
      <li class="sub">
        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
          <?= easyline_get_user_name(); ?>
        </a>
      </li>
    <?php else : ?>
      <li>
        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('התחברות ', 'easyline') ?></a>
      </li>
    <?php endif; ?>

    <?php
      wp_nav_menu( array(
        'theme_location' => 'header',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'fallback_cb'    => false,
      ) );
    ?>
  </ul>
</nav>