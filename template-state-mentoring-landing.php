<?php
/**
 * Template Name: State Mentoring Landing Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php get_template_part('templates/subnav'); ?>
  
  <?php
  ?>
  
  <br />
  <br />
  
  <div class="container clearfix">
    <div class="col-xs-12 search-slider-wrap"> 
      
      <?php
        // print_r(get_field('slider_boxes'));
        $sliderArr = get_field('slider_boxes');
        $total = count($sliderArr);        
        ?>
        
        <ul class="bxslider">
          <?php
            foreach ($sliderArr as $box) { ?>
              <li class="">
                <div class="slider-box" style="background-image: url(<?php echo $box['image']['url']; ?>);">
                  <!--
                  <div class="slider-overlay">
                  </div>
                  -->
                  <div class="slider-inset">
                    <!--
                    <a class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></a>
                    -->
                    <div class="slider-title">
                      <?php echo $box['title']; ?>
                    </div>
                    <?php
	                    if($box['caption'] && !empty($box['caption'])) { ?>
		                    <div class="slider-caption">
		                      <?php echo $box['caption']; ?>
		                    </div>
		                  <?php
			                } ?>
			              <?php
				              if($box['page_link'] && !empty($box['page_link'])) { ?>
		                    <div>
		                      <a class="slider-link" href="<?php echo $box['page_link']; ?>">Read More</a>
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
                maxSlides: 4,
                slideMargin: 5,
                autoReload: true,
                breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2},{screen:768, slides:2},{screen:990, slides:4, pager:true}]
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
                maxSlides: 4,
                slideMargin: 5,
                autoReload: true,
                breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2, pager:false},{screen:768, slides:2, pager:false},{screen:990, slides:4, pager:false}]
              });
              jQuery('bx-controls-direction').hide();
            });
          </script>
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