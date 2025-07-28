  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <div class="footer-top">
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-6">
                <form action="#" method="post" class="question" id="question"
                  aria-label="<?php echo __('טופס פניות ושאלות', 'easyline'); ?>">
                  <div class="question__title"><?php echo __('נשמח לפניות ושאלות ', 'easyline'); ?></div>
                  <div class="input-wrap">
                    <input type="text" name="name_user" id="name_user"
                      placeholder="<?php echo __('שם מלא (שדה חובה)', 'easyline'); ?>" required />
                  </div>
                  <div class="input-wrap">
                    <input type="text" name="mail_user" id="mail_user"
                      placeholder="<?php echo __('טלפון (שדה חובה)', 'easyline'); ?>" required />
                  </div>
                  <div class="input-wrap">
                    <input type="submit" class="submit" value="<?php echo __('שלח ', 'easyline'); ?>">
                  </div>
                  <div class="thanks">
                    <?php _e('שלח ', 'easyline') ?>
                  </div>
                </form>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-6">
                <?php $arg2 = array(  'echo' => false,
                  'container'  => false,
                  'theme_location' => 'primary2',
                  'menu_class' => false,
                  'items_wrap' => '<ul id="%1$s" class="footer-menu">%3$s</ul>',
                  // 'depth' => 1,
                  // 'fallback_cb' => 'fallbackmenu'
                )  ?>
                <?php echo preg_replace(array( '<ul>', '/menu-item /U'), array('ul class=footer-menu','menu-wrap-top__item '), wp_nav_menu( $arg2));  ?>
                <div class="social-footer">
                  <?php $social = get_field("social", 'options');
                    if($social){
                      foreach ($social as $key => $value) { ?>
                  <a href="<?php echo $value["link"]; ?>" class="facebook">
                    <img src="<?php echo $value["image"]["url"]; ?>" alt="facebook" />
                  </a>
                  <?php } ?>
                  <?php } ?>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-6">
                <ul class="footer-menu">
                  <?php $arg = array(  'echo' => false,
                    'container' => false,
                    'theme_location' => 'primary3',
                    'menu_class'=>false,
                    'items_wrap' => '%3$s',
                    // 'depth' => 1,
                    // 'fallback_cb'=> 'fallbackmenu'
                  )  ?>
                  <?php echo preg_replace(array( '<ul>', '/menu-item /U'), array('ul class=footer-menu','menu-wrap-top__item '), wp_nav_menu( $arg)); ?>
                </ul>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-6">
                <ul class="footer-menu">
                  <?php  $arg3 = array(  'echo' => false,
                    'container'  => false ,
                    'theme_location' => 'primary4',
                    'menu_class'=>false,
                    'items_wrap' => '%3$s',
                    // 'depth'           => 1,
                    // 'fallback_cb'=> 'fallbackmenu'
                  ) ?>
                  <?php echo preg_replace(array( '<ul>', '/menu-item /U'), array('ul class=footer-menu','menu-wrap-top__item '), wp_nav_menu( $arg3)); ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-bottom">
            <ul class="phones">
              <li><a href="tel:<?php echo str_replace("-","",get_field("phone", 'options')); ?>">
                  <?php _e('טל ', 'easyline') ?> <?php echo get_field("phone", 'options'); ?></a></li>
            </ul>
            <div class="adress"><?php echo get_field("adress", 'options'); ?></div>
            <a href="mailto:<?php echo get_field("mail", 'options'); ?>"
              class="mail"><?php echo get_field("mail", 'options'); ?></a>
            <div class="privacy"><?php echo __('ט.ל.ח    התמונות להמחשה בלבד ', 'easyline'); ?></div>
            <div class="copyright"><?php echo __('כל הזכויות שמורות לאיזיליין© ', 'easyline'); ?></div>
            <div class="logo-footer">
              <?php if (!is_front_page()){ ?>
              <a href="<?php echo home_url(); ?>">
                <?php } ?>
                <img src="<?php echo get_field("logo-footer", 'options')["url"]; ?>"
                  alt="<?= __('לוגו איזיליין', 'easyline') ?>" />
                <?php if (!is_front_page()){ ?>
              </a>
              <?php } ?>
            </div>
            <div class="build">
              Built By <a href="http://webeffect.co.il/">Webeffect</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <div class="mfp-hide">
    <div class="thanks-popup" id="thanks">
      <div class="thanks__title">
        <?php echo __('המוצר נוסף בהצלחה לעגלת הקניות ', 'easyline'); ?>
      </div>
      <div class="btn-wrap">
        <a href="" class="product-btn close-popup"><?php _e('המשך בקניות ', 'easyline') ?></a>
        <a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" class="product-btn">
          <?php _e('עבור אל סל הקניות ', 'easyline') ?>
        </a>
      </div>
    </div>
  </div>

  <div class="mfp-hide">
    <div class="thanks-popup" id="thanks_wishlist">
      <div class="thanks__title">
        <?php _e('המוצר נוסף בהצלחה לרשימת המשאלות ', 'easyline') ?>
      </div>
      <div class="btn-wrap">
        <a href="" class="product-btn close-popup"><?php _e('המשך בקניות ', 'easyline') ?></a>
        <a href="<?php echo get_permalink( 125 ); ?>"
          class="product-btn"><?php _e('עבור לרשימת משאלות ', 'easyline') ?></a>
      </div>
    </div>
  </div>
  <!-- END out-->

  <?php wp_footer(); ?>
  </body>

  </html>