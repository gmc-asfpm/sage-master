<?php
/**
 * Template Name: Mentor Search Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
	
	<?php
		//
		$ask_a_mentor = get_field('ask_a_mentor_page');
		$ask_a_mentor_permalink = get_permalink($ask_a_mentor->ID);
		// echo "<pre>".print_r($ask_a_mentor_page->ID, true)."</pre>";
		// echo "<pre>".print_r($ask_a_mentor_permalink, true)."</pre>";
	?>
  <script type="text/javascript">
    function resetFwp() {
      FWP.reset();
    }
  </script>
  
  <div class="container clearfix">
    <div class="col-xs-12">
      <div class="clearfix mentor-facet-wrap">
      
        <div class="col-sm-9 col-xs-12 no-pad clearfix">
          
          <div class="clearfix">
            <h3>Areas of Expertise</h3>
            <?php echo facetwp_display( 'facet', 'mentor_expertise' ); ?>
            <br />
            
            <h3>Type of Mentoring</h3>
            <?php echo facetwp_display( 'facet', 'participating_activities' ); ?>
            <br />
            
            <?php /*
            <div class="clearfix" style="margin-bottom: 10px;">
              <a class="button pull-left" style="margin-right: 10px;">CHECK ALL</a>&nbsp;<a class="button pull-left">UNCHECK ALL</a>
            </div>
            */ ?>
          </div>
          
        </div>
        
        <div class="col-sm-3 col-xs-12 no-pad ">
          
          <div class="clearfix">
            <h3>Location</h3>
            <?php echo facetwp_display( 'facet', 'mentor_state' ); ?>
          </div>
          
          <div class="clearfix">
            <h3>FEMA Region</h3>
            <?php echo facetwp_display( 'facet', 'fema_region' ); ?>
          </div>
          
          <?php /*
					<div class="clearfix">
						<h3>Status</h3>
            <?php echo facetwp_display( 'facet', 'mentoring_status' ); ?>
          </div>
          */ ?>
          
        </div>
        
        <div class="col-xs-12 no-pad clearfix">
          <br />
          <div class="clearfix">
            <a class="button pull-right" onclick="resetFwp();">RESET</a>
          </div>          
        </div>
      
      </div>
    </div>
  </div>
  
  <br />
  
  <div class="container clearfix">
    <div class="col-xs-12 no-pad">
  
      <div class="facetwp-template mentor-item-wrap clearfix grid">
        <?php
          wp_reset_postdata();
          wp_reset_query();
          $args1 = array(
            'post_type'		=> 'mentors',
            "post_status" => "publish",
            "orderby" => "date",
            "order" => "DESC",
            'posts_per_page' => -1
          );
          $query1 = new WP_Query($args1);
          if( $query1->have_posts() ) : while ( $query1->have_posts() ) : $query1->the_post();
          
            $theId = get_the_id();
            $theMeta = get_post_meta($theId);
            // print_r($theMeta);
            $expArr = unserialize($theMeta['areas_of_expertise_filter'][0]);
            $activitiesArr = unserialize($theMeta['participating_activities'][0]);
            $headshot = get_field('headshot', $theId);
            $canAsk = false;
            
            foreach($activitiesArr as $item) {
              if($item == "Ask a Mentor") {
                $canAsk = true;
                break;
              }
            }
            
            if($theMeta['mentoring_status'][0] && !empty($theMeta['mentoring_status'][0]) && strtolower($theMeta['mentoring_status'][0]) == "available") { ?>
            
              <div class="mentor-item col-md-12 col-sm-12 col-xs-12 no-pad grid-item" data-mentor="<?php echo $theId; ?>">
                  
                  <?php /*
                    <div class="mentor-item-inset">                  
                      <a class="" href="<?php the_permalink(); ?>">
                        <!-- style="background-image: url(<?php echo $theMeta['wpcf-headshot'][0]; ?>);" -->
                        <?php $headshot = get_field('headshot', $theId); ?>
                        <div class="mentor-th-wrap" style="background-image: url(<?php echo $headshot['url']; ?>)">        
                          <!-- <img class="mentor-th" src="<?php echo $headshot['url']; ?>" /> -->
                        </div>
                      </a>
                      <a class="mentor-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      <div>
                        <?php
                          if($theMeta['mentoring_status'][0] && !empty($theMeta['mentoring_status'][0])) { ?>
                           	Status: <strong><?php echo ucfirst($theMeta['mentoring_status'][0]); ?></strong>  
                          <?php
                          } ?>
                        <h5><strong>Areas of Expertise</strong></h5>
                        <ul class="exp-list">
                          <?php                    
                            foreach($expArr as $item) {
                              echo "<li>".$item."</li>";
                            }
                          ?>
                        </ul>
                      </div>
                      <?php
    	                	if(current_user_is('s2member_level1')) { ?>
    		                  <div>
    			                  <a class="button" href="<?php echo $ask_a_mentor_permalink."?mentor=".$theId; ?>">Ask a Question</a>
    		                  </div>
    										<?php
    										} ?>
                    </div>
                  */ ?>
                  
                  <div class="mentor-item-inset">
                    
                    <div class="row">                    
                      <div class="col-sm-12 col-md-3">
                        <?php if($headshot['url'] && !empty($headshot['url'])) { ?>
                          <a class="" href="<?php the_permalink(); ?>">
                            <img class="mentor-th" src="<?php echo $headshot['url']; ?>" />
                          </a>
                        <?php } ?>
                        <?php
                          /*
                          if($theMeta['mentoring_status'][0] && !empty($theMeta['mentoring_status'][0])) { ?>
                           	Status: <strong><?php echo ucfirst($theMeta['mentoring_status'][0]); ?></strong>  
                          <?php
                          }
                          */
                        ?>
                        <?php
    	                	  if(current_user_is('s2member_level1')) { 
        	                	// CHECK IF MENTOR HAS THE QUESION ONE CHECKED
      	                	?>
      		                  <div>
        		                  <br />
        		                  <?php
          		                  if($canAsk) { ?>
            		                  <a class="button" href="<?php echo $ask_a_mentor_permalink."?mentor=".$theId; ?>">Ask a Question</a>  
            		                <?php  
          		                  }
        		                  ?>          		                  
      		                  </div>
      										<?php
      										}
      								  ?>
                      </div>
                      
                      <div class="col-sm-12 col-md-9">                      
                        <div>
                          <a class="mentor-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
  
                        <div class="row">
                          <?php
                            if($expArr && !empty($expArr)) { ?>
                              <div class="col-sm-12 col-md-6">
                                <h5><strong>Areas of Expertise</strong></h5>
                                <ul class="exp-list">
                                  <?php                    
                                    foreach($expArr as $item) {
                                      echo "<li>".$item."</li>";
                                    }
                                  ?>
                                </ul>
                              </div>  
                            <?php
                            }
                          ?>
                          <?php
                            if($expArr && !empty($activitiesArr)) { ?>
                              <div class="col-sm-12 col-md-6">
                                <h5><strong>Participating Activities</strong></h5>
                                <ul class="exp-list">
                                  <?php                    
                                    foreach($activitiesArr as $item) {
                                      echo "<li>".$item."</li>";
                                    }
                                  ?>
                                </ul>
                              </div>  
                            <?php
                            }
                          ?>  
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  
              </div>
              
            <?php
            } ?>
          
        <?php endwhile;
        else : ?>
        <?php
        endif;
        unset($query1);
        wp_reset_postdata();
        wp_reset_query();
        ?>
      </div>
      
    </div>
  </div>
  
  <div class="container clearfix">
    <div class="col-xs-12">
      
      <div>
        <?php
          // returns current filtered total
          // echo do_shortcode( '[facetwp counts="true"]' );
        ?>
      </div>
      
      <br />
      <div>
        <?php echo facetwp_display( 'pager' ); ?>
      </div>
    </div>
  </div>

<?php endwhile; ?>