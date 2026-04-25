<?php
/**
 * Template Name: Business order page
 */

get_header();

$curent_lang = get_locale() == 'he_IL' ? heb_fields() : eng_fields();
$data = get_fields();

global $wpdb;
$cities = $wpdb->get_results("SELECT city_name FROM " . $wpdb->prefix . "israel_cities order by city_name", ARRAY_A);

?>

<main class="content-page business_order_page">
  <form id="business_order_form" aria-label="Business order form">
    <div class="container">
      <div class="row">
        <?php get_sidebar(); ?>
        <div class="col-md-10 col-xs-12">
          <div class="category">
            <section class="breadcrumbs">
              <ul class="list-unstyled breadcrumbs__list">
                <li class="breadcrumbs__item" itemprop="itemListElement">
                  <a href="<?= home_url(); ?>" class="breadcrumbs__link" itemprop="item">
                    <span class="breadcrumbs__name"
                      itemprop="name"><?php $home_title = get_the_title(get_option('page_on_front'));
                      echo $home_title; ?></span>
                  </a>
                  >
                </li>
                <li class="current breadcrumbs__item">
                  <span><?= the_title(); ?></span>
                </li>
              </ul>
            </section>
            <div class="container content form-contacts">
              <div class="row">
                <div class="col-md-12">
                  <h1 class="title"><?php the_title(); ?></h1>
                </div>
              </div>
              <h2 style="font-size: 0px; line-height: 0">Form</h2>
              <div class="row">
                <div class="col-md-4">
                  <label for="name"><?= $curent_lang['name'] ?> *</label>
                  <input type="text" name="name" id="name" class="addToForm" required>
                </div>
                <div class="col-md-4">
                  <label for="id_card"><?= $curent_lang['id_card'] ?> *</label>
                  <input type="text" name="id_card" id="id_card" class="addToForm" required
                    oninput="this.value = this.value.replace(/[^0-9-]/g, '');" maxlength="9">
                </div>
                <div class="col-md-4">
                  <label for="customer"><?= $curent_lang['customer'] ?> *</label>
                  <input type="text" name="customer" id="customer" class="addToForm" required>
                </div>
                <div class="col-md-4">
                  <label for="office_phone"><?= $curent_lang['office_phone'] ?> *</label>
                  <input type="tel" name="office_phone" id="office_phone" class="addToForm" required
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                </div>
                <div class="col-md-4">
                  <label for="mobile_phone"><?= $curent_lang['mobile_phone'] ?> *</label>
                  <input type="tel" name="mobile_phone" id="mobile_phone" class="addToForm" required
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                </div>
                <div class="col-md-4">
                  <label for="email"><?= $curent_lang['email'] ?> *</label>
                  <input type="email" name="email" id="email" class="addToForm" required>
                </div>
                <div class="col-md-4">
                  <label for="address"><?= $curent_lang['address'] ?> *</label>
                  <input type="text" name="address" id="address" class="addToForm" required>
                </div>
                <div class="col-md-4">
                  <label for="city"><?= $curent_lang['city'] ?> *</label>
                  <input list="my_country_field_list" id="city" name="city" class="addToForm" />
                  <datalist id="my_country_field_list">
                    <?php
                    foreach ($cities as $city) {
                        echo ('<option value="' . trim($city['city_name']) . '">');
                    }
                    ?>
                  </datalist>
                </div>
                <div class="col-md-4">
                  <label for="comment"><?= $curent_lang['comment'] ?></label>
                  <textarea name="comment" id="comment" cols="45" rows="5" class="addToForm"></textarea>
                </div>
              </div>

              <div class="row">
                <?php if ($data['products_to_order_by_units']): ?>
                    <div class="col-md-12 products_to_order_by_units">
                      <h3 class="title"><?= $curent_lang['units'] ?></h3>
                    </div>
                    <?php foreach ($data['products_to_order_by_units'] as $item): ?>
                        <div class="col-md-4">
                          <div class="single_product">
                            <?= get_the_post_thumbnail($item['product'], 'thumbnail'); ?>
                            <div class="product_data">
                              <h4 class="title"><?= get_the_title($item['product']); ?></h4>
                              <?= $item['short_description'] ?>
                              <label>
                                <span style="line-height: 0; font-size: 0px">Product Units <?= $item['product']; ?></span>
                                <input type="hidden" id="product-units-<?= $item['product']; ?>"
                                  name="product_units__<?= $item['product']; ?>"
                                  data-value="<?= get_the_title($item['product']); ?>"
                                  class="addToForm product_units__<?= $item['product']; ?>"
                                  aria-label="Product Units <?= $item['product']; ?>">
                              </label>
                              <div class="d-flex">
                                <button type="button" class="addCounter"
                                  data-counter=".product_counter__<?= $item['product']; ?>"><img
                                    src="/wp-content/themes/easyline2/img/plus.png" alt=""></button>

                                <label>
                                  <span style="line-height: 0; font-size: 0px">Units <?= $item['product']; ?></span>
                                  <input type="number" id="units-<?= $item['product']; ?>"
                                    name="units__<?= $item['product']; ?>"
                                    class="product_counter product_counter__<?= $item['product']; ?>" value="0" min="0"
                                    aria-label="Units <?= $item['product']; ?>">
                                </label>

                                <button type="button" class="subCounter"
                                  data-counter=".product_counter__<?= $item['product']; ?>"><img
                                    src="/wp-content/themes/easyline2/img/minus.png" alt=""></button>
                              </div>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($data['products_to_order_by_cartons']): ?>
                    <div class="col-md-12 products_to_order_by_cartons">
                      <h3 class="title"><?= $curent_lang['cartons'] ?></h3>
                    </div>
                    <?php foreach ($data['products_to_order_by_cartons'] as $item): ?>
                        <div class="col-md-4">
                          <div class="single_product">
                            <?= get_the_post_thumbnail($item['product'], 'thumbnail'); ?>
                            <div class="product_data">
                              <h4 class="title"><?= get_the_title($item['product']); ?></h4>
                              <?= $item['short_description'] ?>
                              <input type="hidden" name="product_cartons__<?= $item['product']; ?>"
                                data-value="<?= get_the_title($item['product']); ?>"
                                class="addToForm product_cartons__<?= $item['product']; ?>">
                              <div class="d-flex">
                                <button type="button" class="addCounter"
                                  data-counter=".product_counter__<?= $item['product']; ?>"><img
                                    src="/wp-content/themes/easyline/img/plus.png" alt=""></button>
                                <input type="number" name="cartons__<?= $item['product']; ?>"
                                  class="product_counter product_counter__<?= $item['product']; ?>" value="0" min="0">
                                <button type="button" class="subCounter"
                                  data-counter=".product_counter__<?= $item['product']; ?>"><img
                                    src="/wp-content/themes/easyline/img/minus.png" alt=""></button>
                              </div>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <hr>
                  <?= $curent_lang['term_block'] ?>
                  <label for="terms">
                    <input type="checkbox"
                      class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms"
                      id="terms" required>
                    <?= $curent_lang['term_label'] ?>
                  </label>
                </div>
                <div class="col-md-12 text-center">
                  <button type="submit" class="button submit"><?= $curent_lang['submit'] ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div class="preloader">
    <img src="/wp-content/themes/easyline/img/preloader.gif" alt="">
    <div class="thanks" style="display:none;">
      <h2><?= $curent_lang['thanks'] ?></h2>
    </div>
  </div>
</main>

<script>
jQuery(document).ready(($) => {

  $('.addCounter, .subCounter').on('click', function() {
    let counterClass = $(this).data('counter'),
      btnClass = $(this).attr('class'),
      currentValue = parseInt($(counterClass).val());

    if (currentValue >= 0 && btnClass === 'addCounter') {
      $(counterClass).val(++currentValue);
    } else if (currentValue > 0 && btnClass === 'subCounter') {
      $(counterClass).val(--currentValue);
    }
  })

  $('#id_card').on('change', function() {
    if ($(this).val().length !== 9) {
      $(this).focus();
      alert('<?= $curent_lang['no_correct'] ?>');
    }
  })

  $('form').on('submit', function(event) {
    event.preventDefault();
    let data = {};
    $('.product_counter').each(function(intex, item) {
      let nameProduct = `.product_${$(item).attr('name')}`;
      if (parseInt($(item).val()) > 0) {
        $(nameProduct).val(`${$(nameProduct).data('value')} x ${$(item).val()}`);
      }
    })
    $('.addToForm').each(function(intex, item) {
      data[this.name] = $(this).val();
    })

    $.ajax({
      type: 'POST',
      url: admin_ajax.url,
      data: {
        action: 'business_order',
        data: data
      },
      beforeSend: function() {
        $('.preloader').addClass('active');
      },
      success: function(data) {
        console.log(data);
        $('.preloader img').css('display', 'none');
        $('.preloader .thanks').fadeIn();
        $('#business_order_form')[0].reset();
        setTimeout(() => {
          $('.preloader').fadeOut();
        }, 4000);
      },
    });
  })
})
</script>

<?php
get_footer();