<?php
/**
 * Template Name: Products
 */

 get_header();
 ?>

<main class="content-page">
    <div class="container">
        <div class="row">
            <?php get_sidebar(); ?>
            <div class="col-md-10 col-xs-12">
                <div class="products-page">
                    <section class="breadcrumbs">
                        <ul class="list-unstyled breadcrumbs__list" itemscope=""
                            itemtype="http://schema.org/BreadcrumbList">
                            <li class="breadcrumbs__item" itemprop="itemListElement" itemscope=""
                                itemtype="http://schema.org/ListItem">
                                <a href="<?php echo home_url(); ?>" class="breadcrumbs__link" itemscope=""
                                    itemtype="http://schema.org/Thing" itemprop="item">
                                    <span class="breadcrumbs__name"
                                        itemprop="name"><?php $home_title = get_the_title( get_option('page_on_front') ); echo $home_title; ?></span>
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
                        <div class="desc">
                            <?php the_post(); the_content(); ?>
                        </div>
                        <?php $image = get_field("image",$post->ID);

                        if($image){ ?>
                        <div class="image-products">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table__title"><?php the_excerpt(); ?></div>
                                    </div>

                                    <div class="col-md-12">
                                        <a class="popup" href="<?php echo $image['url']; ?>">
                                            <img id="popup_img1" src="<?php echo $image['url']; ?>" alt="products" />
                                        </a>

                                        <div class="zoom">
                                            <span class="zoom__text"><?php echo get_field("zoom_text", $post->ID); ?></span>
                                            <?php $zoom_search = get_field("zoom_search", $post->ID);

                                            if($zoom_search){ ?>
                                              <img class="zoom__search" src="<?php echo $zoom_search['url']; ?>" alt="search" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="table">
                            <?php $color_table_image = get_field("color_table_image",$post->ID);
                          if($color_table_image){ ?>
                            <a class="popup" href="<?php echo $color_table_image['url']; ?>">
                                <img id="popup_img" src="<?php echo $color_table_image['url']; ?>" alt="products" />
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>