<?php get_template_part('templates/page', 'header'); ?>


<div class="container clearfix">
  <div class="col-xs-12 clearfix">
    
    <?php if (!have_posts()) : ?>
      <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
      </div>
      <?php get_search_form(); ?>
    <?php endif; ?>
    
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/content', 'search'); ?>
    <?php endwhile; ?>

  </div>
</div>

<br />

<div class="container clearfix">
  <div class="col-xs-12 clearfix">
    <?php the_posts_navigation(); ?>
  </div>
</div>
