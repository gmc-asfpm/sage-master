<?php
/**
 * Template Name: Calendars Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php get_template_part('templates/subnav');
// $tag_filter = get_field('event_page_tag');
?>

  <div class="container clearfix">
    <div class="col-xs-12">
            
      <div class="page-header">
        <h1 class="page-title">
          <?php /* <?= Titles\title(); ?> */ ?>
          Calendars
        </h1>
      </div>
  
    </div>
  </div>
        
  <div class="container clearfix calendar-landing-wrap">
    <div class="col-xs-12">
      
        <?php
          if(get_field('calendar_category')) {
            $event_cats_objects = get_field('calendar_category');
        	  $event_cats = array();
        	  foreach($event_cats_objects as $cat) {
        	  	$event_cats[] = $cat->name;
        	  }
        	  $event_cats = implode(',',$event_cats);
        	  // print_r($event_cats);
        	  
        	  // echo do_shortcode("[ai1ec cat_name=\"" . $event_cats . "\" tag_name=\"$tag_filter->name\"]")
        	  // echo do_shortcode('[ai1ec view="monthly" cat_name="'.$event_cats.'"]');
        	  echo do_shortcode('[ai1ec cat_name="'.$event_cats.'"]');
        	  // echo do_shortcode('[ai1ec]');
          } else {
            echo do_shortcode('[ai1ec]');
          }
        ?>
    	
    </div>
  </div>

<?php endwhile; ?>