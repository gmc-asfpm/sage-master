<?php
/**
 * Template Name: Single Event Template
 */
?>
<?php
  session_start();
  global $wp_query;
  global $wp_query_hold;
  global $ai1ec_registry;
  
  // hold default query
  $wp_query_hold = $wp_query;
?>

<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php // get_template_part('templates/subnav'); ?>
<?php endwhile; ?>

<?php
  
  ob_start();   
  if(function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<div id="breadcrumbs">','</div>');
  }
  $existing_breadcrumbs = ob_get_clean();
  
  wp_reset_query();
  wp_reset_postdata();
  unset($wp_query);
?>

<?php
  if(isset($_GET['id']) && $_GET['id'] != "") {
    $eventId = $_GET['id'];
    $args = array(
      'p' => $eventId,
      'post_type' => 'ai1ec_event',
      'posts_per_page' => 1
    );
    $wp_query = new WP_Query($args);
    
    if($wp_query->have_posts()) {
      while($wp_query->have_posts()) {
        $wp_query->the_post(); ?>

        <?php get_template_part('templates/subnav'); ?>
        
        <div class="container clearfix">
          <div class="col-xs-12">
            <div class="single-event-wrap">
        
              <?php
                $event = new Ai1ec_Event($ai1ec_registry);
                $event->initialize_from_id( $eventId );
                
                // all fields now available
                // from table -> wp_ai1ec_events
                // $event->get('');
                
                $event_start = $event->get('start');
                $event_end = $event->get('end');
                
                //$event_start = $event->get('start')->format('Y-m-d');
                //$event_end = $event->get('end')->format('Y-m-d');
                
                $event_title = $event->get('post')->post_title;
                $event_name = $event->get('post')->post_name;
                $event_allday = $event->get('allday');
                $event_venue = $event->get('venue');
                $event_country = $event->get('country');
                $event_address = $event->get('address');
                $event_city = $event->get('city');
                $event_province = $event->get('province');
                $event_contact_name = $event->get('contact_name');
                $event_contact_phone = $event->get('contact_phone');
                $event_contact_email = $event->get('contact_email');
                $event_contact_url = $event->get('contact_url');
                $event_cost = $event->get('cost');
                $event_ticket_url = $event->get('ticket_url');
                $event_ical_feed_url = $event->get('ical_feed_url');
                $event_ical_source_url = $event->get('ical_source_url');
                $event_ical_organizer = $event->get('ical_organizer');
                $event_ical_contact = $event->get('ical_contact');
                $event_show_coordinates = $event->get('show_coordinates');
                $event_latitude = $event->get('latitude');
                $event_longitude = $event->get('longitude');
              ?>
    
              <div class="page-header">
                <?php /* <h1 class="page-title"><?php echo get_field('page_title'); ?></h1> */ ?>
                <div class="event-underline"></div>
              </div>
              
              <div class="single-event-inset">
                <div class="clearfix">
                  
                  <div class="date-box clearfix">
                    
                    <div class="date-day-text">
                      <?php echo strtoupper($event_start->format('l')); ?>
                    </div>
                    <div class="date-date-number">
                      <?php echo $event_start->format('j'); ?>
                    </div>
                    <div class="date-month-year">
                      <?php echo strtoupper($event_start->format('M')).' '.$event_start->format('Y'); ?>
                    </div>
                    
                  </div>

                  <div class="event-title">
                    <?php echo $event_title; ?>
                  </div>
                  
                </div>

                <?php
                  $img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                  if($img_url && !empty($img_url)) {
                    $img_url = $img_url[0]; ?>
                      
                    <div class="event-img">
                      <img src="<?php echo $img_url; ?>" />
                    </div>                      
                  
                  <?php
                  }
                ?>
                
                <div class="event-detail">
                  <span class="event-detail-label">Locations:</span>
                  <span class="event-detail-value"><?php echo $event_venue; ?></span>
                </div>
                
                <div class="event-detail">
                  <span class="event-detail-label">Time:</span>
                  <span class="event-detail-value"><?php echo $event_start->format('l, M j, Y, g:i A').' - '.$event_end->format('l, M j, Y, g:i A'); ?></span>
                </div>
                
                <?php
                  if(get_field('sponsor') && !empty(get_field('sponsor'))) { ?>
                		<div class="event-detail">
                      <span class="event-detail-label">Sponsor:</span>
                      <span class="event-detail-value"><?php echo get_field('sponsor'); ?></span>
                    </div>  
                  <?php  
                  } ?>
								
								<?php
									if(get_field('location_extra_info') && !empty(get_field('location_extra_info'))) { ?>
                    <div class="event-detail">
                      <span class="event-detail-label">Location:</span>
                      <div class="">
                        <?php echo get_field('location_extra_info'); ?>
                      </div>
                    </div>
                  <?php
	                } ?>
                
                <?php
									/*
                  if(the_content() && !empty(the_content())) { ?>
                    <div class="event-detail">
                      <span class="event-detail-label">Details:</span>
                      <div class="">
	                      <?php
		                      // $the_content = get_the_content();
		                      // echo $the_content;
													// echo the_content();
												?>
                      </div>
                    </div>
                  <?php
	                }
	                */
	            	?>
	            	<div class="event-detail">
                  <span class="event-detail-label">Details:</span>
                  <div class="">
                    <?php
                      $the_content = get_the_content();
                      echo $the_content;
											// echo the_content();
										?>
                  </div>
                </div>
                
                <?php
                  if(get_field('cost') && !empty(get_field('cost'))) { ?>
                    <div class="event-detail">
                      <span class="event-detail-label">Cost:</span>
                      <div class="">
                        <?php echo get_field('cost'); ?>
                      </div>
                    </div>
                  <?php
	                } ?>
                
                <?php
                  if($event_ticket_url && !empty($event_ticket_url)) { ?>
                    <div class="event-detail">
                      <span class="event-detail-label registrations">Registrations:</span>
                      <div>
                            <div>
                              <a href="<?php echo $event_ticket_url; ?>" class="registration-button" target="_blank">Click here to register online</a>
                            </div>
                            <div>
                              <a href="<?php echo $event_ticket_url; ?>" class="registration-link" target="_blank"><?php echo $event_ticket_url; ?></a>
                            </div>
                      </div>
                    </div>
                	<?php
                  } ?>
                
                <?php
                  $files = get_field('attached_files');
                  if($files && !empty($files)) { ?>
                    <div class="event-detail">
                      <div class="event-files">
                        <?php
                          foreach($files as $file) {
                            // print_r($file); ?>
                            <div class="file-attachment">
                              Attached File: <a href="<?php echo $file['attachment']['url']; ?>" target="_blank"><?php echo $file['attachment']['filename']; ?></a>
                            </div>
                          <?php
                          } ?>
                          
                      </div>
                    </div>
                  <?php
	                } ?>
                
                <br />
                
                <?php
                  if(get_field('bottom_content')) { ?>
                    <div class="event-detail">
                      <div class="">
                        <?php echo get_field('bottom_content'); ?>
                      </div>
                    </div>
                  <?php
	                } ?>
                
                <br />                
                
                <?php
                  // http://floods.local/?plugin=all-in-one-event-calendar&controller=ai1ec_exporter_controller&action=export_events&ai1ec_post_ids=289
                ?>
                <div class="event-detail">
                  <div class="">
                    <?php ?>
                    <a href="?plugin=all-in-one-event-calendar&controller=ai1ec_exporter_controller&action=export_events&ai1ec_post_ids=<?php echo $eventId; ?>" class="event-action" target="_blank"><img class="event-detail-icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/event-calendar.svg" /> Download Calendar Event</a>
                    <?php ?>
                    
                    <?php /* ?>
                    <a href="?event_ics=<?php echo $eventId; ?>" class="event-action" target="_blank"><img class="event-detail-icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/event-calendar.svg" /> Download Calendar Event</a>
                    <?php */ ?>
                    
                  </div>
                </div>
                
                <?php /*
                <div class="event-detail">
                  <div class="">
                    <a href="#" class="event-action"><img class="event-detail-icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/event-rss.svg" /> Subscribe to RSS feed</a>
                  </div>
                </div>
                */ ?>
                
                <div class="event-detail">
                  <div class="">
                    <a href="#" class="event-action" onclick="history.go(-1);"><img class="event-detail-icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/event-back.svg" /> Back to Calendar</a>
                  </div>
                </div>
                
              </div>   
                   
            </div>
          </div>
        </div>

    <?php  
      }
    }

  } else {
    // no event
  }  
?>

<?php
  // reset default query
  wp_reset_query();
  wp_reset_postdata();
  $wp_query = $wp_query_hold;
?>

<?php /*    
  <div class="container clearfix">
    <div class="col-xs-12">
      <?php get_template_part('templates/content', 'page'); ?>
    </div>
  </div>
*/ ?>
