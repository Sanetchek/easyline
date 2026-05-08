<?php
/**
 * Template Name: FAQ
 */
 get_header();
 ?>
 <main class="content-page">
   <div class="container">
     <div class="row">
      <?php get_sidebar(); ?>
       <div class="col-md-10 col-xs-12">
         <?php
         $bg_select = get_field('bg_select',$post->ID);
         $bg = get_field('bg',$post->ID);
         $color = get_field('color',$post->ID);
         $resolve_acf_image_url = static function ($image_field): string {
           if (is_array($image_field)) {
             return isset($image_field['url']) ? (string) $image_field['url'] : '';
           }

           if (is_numeric($image_field)) {
             $image_url = wp_get_attachment_image_url((int) $image_field, 'full');
             return $image_url ? (string) $image_url : '';
           }

           if (is_string($image_field) && filter_var($image_field, FILTER_VALIDATE_URL)) {
             return $image_field;
           }

           return '';
         };
         $bg_image_url = $resolve_acf_image_url($bg);
         $faq = get_field('faq_new',$post->ID);
         if (!is_array($faq)) {
           $faq = [];
         }
         ?>
         <div class="products-page faq-page" <?php if ($bg_select=="bg" && $bg_image_url){ ?>style="background-image:url(<?php echo esc_url($bg_image_url); ?>);background-repeat:no-repeat;background-position:left;background-size:cover;" <?php } ?> <?php if ($bg_select=="color"){ ?>style="background:<?php echo esc_attr(get_field('color_bg',$post->ID)); ?>;" <?php } ?>>
           <section class="breadcrumbs">
                 <ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                   <li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                     <a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
                       <span class="breadcrumbs__name" itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
                     </a>
                     >
                   </li>
                   <li class="current breadcrumbs__item">
                     <span><?php echo the_title(); ?></span>
                   </li>
                 </ul><!-- .breadcrumbs -->
           </section>
           <div class="content">
             <h1><?php the_title(); ?></h1>
              <div class="faq">
                <div class="faq-list">
                  <?php foreach ($faq as $key => $faq_value) { ?>
                  <div class="faq-list-item">
                    <a href="javascript:void(0);" class="faq-list-item__title">
                      <span class="slide-icon">
                        <span class="">+</span>
                        <span class="hide">-</span>
                      </span>
                      <?php echo $faq_value["faq_new_title"]; ?>
                    </a>
                    <div class="faq-list-item__content">
                      <?php echo $faq_value["faq_new_content"]; ?>
                    </div>
                  </div>
                  <?php } ?>
                </div>
             </div>
           </div>
           <br/>
          <?php $image = get_field("image",$post->ID);
          $image_url = $resolve_acf_image_url($image);
          if($image_url){ ?>
           <div>
           <img src="<?php echo esc_url($image_url); ?>" alt="products" />
           </div>
          <?php } ?>
         </div>
       </div>
     </div>
   </div>
 </main>
<?php get_footer(); ?>
