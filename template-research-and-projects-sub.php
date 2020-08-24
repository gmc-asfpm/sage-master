<?php
/**
 * Template Name: Research and Projects Sub-Landing Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php get_template_part('templates/subnav'); ?>
  
  <?php
    $headline_post = get_field('headline_post');
    // print_r($headline_post);
  ?>
      
  <div class="container rp-landing-headline clearfix">
    <div class="col-md-7">
      <div class="rp-landing-headline-left">
        <div class="title">
          <?php echo $headline_post->post_title; ?>
        </div>
        
        <div class="excerpt clearfix">
          <div class="col-md-12 no-pad">
            <?php 
	            // echo $headline_post->post_excerpt;
	            // echo "<pre>".print_r($headline_post, true)."</pre>";
							echo $headline_post->post_content;
	          ?>
          </div>
        </div>
        
        <?php
          if(get_field('include_read_more_link')) { ?>
            <div class="more">
              <a class="button" href="<?php echo $headline_post->guid; ?>">
                Read More
              </a>
            </div>
          <?php  
          } ?>
      </div>
      
    </div>
    <div class="col-md-5">
    <?php
      $headline_image = get_field('headline_image');
    ?>
      <?php /*
      <div class="rp-landing-headline-right img-cover" style="background-image: url(<?php echo $headline_image['url']; ?>);">
      */ ?>
      <div class="rp-landing-headline-right text-center img-cover">
        <img class="max-width" src="<?php echo $headline_image['url']; ?>" />
        <?php
          if($headline_image['caption'] && !empty($headline_image['caption'])) { ?>
            <span class="img-caption-1 rp-landing-headline-right-caption"><?php echo $headline_image['caption']; ?></span>
            <!-- <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" class="img-attr" /></a> -->
          <?php
          } ?>
      </div>
    </div>
  
    <div class="col-xs-12">
      <hr />
    </div>  
  </div>
  
  
  
  <?php
  // print_r(get_field('slider_boxes'));
  $sliderArr = get_field('slider_boxes');
  
  if($sliderArr && !empty($sliderArr)) {
    $total = count($sliderArr); ?>
  
	  <div class="container clearfix">
	    <div class="col-xs-12 search-slider-wrap"> 
	
	          <ul class="bxslider">
	            <?php
	              foreach ($sliderArr as $box) {
	                
	                if($box['external_link'] && !empty($box['external_link'])) {
	                  $boxLink = $box['external_link'];
	                } elseif($box['page_link'] && !empty($box['page_link'])) {
	                  $boxLink = $box['page_link'];
	                } else {
	                  $boxLink = false;
	                }
	              ?>
	                <li class="">
	
	                  <div class="slider-box" style="background-image: url(<?php echo $box['image']['url']; ?>);">
	                    
	                    <div class="slider-overlay">
	                      <a class="inset-link" href="<?php echo $boxLink; ?>"></a>
	                    </div>
	                    
	                    <div class="slider-inset">
	                      <?php
	                        if($box['image']['caption'] && !empty($box['image']['caption'])) { ?>
	                          <a class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></a>
	                        <?php
	                        }
	                      ?>                      
	                      
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
		                      if($box['caption'] && !empty($box['caption']) && strlen($box['caption']) > 1) { ?>
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
	                      
	                      <?php
	                      if($boxLink) { ?>
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
												<?php
												} ?>
												
	                    </div>
	                    
	                  </div>
	
	                </li>    
	              <?php
	              }
	            ?>
	          </ul>
	          
	          <?php
	          if($total > 4) {
	            // USE BXSLIDER ?>
	            <script type="text/javascript">
	              jQuery(document).ready(function() {
	                jQuery('.bxslider').bxSlider({
	                  minSlides: 1,
	                  maxSlides: 3,
	                  slideMargin: 5,
	                  autoReload: true,
	                  breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2},{screen:768, slides:2},{screen:990, slides:3, pager:true}]
	                });
	              });
	            </script>
	          <?php  
	          } else {
	            // NO BXSLIDER CONTROLS ?>
	            <script type="text/javascript">
	              jQuery(document).ready(function() {
	                jQuery('.bxslider').bxSlider({
	                  minSlides: 1,
	                  maxSlides: 3,
	                  slideMargin: 5,
	                  autoReload: true,
	                  breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2, pager:false},{screen:768, slides:2, pager:false},{screen:990, slides:3, pager:false}]
	                });
	                jQuery('bx-controls-direction').hide();
	              });
	            </script>
	          <?php  
	          } ?>  
	          
	    </div>
	  </div>

	<?php
	}	?>
  
  <div class="container clearfix">
    <div class="col-xs-12 rp-landing-resources">
      
      <?php
        // print_r(get_field('resource_boxes'));
        $resourceArr = get_field('resource_boxes');
        
        foreach ($resourceArr as $box) { ?>
          
          <a href="<?php echo $box['page_link']; ?>">
	          
	          <div class="col-md-6 resource-box">
	            <div class="col-md-2 no-pad text-center resource-box-left">
	              <div class="resource-icon">
		              <a href="<?php echo $box['page_link']; ?>">
	                	<img src="<?php echo $box['icon']['url']; ?>" />
		              </a>
	              </div>
	            </div>
	            
	            <div class="col-md-10 resource-box-right">
		            <a href="<?php echo $box['page_link']; ?>">
		              <div class="resource-title">
		                <?php echo $box['title']; ?>
		              </div>
	              </a>
	              <?php
		              if($box['caption'] && !empty($box['caption'])) { ?>
			              <div class="resource-caption">
			                <?php echo $box['caption']; ?>
			              </div>
			            <?php
				          } ?>
	              <?php
		              if($box['page_link'] && !empty($box['page_link'])) { ?>
			              <div class="resource-link">
			                <a href="<?php echo $box['page_link']; ?>">Read More</a>
			              </div>
			            <?php
				          } ?>
	            </div>
	          </div>
          
          </a>
          
        <?php
        }
      ?>
      
    </div>
  </div>
      

<?php /*    
  <div class="container clearfix">
    <div class="col-xs-12">
      <?php get_template_part('templates/content', 'page'); ?>
    </div>
  </div>
*/ ?>
  
<?php endwhile; ?>