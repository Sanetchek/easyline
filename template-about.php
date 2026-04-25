<?php
/**
 * Template Name: About
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
          <div class="about-page" <?php if ($bg_select=="bg"){ ?>style="background-image:url(<?php echo get_field('bg',$post->ID)['url']; ?>);background-repeat:no-repeat;background-position:left;background-size:cover;" <?php } ?> <?php if ($bg_select=="color"){ ?>style="background:<?php echo get_field('color_bg',$post->ID); ?>;" <?php } ?>>
            <div class="row">
              <section class="breadcrumbs">
                  <ul class="list-unstyled breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                    <li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                      <a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
                        <span class="breadcrumbs__name" itemprop="name"><?php echo get_the_title(2); ?></span>
                      </a>
                      &gt;
                    </li>
                    <li class="current breadcrumbs__item">
                      <span><?php the_title(); ?></span>
                    </li>
                  </ul><!-- .breadcrumbs -->
            </section>
              <div class="col-md-7">
                <?php the_post(); the_content(); ?>
              </div>
            </div>
            <?php $image = get_field("image",$post->ID);
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
