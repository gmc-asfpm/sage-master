<?php
/**
 * Template Name: Workshop Landing Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php get_template_part('templates/subnav');
$tag_filter = get_field('event_page_tag');
 ?>
        
  <div class="container clearfix workshop-top">
    <div class="col-md-6 featured-workshop-wrap">
      
    	<?php
        $featured_workshop = get_field('featured_event'); 
    	?>
    	<div class="featured-workshop-background cover-img" onclick="window.location = 'detail?id=<?php echo $featured_workshop->ID; ?>'" style="background-image: url('<?php echo get_the_post_thumbnail_url($featured_workshop);?>')">
	    	<div class="featured-workshop-background-inset">
		    	<div class="featured-name">Featured <?php echo $tag_filter->name;?></div>
	        <div class="featured-title">
	          <?php echo $featured_workshop->post_title; ?>
	        </div>
	        <div class="featured-blurb"><?php echo get_field('featured_blurb', $featured_workshop->ID);?></div>
	        <div>
	          <?php 
	            // echo get_permalink($featured_workshop->ID);
	          ?>
	          <a class="button" href="detail?id=<?php echo $featured_workshop->ID; ?>">READ MORE</a>
	        </div>
	    	</div>
    	</div>
    </div>
    
    <div class="col-md-6">
    	<div class="cal_adgenda_landing_box">
    		<h2><?php echo $tag_filter->name;?> Calendar</h2>
        <?php
          $event_cats_objects = get_field('workshop_calendar_category');
      	  $event_cats = array();
      	  foreach($event_cats_objects as $cat) {
      	  	$event_cats[] = $cat->name;
      	  }
      	  $event_cats = implode(',',$event_cats);
      	  // echo $event_cats;
      	  echo do_shortcode("[ai1ec view=\"agenda\" cat_name=\"" . $event_cats . "\" tag_name=\"$tag_filter->name\"]")
        ?>
    		<a class="button" href="<?php echo get_field('view_more_link');?>">VIEW ALL</a>
    	</div>
    	
    </div>
  </div>
  

<?php
  if( have_rows('workshops_lists') ) : ?>
    <div class="container clearfix">
      <div class="col-xs-12">
        
        <div class="workshop-topics-wrap clearfix">
          
          <h2>Workshops</h2>
          
          <div class="inset clearfix">
            
        		<?php
              // loop through the rows of data
        	    while ( have_rows('workshops_lists') ) : the_row(); ?>
        			<div class="col-md-3 col-sm-6">
        				<h4><?php the_sub_field('list_title'); ?></h4>
        				<ul>
        			<?php
        			  while ( have_rows('list') ) : the_row(); ?>
                  <li>
                    <?php
                      $event_item = get_sub_field('item');
                      // echo "<a href='details/?" . $event_item->post_name . "'>" . $event_item->post_title . "</a>";
                      echo "<a href='detail/?id=" . $event_item->ID . "'>" . $event_item->post_title . "</a>";
        				    ?>
        				  </li>
              <?php	
        		    endwhile; ?>
        				</ul>
        			</div>
        		<?php	
        	    endwhile; ?>
        	    
          </div>

        </div>
        
    	</div>
    </div>  
	<?php
	endif; ?>
 
<?php
  if( have_rows('sliders') ) :
    // loop through the rows of data
    while ( have_rows('sliders') ) : the_row(); ?>
			
			<?php if(get_sub_field('topic_title')) { ?>
        <div class="container clearfix">
          <div class="col-xs-12 search-topic-title">
            <h2 class="search-topic-title"><?php echo get_sub_field('topic_title'); ?></h2>
          </div>
  			</div>
  		<?php	
			} ?>
			
			
  <div class="container clearfix">
    <div class="col-xs-12 search-slider-wrap">
      
      <?php
        $sliderArr = get_sub_field('slider_boxes');
        $total = count($sliderArr); ?>
        
        <ul class="bxslider <?php echo ($total > 4) ? 'bxslider_maxslide' : 'bxslider_minslide'; ?>">
          <?php
            foreach ($sliderArr as $box) {
              
              if($box['external_link'] && !empty($box['external_link'])) {
                $boxLink = $box['external_link'];
              } elseif($box['page_link'] && !empty($box['page_link'])) {
                $boxLink = $box['page_link'];
              } else {
                $boxLink = "#";
              }                
            ?>
            
              <li class="">
              
                <div class="slider-box" style="background-image: url(<?php echo $box['image']['url']; ?>);">      
                    <div class="slider-overlay">
                      <a class="inset-link" href="<?php echo $boxLink; ?>"></a>
                    </div>
                  
                  <div class="slider-inset">
                    <a class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></a>
                    
                    <div class="slider-title">
                      <a title="<?php echo $box['title']; ?>" class="" href="<?php echo $boxLink; ?>">
                        <?php
                          if(strlen($box['title']) >= 44) {
                            $truncatedTitle = trim(substr($box['title'], 0, 44))."...";
                            echo $truncatedTitle;  
                          } else {
                            echo $box['title'];
                          }
                        ?>
                      </a>
                    </div>
                    
                    <?php 
                      if($box['caption'] && (strlen($box['caption']) > 1)) { ?>
	                      <div class="slider-caption">
	                        <a class="" href="<?php echo $boxLink; ?>">
	                          <?php 
	                            if(strlen($box['caption']) >= 100) {
	                              $truncatedCaption = trim(substr($box['caption'], 0, 100))."...";
	                              echo $truncatedCaption;  
	                            } else {
	                              echo $box['caption'];
	                            }
	                          ?>
	                        </a>
	                      </div>
	                    <?php
		                  } ?>
                    
                    <div>
                      <a class="slider-link" href="<?php echo $boxLink; ?>">
                        <?php
                          if($box['link_text'] && !empty($box['link_text'])) {
                            echo $box['link_text'];  
                          } else { ?>
                            Read More
                          <?php  
                          }
                        ?>
                      </a>
                    </div>
                  </div>
                </div>
                
              </li>    
            <?php
            }
          ?>
        </ul>
        
	  </div>
  </div>
  
  <?php	
    endwhile; ?>
    
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('.bxslider_maxslide').bxSlider({
        slideMargin: 5,
        autoReload: true,
        infiniteLoop: false,
        hideControlOnEnd: true,
        breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2, pager:true},{screen:768, slides:2, pager:true},{screen:990, slides:4, pager:true}]
      });
    });
  </script>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('.bxslider_minslide').bxSlider({
        slideMargin: 5,
        autoReload: true,
        infiniteLoop: false,
        hideControlOnEnd: true,
        breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2, pager:true},{screen:768, slides:2, pager:true},{screen:990, slides:4, pager:false}]
      });
    });
  </script>
  	
<?php
	endif; ?>
	
<?php
  endwhile; ?>