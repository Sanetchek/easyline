<!doctype html>

<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="theme-color" content="#fff">

  <?php wp_head(); ?>
</head>

<?php $langClass = is_rtl() ? 'rtl' : 'ltr'; ?>
<body <?php body_class($langClass); ?>>
  <?php wp_body_open(); ?>

  <header class="header">
    <div class="container">
      <div class="row">
        <div class="col-md-2 col-xs-12">
          <?php get_template_part( 'template-parts/header/logo' ); ?>
        </div>

        <div class="col-md-10 col-xs-12">
          <div class="top-line-wrap">
            <div class="top-line">
              <?php easyline_header_toggle_menu(); ?>

              <?php show_phone_number(); ?>

              <?php get_search_form(); ?>

              <?php get_template_part( 'template-parts/header/main-menu' ); ?>

              <?php get_template_part( 'template-parts/header/cart' ); ?>

              <div class="language dropdown">
                <?php	do_action('wpml_add_language_selector'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>