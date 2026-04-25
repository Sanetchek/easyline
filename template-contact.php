<?php
/**
 * Template Name: Contact
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
         ?>
         <div class="products-page contacts-page" <?php if ($bg_select=="bg"){ ?>style="background-image:url(<?php echo get_field('bg',$post->ID)['url']; ?>);background-repeat:no-repeat;background-position:left;background-size:cover;" <?php } ?> <?php if ($bg_select=="color"){ ?>style="background:<?php echo get_field('color_bg',$post->ID); ?>;" <?php } ?>>
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
             <div class="row no-margin">
               <div class="col-sm-5 contact-form">
                 <div class="bg-contact">
                   <?php the_post(); the_content(); ?>
                 </div>
               </div>
               <div class="col-sm-7 map-wrap">
                 <div id="map" class="map"></div>
               </div>
             </div>
             <br/>
             <div class="row no-margin">
               <div class="col-sm-6 adress">
                <div class="bg-contact">
                  <h4><?php _e('שמרו על קשר ', 'easyline') ?></h4>
                  <p><strong><?php bloginfo('name') ?></strong></p>
                  <p class="marg_cont"><?php _e('טל: ', 'easyline') ?><a href="tel:046327777"><?php the_field('fax', 'options') ?></a></p>
                  <p><?php _e('דוא”ל: ', 'easyline') ?></span> <span><?php the_field('mail', 'options') ?></p>
                  <p><?php _e('כתובת: ', 'easyline') ?></span> <span><?php the_field('adress', 'options') ?></p>
                </div>
               </div>
               <div class="col-sm-6 social">
                 <div class="bg-contact">
                    <h4><?php _e('חפשו אותנו ברשת ', 'easyline') ?></h4>
                    <p><?php _e('נשמח אם תעקבו, תשתפו תפרגנו אנחנו תמיד זמינים בכל פלטפורמה בכל זמן ', 'easyline') ?></p>
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
             </div>
           </div>
           <?php $image = get_field("image", 'options');
           if($image){ ?>
           <div>
            <img src="<?php echo $image['url']; ?>" alt="products" />
           </div>
          <?php } ?>
         </div>
       </div>
     </div>
   </div>
 </main>
<?php get_footer(); ?>
