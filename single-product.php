<?php

get_header();

$slug = get_queried_object();
$cat_id = get_the_terms( $post->ID, 'product_cat');
$_product = new WC_Product( $post->ID );
$post_current = $post->ID;
$attachment_ids = $_product->get_gallery_attachment_ids();
// variable usage
global $product;
?>

<main class="content-page">
  <div class="container">
    <div class="row">
      <?php get_sidebar(); ?>
      <div class="col-md-10  col-xs-12">
        <div class="category product">
          <section class="breadcrumbs">
                <ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                  <li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
                      <span class="breadcrumbs__name" itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
                    </a> >
                  </li>
                  <li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a href="<?php echo get_the_permalink(42); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
                      <span class="breadcrumbs__name" itemprop="name"><?php echo get_the_title(42); ?></span>
                    </a> >
                  </li>
                  <li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a href="<?php echo get_term_link($cat_id[0]->term_id); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
                      <span class="breadcrumbs__name" itemprop="name"><?php echo $cat_id[0]->name; ?></span>
                    </a> >
                  </li>
                  <li class="current breadcrumbs__item">
                    <span><?php echo the_title(); ?></span>
                  </li>
                </ul><!-- .breadcrumbs -->
          </section>
          <div class="content">
            <div class="content-top">
              <h1 class="title-big"><?php echo get_the_title($post->ID); ?></h1>
              <div class="row content-product">
                <div class="col-md-5 col-sm-12">
                  <div class="product-slider-one">
                    <div class="slide">
                      <?php if($product->is_on_sale()) : ?>
                        <span class="sale_message"><?php echo __('מבצע ', 'easyline'); ?></span>
                      <?php endif; ?>

                      <a href="<?php the_post_thumbnail_url(); ?>" class="product_img"><?php the_post_thumbnail("thumbnail-215x300"); ?></a>
                    </div>
                  </div>
                </div>

                <div class="col-md-7 col-sm-12">
                  <div class="desc">
                    <?php $content_post = get_post($post->ID);
                			$content = $content_post->post_content;
                			$content = apply_filters('the_content', $content);
                			$content = str_replace(']]>', ']]&gt;', $content);
                			echo $content; ?>
                  </div>

                  <div class="info-product">
                    <?php  if(get_field("image_below_description",$post->ID)["url"]){ ?>
                    <div class="image">
                      <a href="<?php echo get_field("image_below_description_big",$post->ID)["url"]; ?>" class="popup">
                        <img src="<?php echo get_field("image_below_description",$post->ID)["url"]; ?>" alt="product" />
                      </a>
                    </div>
                  <?php } ?>

                    <div class="info-product-content">
                      <div class="select-wrap">
                        <div class="arrow-select">
                          <select name="product_var" id="product_var">
                            <option value=""><?php _e('בחר אפשרות: ', 'easyline') ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="top-line">
                        <?php
                        $product = wc_get_product();
                        $product_attr = $product->get_attribute('pa_נפח');
                        if ($product_attr) { ?>
                          <div class="kg"><?php echo $product_attr; ?></div>
                        <?php } ?>

                        <?php if ($product->is_on_sale()) : ?>
                          <div class="price"><del><?php echo $product->get_regular_price(); ?> ₪</del></div>
                          <span class="price"><?php echo $product->get_sale_price(); ?> ₪</span>
                        <?php else : ?>
                          <div class="price"><?php echo $product->get_regular_price(); ?> ₪</div>
                        <?php endif; ?>

                        <?php show_add_to_cart_form($post->ID, $product->is_in_stock()); ?>

                        <?php if ($show_wishlist_btn) : ?>
                          <div class="two-button">
                            <a href="<?php echo esc_url(add_query_arg('add_to_wishlist', $post->ID)) ?>"
                              rel="nofollow"
                              data-product-id="<?php echo $post->ID; ?>"
                              data-product-type="<?php echo $product_type; ?>"
                              class="<?php echo $link_classes; ?>">
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </a>
                          </div>
                        <?php endif ?>
                      </div>


                      <?php if ($_product->get_sku()){ ?>
                      <div class="sku">
                        <?php _e('מק"ט: ', 'easyline') ?> <?php echo $_product->get_sku(); ?>
                      </div>
                    <?php } ?>
                      <div class="bottom-line">
                        <div class="shiping">
                        <?php echo get_field("text",$post->ID); ?>
                        </div>
                        <div class="price red">
                          <?php
                            if($_product->stock_status=="outofstock"){
                              echo __('אזל מהמלאי', 'easyline');
                            } else {
                              echo '';
                            }?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php
                $slug = $cat_id[0]->slug;

                $params = array(
                  'post_type' => 'product',
                  'posts_per_page' => -1,
                  'exclude' => $post_current,
                  'orderby' => 'menu_order',
                  'order' => 'ASC',
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'product_cat',
                      'field' => 'slug',
                      'terms' => $slug
                    )
                  )
                );
                $recent_posts_array = get_posts( $params );
                if($recent_posts_array){
              ?>

              <div class="content-top">
                  <div class="title-big"><?php _e('מוצרים שיענינו אתכם ', 'easyline') ?></div>
                <div class="slider-shop products">
                  <?php foreach ($recent_posts_array as $key => $value) {
                      $_product = new WC_Product( $value->ID );
                    ?>
                    <div class="product-item">
                      <a href="<?php echo get_the_permalink($value->ID); ?>" class="product-item-image">
                        <span class="title"><?php echo get_the_title($value->ID); ?><br/>
                          <span class="price-hide"><?php echo $_product->get_regular_price(); ?> ₪</span>
                        </span>
                        <?php echo get_the_post_thumbnail($value->ID); ?>
                      </a>
                      <div class="button-wrap">
                        <a href="<?php echo get_the_permalink($value->ID); ?>" class="product-btn"><?php echo __('לפרטים>', 'easyline'); ?></a>

                        <?php $show_wishlist_btn = false; ?>
                        <?php if ($show_wishlist_btn) : ?>
                          <div class="two-button">
                            <a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $value->ID ) )?>" rel="nofollow" data-product-id="<?php echo $value->ID ?>" data-product-type="<?php echo $product_type?>" class="<?php echo $link_classes ?>" >
                              <i class="fa fa-star" aria-hidden="true"></i>
                            </a>
                          </div>
                        <?php endif ?>

                        <?php show_add_to_cart_form($value->ID, $_product->is_in_stock()); ?>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>

