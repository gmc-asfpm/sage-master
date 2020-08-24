<?php
/**
 * Template Name: Headline and Sliders Template
 */
?>
<?php
  session_start();
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
$topicArr = get_field('topics');
if($topicArr && !empty($topicArr)) {

  $count = 1;
  foreach ($topicArr as $topic) { ?>
    
    <div class="container clearfix">
      <div class="col-xs-12">
        <h2 class="search-topic-title"><?php echo $topic['title']; ?></h2>
      </div>
    </div>
    
    <div class="container clearfix">
      <div class="col-xs-12 search-slider-wrap">
        
          <?php
            $total = count($topic['slider_boxes']);
          ?>
          <ul class="bxslider bxslider-<?php if($count > 4) { echo 'min'; } else { echo 'max'; } ?>">
            <?php
              foreach ($topic['slider_boxes'] as $box) {
                
                $boxData = koha_get_data_func(
                  array(
                    'action' => 'single',
                    'id' => $box['koha_record']
                  )
                );

                if($boxData[0]['electro'] && $boxData[0]['electro'] != "") {
                  // Electronic
                  $itemUrl = $boxData[0]['electro'];
                  
                  if($boxData[0]['type'] && !empty($boxData[0]['type'])) {
                    $linkText = $boxData[0]['type'];
                  } else {
                    $linkText = "Read More";
                  }
                } else {
                  $itemUrl = 'item?id='.$boxData[0]['bibId'];
                }
                
                // $itemUrl
                if($box['external_link'] && !empty($box['external_link'])) {
                  $itemUrl = $box['external_link'];
                }
                
                // $linkText
                if($box['link_text'] && !empty($box['link_text'])) {
                  $linkText = $box['link_text'];
                }
              ?>
                        
                <li class="<?php echo $box['koha_record']; ?>">
                    
                	<a target="_blank" class="cleafix" style="display: block;" href="<?php echo $itemUrl; ?>">
                  	
                		<?php /* onclick='window.location = "<?php echo $itemUrl; ?>"' */ ?>
                    <div class="search-slider-box">

                      <?php /* onclick="window.open('<?php echo $itemUrl; ?>');" */ ?>
                      <div class="search-slider-img img-cover" style="background-image: url(<?php echo $box['image']['url']; ?>);">
                        <?php /* <img src="<?php echo $box['image']['url']; ?>" /> */ ?>
                        <?php /*
                        <a class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></a>
                        */ ?>
                        <?php
                          if($box['image']['caption'] && !empty($box['image']['caption'])) { ?>
                          	<span class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></span>
                          <?php
                          } else { ?>
                          <?php
													} ?>
                      </div>

                      <div class="slider-inset">
                        <div class="slider-title">
                          <?php
                            // echo '<a href="'.$itemUrl.'" target="_blank">'.$boxData[0]['title'].'</a>';
                          ?>
                            <?php
                              if(strlen($boxData[0]['title']) >= 48) {
                                $truncatedTitle = trim(substr($boxData[0]['title'], 0, 48))."...";
                                echo $truncatedTitle;  
                              } else {
                                echo $boxData[0]['title'];
                              }
                            ?>
                          
                          <div class="subtitle">
                            <!-- <a target="_blank" href="<?php echo $itemUrl; ?>"> -->
                              <?php
                                $subTitle = ucfirst($boxData[0]['subTitle']);
                                if(strlen($subTitle) >= 120) {
                                  $truncatedCaption = trim(substr($subTitle, 0, 120))."...";
                                  echo $truncatedCaption;  
                                } else {
                                  echo $subTitle;
                                }
                              ?>
                              <?php // echo ucfirst($boxData[0]['subTitle']); ?>
                            <!-- </a> -->
                          </div>
                        </div>
                        
                        <div class="slider-caption">
                          <?php
                          ?>
                        </div>
                        
                        <div>  
                          <?php // echo '<a href="'.$itemUrl.'" class="slider-link" target="_blank">'.$linkText.'</a>'; ?>
                          <!-- <a href="<?php echo $itemUrl; ?>" class="slider-link" target="_blank"> -->
                          <span class="slider-link">
                          	<?php echo $linkText; ?>
                          </span>
                          <!-- </a> -->
                        </div>
                      </div>
                    </div>
                    
                	</a>

                </li>   
                 
              <?php
              }
            ?>
          </ul>

      </div>
    </div>
    <?php
    $count++;
  }
}
?>

<script type="text/javascript">
  jQuery(document).ready(function() {
    
    jQuery('.bxslider-max').bxSlider({
      minSlides: 1,
      maxSlides: 4,
      slideMargin: 5,
      autoReload: true,
      infiniteLoop: false,
      hideControlOnEnd: true,
      breaks: [{screen:0, slides:1, pager:true},{screen:460, slides:2, pager:true},{screen:768, slides:2, pager:true},{screen:990, slides:4, pager:true}]
    });
    
    jQuery('.bxslider-min').bxSlider({
      minSlides: 1,
      maxSlides: 4,
      slideMargin: 5,
      autoReload: true,
      infiniteLoop: false,
      hideControlOnEnd: true,
      breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2, pager:false},{screen:768, slides:2, pager:false},{screen:990, slides:4, pager:false}]
    });
    
    
    jQuery("a.view-topics, .close-x").click(function() {
      jQuery(".search-topics-wrap").toggleClass("show-topics");
    });
    
    jQuery(".submit-form").click(function() {
      jQuery("#search-form").submit();  
    });
    
  });
</script>


<?php /*    
  <div class="container clearfix">
    <div class="col-xs-12">
      <?php get_template_part('templates/content', 'page'); ?>
    </div>
  </div>
*/ ?>

<?php endwhile; ?>