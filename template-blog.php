<?php
/**
 * Template Name: Blog
 */
 get_header();
 ?>
 <main class="content-page">
   <div class="container">
     <div class="row">
         <?php get_sidebar(); ?>
       <div class="col-md-10  col-xs-12">
         <div class="products-page">
           <div class="content">
             <h1><?php the_title(); ?></h1>
             <?php $args = array(
                'post_type' => 'post',
              	'posts_per_page' => -1, // значение по умолчанию берётся из настроек, но вы можете использовать и собственное
                'orderby' => 'date',
								'order' => 'DESC',
              );
              query_posts( $args );
              while(have_posts()): the_post();
              ?>
              <h2><?php the_title() /* заголовок */ ?></h2>
              <p><?php the_content() /* содержимое поста */ ?></p>
              <?php
              endwhile; ?>

           </div>
         </div>
       </div>
     </div>
   </div>
 </main>
<?php get_footer(); ?>
