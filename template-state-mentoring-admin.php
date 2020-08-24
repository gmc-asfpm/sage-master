<?php
session_start();
/**
 * Template Name: State Mentoring Admin Template
 */
?>
<?php
  // MESSAGE
  $message = false;
  // GET REQUEST MENTORING FORM ID
  $form_options = get_option('state_mentoring_option_name');
  $request_mentoring_form_id = $form_options['request_mentoring_form_id'];
  // ACCESS DATABASE
  global $wpdb;
  // HANDLE FORM POST
  if(isset($_POST) && !empty($_POST)) {
    // echo "<pre>".print_r($_POST, true)."</pre>";
    // UPDATE FORM WITH MATCHED/REMATCHED/UNMATCHED MENTOR
    if(
      isset($_POST['match_mentor']) &&
      !empty($_POST['match_mentor']) &&
      isset($_POST['request_form_id']) &&
      !empty($_POST['request_form_id']) &&
      isset($_POST['mentor_post_id']) &&
      !empty($_POST['mentor_post_id'])
      ) {
      if($_POST['mentor_post_id'] == "unmatch_mentor") {
        // DELETE ASSOCIATED ROW
        $result = $wpdb->delete("{$wpdb->prefix}rg_lead_detail", array(
            'lead_id'       => (int) $_POST['request_form_id'],
            'field_number'  => 31,
            'form_id'       => $request_mentoring_form_id
        ));
        // echo "<pre>".print_r($result, true)."</pre>";
      } else {
        $mentor_post_id = $_POST['mentor_post_id'];
        // UPDATE FORM LEAD DATABASE ENTRY
        $result = $wpdb->insert("{$wpdb->prefix}rg_lead_detail", array(
            'value'         => $mentor_post_id,
            'field_number'  => 31,
            'lead_id'       => (int) $_POST['request_form_id'],
            'form_id'       => $request_mentoring_form_id
        ));    
        // echo "<pre>".print_r($result, true)."</pre>";
        
        // SEND EMAIL TO MENTOR AND MENTEE
        // GET MENTOR FROM MENTOR POST AUTHOR
        $mentorPost = get_post($mentor_post_id);
        $mentorUserData = get_userdata($mentorPost->post_author);
        // $mentorUserData->first_name;
        // $mentorUserData->last_name;
        // $mentorUserData->user_email;
        
        // GET MENTEE FROM REQUEST FOR CREATED BY
        $requestEntry = GFAPI::get_entry($_POST['request_form_id']);
        $menteeUserData = get_userdata($requestEntry['created_by']);
        // $menteeUserData->first_name;
        // $menteeUserData->last_name;
        // $menteeUserData->user_email;
        
        // MENTOR EMAIL        
        $mentorEmailBody = "";
        $mentorEmailBody .= "Mentor: Line 1 \n\r";
        $mentorEmailBody .= "Line 2 \n\r";
        
        // MENTEE EMAIL        
        $menteeEmailBody = "";
        $menteeEmailBody .= "Mentee: Line 1 \n\r";
        $menteeEmailBody .= "Line 2 \n\r";
        
        // SEND MENTOR EMAIL
        // wp_mail($mentorUserData->user_email, "New Mentoring Connection", $mentorEmailBody);
        wp_mail('anderson@earthlinginteractive.com', "New Mentoring Connection", $mentorEmailBody);
        // SEND MENTOR EMAIL
        // wp_mail($mentorUserData->user_email, "New Mentoring Connection", $menteeEmailBody);
        wp_mail('anderson@earthlinginteractive.com', "New Mentoring Connection", $menteeEmailBody);
      }
    }
    // ACTIVATE MENTOR
    if(
      isset($_POST['activate_mentor']) &&
      !empty($_POST['activate_mentor']) &&
      isset($_POST['mentor_post_id']) &&
      !empty($_POST['mentor_post_id'])
      ) {
      $updateArr = array(
        'ID'           => $_POST['mentor_post_id'],
        'post_status'  => 'publish'
      );
      wp_update_post($updateArr);
    }
    // DEACTIVATE MENTOR
    if(
      isset($_POST['deactivate_mentor']) &&
      !empty($_POST['deactivate_mentor']) &&
      isset($_POST['mentor_post_id']) &&
      !empty($_POST['mentor_post_id'])
      ) {
      // CHECK IF MENTOR IS CURRENTLY ATTACHED TO A REQUEST FORM
      $mentorInUse = false;
      $entries = GFAPI::get_entries($request_mentoring_form_id);
      if($entries && !empty($entries)) {
        foreach($entries as $entry) {
          if($entry['31'] == $_POST['mentor_post_id']) {
            $mentorInUse = true;
          }
        }
      }
      if(!$mentorInUse) {
        $updateArr = array(
          'ID'           => $_POST['mentor_post_id'],
          'post_status'  => 'draft'
        );
        wp_update_post($updateArr);  
      } else {
        $message = "<strong>Error:</strong> Mentor is currently attached to a mentoring request.";
      }
    }
  }
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
  <div class="container clearfix">
    <div class="col-xs-12">
      <div>
        <?php  
        if( current_user_is('s2member_level1') ) {
          echo "You are a Mentee";
        }
        if( current_user_is('s2member_level2') ) {
          echo "You are a Mentor";
        }
        if( current_user_is( 's2member_level1', 's2member_level2', 's2member_level3') ) {
          // echo "This is both";
        }
        
        // ADMIN ####################
        if( current_user_is('s2member_level3') ||  current_user_is('administrator')  ) {
          if($message) { ?>
            <div class="well" style="background-color: #F9F6A7; border: 1px solid #e7d605;">
              <p style="margin: 0;"><?php echo $message; ?></p>  
            </div>
            <br />
          <?php
          }
          // GET ALL SUBMITED MENTORING REQUESTS
  	      // $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
          // $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
          $sorting = array( 'key' => 'created_by', 'direction' => 'DESC');            
          $entries = GFAPI::get_entries($request_mentoring_form_id, array(), $sorting);
  	      if($entries && !empty($entries)) { ?>
  	        <div class="clearfix">
      	      <h3 class="pull-left" id="unmatched-mentoring-request"><strong>Unmatched</strong> Mentoring Request Submissions</h3>
      	      <div class="pull-right">
        	      <a class="button" target="_blank" href="/products/state-mentoring-program/mentor-search/">Go to the Mentor Finder</a>
      	      </div>
  	        </div>
    	      <hr />
            <?php
    	        foreach($entries as $entry) {
      	        $user_info = get_userdata($entry['created_by']);
      	        if(
                  $user_info &&
                  !empty($user_info) &&
                  empty(trim($entry['31']))
                ) { ?>
      	        
      	          <form class="unmatched-request-item" method="post">
        	          <input type="hidden" name="match_mentor" value="true" />
        	          <input type="hidden" name="request_form_id" value="<?php echo $entry['id'] ?>" />
        	          <?php 
          	          // echo "<pre>".print_r($entry, true)."</pre>";
          	          // echo "<pre>Submission ID: ".print_r($entry['id'], true)."</pre>";
          	          // echo "<pre>Created By: ".print_r($entry['created_by'], true)."</pre>";
          	          // echo "<pre>User Email: ".print_r($entry['10'], true)."</pre>";
          	          // echo "<pre>".print_r($user_info, true)."</pre>";
                      // echo "<pre>".print_r($user_info->first_name." ".$user_info->first_name, true)."</pre>";
        	            // echo "<pre>Submission ID: ".print_r($entry['id'], true)."</pre>";
        	          ?>
                    <?php
                      // $user_info->ID
                      $mentee_query = array(
                        'posts_per_page' => '1',
                        'author' => $user_info->ID,
                        'post_type' => array('mentees'),
                        'post_status' => 'any'
                      );
                      $mentee_posts = new WP_Query($mentee_query);
                      $menteePostId = "";
                      while($mentee_posts->have_posts()) : $mentee_posts->the_post(); 
                        $menteePostId = get_the_id();
                        break;
                      endwhile;
                    ?>
        	            <div class="row">
          	            <div class="col-sm-12 col-md-5">
            	            <h5><strong>Mentee Name:</strong> <?php echo $user_info->first_name." ".$user_info->last_name; ?></h5>
            	            <h5><strong>Mentee Email:</strong> <a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo $user_info->user_email; ?></a></h5>
            	            <?php
              	            if($menteePostId && !empty($menteePostId)) { ?>
                              <h5><strong>State:</strong> <?php echo get_field('address_state', $menteePostId); ?></h5>
                            <?php
              	            } ?>
            	            <h5><strong>Application Name:</strong> <?php echo $entry['1']; ?></h5>
            	            <h5><strong>Date Submitted:</strong> <?php echo date("F j, Y", strtotime($entry['date_created'])); ?></h5>
            	            <br />
          	            </div>
          	            <div class="col-sm-12 col-md-7">
                          <h5><strong>Type of Mentoring:</strong> <?php echo $entry['2']; ?></h5>
                          <?php
                            if($entry['19'] && !empty($entry['19'])) { ?>
                  	          <h5><strong>Topic:</strong> <?php echo $entry['19']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['20'] && !empty($entry['20'])) { ?>
                  	          <h5><strong>Size of Group:</strong> <?php echo $entry['20']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['21'] && !empty($entry['21'])) { ?>
                  	          <h5><strong>When:</strong> <?php echo $entry['21']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['22'] && !empty($entry['22'])) { ?>
                  	          <h5><strong>Method:</strong> <?php echo $entry['22']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['24'] && !empty($entry['24'])) { ?>
                  	          <h5><strong>Preferred Mentor:</strong> <?php echo $entry['24']; ?></h5>
                  	          <?php
                	          } else { ?>
                  	          <h5><strong>Preferred Mentor:</strong> No preferred mentor</h5>
                            <?php
                	          }
                          ?>
                          <?php
                            unset($mentorPost);
                            if($entry['31'] && !empty($entry['31'])) {
                              $mentorPost = get_post($entry['31']);
                              $mentorUserData = get_userdata($mentorPost->post_author); ?>
                  	          <h5><strong>Matched Mentor:</strong> <?php echo $mentorUserData->first_name." ".$mentorUserData->last_name; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <br />
          	            </div>
          	            <div class="col-sm-12">
            	            <p><strong><a class="show-mentors"><span class="show-text">Show</span><span class="hide-text">Hide</span> Mentors</a></strong></p>
            	            <div class="available-mentors clearfix">
              	            <?php /*
              	            <select name="mentor_post_id">
                	            <option value="">Select Mentor</option>
                	            <?php
                                wp_reset_postdata();
                                wp_reset_query();
                                $args1 = array(
                                  'post_type'		=> 'mentors',
                                  "post_status" => "publish",
                                  "orderby" => "date",
                                  "order" => "DESC",
                                  'posts_per_page' => -1,
                                  'meta_key' => 'mentoring_status',
                                  'meta_value' => 'available'
                                );
                                $query1 = new WP_Query($args1);
                                if( $query1->have_posts() ) : while ( $query1->have_posts() ) : $query1->the_post();
                                    $theId = get_the_id();
                                    $mentorUserId = get_the_author_meta('ID');
                                    $theMeta = get_post_meta($theId);
                                    // echo "<pre>".print_r($theMeta)."</pre>";
                                    $user_info = get_userdata($mentorUserId);
                                    // get_field('ACF_FIELD', $theId); ?>
                                    <option value="<?php echo $theId; ?>"><?php echo $user_info->first_name." ".$user_info->last_name; ?></option>
                              <?php endwhile;
                              else : ?>
                              <?php
                              endif;
                              unset($query1);
                              wp_reset_postdata();
                              wp_reset_query();
                              ?>
              	            </select>
              	            &nbsp;
              	            */ ?>
                              <div class="clearfix">
                  	            <?php
                                  wp_reset_postdata();
                                  wp_reset_query();
                                  $args1 = array(
                                    'post_type'		=> 'mentors',
                                    "post_status" => "publish",
                                    "orderby" => "date",
                                    "order" => "DESC",
                                    'posts_per_page' => -1,
                                    'meta_key' => 'mentoring_status',
                                    'meta_value' => 'available'
                                  );
                                  $query1 = new WP_Query($args1); ?>
                                  
                                  <!-- One-on-One Mentoring -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>One-on-One Mentoring</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("One-on-One Mentoring", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
                                  
                                  <!-- Group Mentoring -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>Group Mentoring</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("Group Mentoring", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
                                  
                                  <!-- Situational Mentoring -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>Situational Mentoring</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("Situational Mentoring", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
                                  
                                  <!-- Ask a Mentor -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>Ask a Mentor</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("Ask a Mentor", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
 
                                <?php
                                unset($query1);
                                wp_reset_postdata();
                                wp_reset_query();
                                ?>
                              </div>
                              
                              <div class="clearfix">
                                <br />
                                <input type="submit" value="Submit" />
                              </div>
                              
            	            </div>
          	            </div>
        	            </div>
        	            <?php
          	            /*
            	          if($entry['1'] && !empty($entry['1'])) {
              	          // APPLICATION NAME
              	          echo $entry['1'];
            	          }
            	          if($entry['2'] && !empty($entry['2'])) {
              	          // TYPE OF MENTORING REQUESTED
              	          echo $entry['2'];
            	          }
            	          if($entry['19'] && !empty($entry['19'])) {
              	          // TOPIC/ISSUE
              	          echo $entry['19'];
            	          }
            	          if($entry['20'] && !empty($entry['20'])) {
              	          // SIZE OF GROUP
              	          echo $entry['20'];
            	          }
            	          if($entry['21'] && !empty($entry['21'])) {
              	          // WHEN
              	          echo $entry['21'];
            	          }
            	          if($entry['22'] && !empty($entry['22'])) {
              	          // METHOD
              	          echo $entry['22'];
            	          }
            	          if($entry['24'] && !empty($entry['24'])) {
              	          // PREFERRED MENTOR
              	          echo $entry['24'];
            	          }
            	          if($entry['31'] && !empty($entry['31'])) {
              	          // MATCHED MENTOR
              	          echo $entry['31'];
            	          }
            	          */
                      ?>
      	          </form>
                <?php           
                }
    	        } 
  	      } ?>
  	      
  	      <br />
  	      
  	      <?php
  	      if($entries && !empty($entries)) { ?>
    	      <h3><strong>Matched</strong> Mentoring Request Submissions</h3>
    	      <hr />
            <?php
    	        foreach($entries as $entry) {
      	        $user_info = get_userdata($entry['created_by']);
      	        if(
                  $user_info &&
                  !empty($user_info) &&
                  !empty(trim($entry['31']))
                ) { ?>
      	          <form class="matched-request-item" method="post">
        	          <input type="hidden" name="match_mentor" value="true" />
        	          <input type="hidden" name="request_form_id" value="<?php echo $entry['id'] ?>" />
        	          <?php 
          	          // echo "<pre>".print_r($entry, true)."</pre>";
          	          // echo "<pre>Submission ID: ".print_r($entry['id'], true)."</pre>";
          	          // echo "<pre>Created By: ".print_r($entry['created_by'], true)."</pre>";
          	          // echo "<pre>User Email: ".print_r($entry['10'], true)."</pre>";
          	          // echo "<pre>".print_r($user_info, true)."</pre>";
                      // echo "<pre>".print_r($user_info->first_name." ".$user_info->first_name, true)."</pre>";
        	            // echo "<pre>Submission ID: ".print_r($entry['id'], true)."</pre>";
        	          ?>
        	          <?php
                      // $user_info->ID
                      $mentee_query = array(
                        'posts_per_page' => '1',
                        'author' => $user_info->ID,
                        'post_type' => array('mentees'),
                        'post_status' => 'any'
                      );
                      $mentee_posts = new WP_Query($mentee_query);
                      $menteePostId = "";
                      while($mentee_posts->have_posts()) : $mentee_posts->the_post(); 
                        $menteePostId = get_the_id();
                        break;
                      endwhile;
                    ?>
        	            <div class="row">
          	            <div class="col-sm-12 col-md-5">
            	            <h5><strong>Mentee Name:</strong> <?php echo $user_info->first_name." ".$user_info->last_name; ?></h5>
            	            <h5><strong>Mentee Email:</strong> <a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo $user_info->user_email; ?></a></h5>
            	            <?php
              	            if($menteePostId && !empty($menteePostId)) { ?>
                              <h5><strong>State:</strong> <?php echo get_field('address_state', $menteePostId); ?></h5>
                            <?php
              	            } ?>
            	            <h5><strong>Application Name:</strong> <?php echo $entry['1']; ?></h5>
            	            <h5><strong>Date Submitted:</strong> <?php echo date("F j, Y", strtotime($entry['date_created'])); ?></h5>
            	            <br />
          	            </div>
          	            <div class="col-sm-12 col-md-7">
                          <h5><strong>Type of Mentoring:</strong> <?php echo $entry['2']; ?></h5>
                          <?php
                            if($entry['19'] && !empty($entry['19'])) { ?>
                  	          <h5><strong>Topic:</strong> <?php echo $entry['19']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['20'] && !empty($entry['20'])) { ?>
                  	          <h5><strong>Size of Group:</strong> <?php echo $entry['20']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['21'] && !empty($entry['21'])) { ?>
                  	          <h5><strong>When:</strong> <?php echo $entry['21']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['22'] && !empty($entry['22'])) { ?>
                  	          <h5><strong>Method:</strong> <?php echo $entry['22']; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['24'] && !empty($entry['24'])) { ?>
                  	          <h5><strong>Preferred Mentor:</strong> <?php echo $entry['24']; ?></h5>
                  	          <?php
                	          } else { ?>
                  	          <h5><strong>Preferred Mentor:</strong> No preferred mentor</h5>
                            <?php
                	          }
                          ?>
                          <?php
                            unset($mentorPost);
                            if($entry['31'] && !empty($entry['31'])) {
                              $mentorPost = get_post($entry['31']);
                              $mentorUserData = get_userdata($mentorPost->post_author); ?>
                  	          <h5><strong>Matched Mentor:</strong> <?php echo $mentorUserData->first_name." ".$mentorUserData->last_name; ?></h5>
                  	          <?php
                	          }
                          ?>
                          <br />
          	            </div>
          	            <div class="col-sm-12">
            	            <p><strong><a class="show-mentors"><span class="show-text">Show</span><span class="hide-text">Hide</span> Mentors</a></strong></p>
            	            <div class="available-mentors clearfix">
              	            <label class="mentor-button-label">
                              <input name="mentor_post_id" type="radio" class="mentor-button" value="unmatch_mentor">
                              Unmatch Mentor
                            </label>
                            <br />        
              	            <?php /*
              	            <select name="mentor_post_id">
                	            <option value="">Select Mentor</option>
                	            <?php
                                wp_reset_postdata();
                                wp_reset_query();
                                $args1 = array(
                                  'post_type'		=> 'mentors',
                                  "post_status" => "publish",
                                  "orderby" => "date",
                                  "order" => "DESC",
                                  'posts_per_page' => -1,
                                  'meta_key' => 'mentoring_status',
                                  'meta_value' => 'available'
                                );
                                $query1 = new WP_Query($args1);
                                if( $query1->have_posts() ) : while ( $query1->have_posts() ) : $query1->the_post();
                                    $theId = get_the_id();
                                    $mentorUserId = get_the_author_meta('ID');
                                    $theMeta = get_post_meta($theId);
                                    // echo "<pre>".print_r($theMeta)."</pre>";
                                    $user_info = get_userdata($mentorUserId);
                                    // get_field('ACF_FIELD', $theId); ?>
                                    <option value="<?php echo $theId; ?>"><?php echo $user_info->first_name." ".$user_info->last_name; ?></option>
                              <?php endwhile;
                              else : ?>
                              <?php
                              endif;
                              unset($query1);
                              wp_reset_postdata();
                              wp_reset_query();
                              ?>
              	            </select>
              	            &nbsp;
              	            */ ?>
                              <div class="clearfix">
                  	            <?php
                                  wp_reset_postdata();
                                  wp_reset_query();
                                  $args1 = array(
                                    'post_type'		=> 'mentors',
                                    "post_status" => "publish",
                                    "orderby" => "date",
                                    "order" => "DESC",
                                    'posts_per_page' => -1,
                                    'meta_key' => 'mentoring_status',
                                    'meta_value' => 'available'
                                  );
                                  $query1 = new WP_Query($args1); ?>
                                  
                                  <!-- One-on-One Mentoring -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>One-on-One Mentoring</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("One-on-One Mentoring", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
                                  
                                  <!-- Group Mentoring -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>Group Mentoring</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("Group Mentoring", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
                                  
                                  <!-- Situational Mentoring -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>Situational Mentoring</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("Situational Mentoring", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>, <?php echo get_field('address_state', $theId); ?> (<?php echo trim(str_replace('Region', '', get_field('fema_region', $theId))); ?>)
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
                                  
                                  <!-- Ask a Mentor -->
                                  <ul class="mentoring-type-list">
                                    <li class="title">
                                      <strong>Ask a Mentor</strong>
                                    </li>
                                    <?php
                                    if($query1->have_posts()) : while ($query1->have_posts()) : $query1->the_post();
                                        $theId = get_the_id();
                                        $mentorUserId = get_the_author_meta('ID');
                                        $theMeta = get_post_meta($theId);
                                        $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                                        $user_info = get_userdata($mentorUserId);
                                        // echo "<pre>".print_r($activitiesArr, true)."</pre>";
                                        if(in_array("Ask a Mentor", $activitiesArr)) { ?>
                                          <li>
                                            <label class="mentor-button-label">
                                              <input name="mentor_post_id" type="radio" class="mentor-button" value="<?php echo $theId; ?>">
                                              <?php echo $user_info->first_name." ".$user_info->last_name; ?>
                                            </label>
                                          </li>
                                        <?php
                                        } else { } ?>
                                    <?php 
                                    endwhile;
                                    endif; ?>
                                  </ul>
 
                                <?php
                                unset($query1);
                                wp_reset_postdata();
                                wp_reset_query();
                                ?>
                              </div>
                              
                              <div class="clearfix">
                                <br />
                                <input type="submit" value="Submit" />
                              </div>
                              
            	            </div>
          	            </div>
        	            </div>
        	            <?php
          	            /*
            	          if($entry['1'] && !empty($entry['1'])) {
              	          // APPLICATION NAME
              	          echo $entry['1'];
            	          }
            	          if($entry['2'] && !empty($entry['2'])) {
              	          // TYPE OF MENTORING REQUESTED
              	          echo $entry['2'];
            	          }
            	          if($entry['19'] && !empty($entry['19'])) {
              	          // TOPIC/ISSUE
              	          echo $entry['19'];
            	          }
            	          if($entry['20'] && !empty($entry['20'])) {
              	          // SIZE OF GROUP
              	          echo $entry['20'];
            	          }
            	          if($entry['21'] && !empty($entry['21'])) {
              	          // WHEN
              	          echo $entry['21'];
            	          }
            	          if($entry['22'] && !empty($entry['22'])) {
              	          // METHOD
              	          echo $entry['22'];
            	          }
            	          if($entry['24'] && !empty($entry['24'])) {
              	          // PREFERRED MENTOR
              	          echo $entry['24'];
            	          }
            	          if($entry['31'] && !empty($entry['31'])) {
              	          // MATCHED MENTOR
              	          echo $entry['31'];
            	          }
            	          */
                      ?>
      	          </form>
                <?php           
                }
    	        }
  	      } ?>
          
          <br />
          <br />
            
            <div class="clearfix">          
              <h3 class="pull-left"><strong>Active</strong> Mentors</h3>
              <div class="pull-right">
        	      <a class="button" target="_blank" href="/products/state-mentoring-program/mentor-search/">Go to the Mentor Finder</a>
      	      </div>
            </div>            
    	      <hr />
    	      <div class="row">
              <?php
                wp_reset_postdata();
                wp_reset_query();
                $args1 = array(
                  'post_type'		=> 'mentors',
                  // "post_status" => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private'),
                  "post_status" => 'publish',
                  "orderby" => "author",
                  "order" => "ASC",
                  'posts_per_page' => -1,
                  'meta_key' => 'mentoring_status',
                  'meta_value' => 'available'
                );
                $query1 = new WP_Query($args1);
                if( $query1->have_posts() ) : while ( $query1->have_posts() ) : $query1->the_post();
                    $theId = get_the_id();
                    $theMeta = get_post_meta($theId);
                    $mentorUserId = get_the_author_meta('ID');
                    $user_info = get_userdata($mentorUserId);
                    // echo "<pre>".print_r($theMeta, true)."</pre>";
                    // $expArr = unserialize($theMeta['areas_of_expertise_filter'][0]);
                    $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                    // get_field('ACF_FIELD', $theId);
                    
                    if($mentorUserId) { 
                      $dbStatus = get_post_status($theId);
                      $status = get_post_status($theId);
                      if($status == "publish") {
                        $status = "Active";
                      } elseif($status == "draft") {
                        $status = "Inactive";
                      } else {
                        $status = "Inactive";
                      }
                    ?>
                    <div class="col-sm-12 col-md-6">
                      <div class="mentor-admin-item <?php echo strtolower($status); ?>" data-status="<?php echo strtolower($status); ?>" data-postid="<?php echo $theId; ?>" data-mentorid="<?php echo $mentorUserId; ?>">
                        <div class="row">
                          <div class="col-sm-12 col-md-6">
                            <h4><strong><a target="_blank" href="<?php the_permalink(); ?>"><?php echo $user_info->first_name." ".$user_info->last_name; ?></a></strong></h4>
                            <p><a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo $user_info->user_email; ?></a></p>
                            <!-- MENTOR INFO -->
                            <strong>State:</strong> <?php echo get_field('address_state', $theId); ?>
                            <br />
                            <strong>Status:</strong> <?php echo $status; ?>
                            <br />
                            <strong>Activities:</strong>
                            <ul>
                              <?php
                                foreach($activitiesArr as $activity) { ?>
                                  <li><?php echo $activity; ?></li>
                                <?php
                                }
                              ?>
                            </ul>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <?php
                              if($status == "Active") { ?>
                                <form method="post">
                                  <input type="hidden" name="deactivate_mentor" value="true" />
                                  <input type="hidden" name="mentor_post_id" value="<?php echo $theId; ?>" />
                    	            <input type="submit" value="Deactivate Mentor" />
                                </form>
                              <?php  
                              }
                            ?>
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
            
            <h3><strong>Inactive</strong> Mentors</h3>
    	      <hr />
            <div class="row">
              <?php
                wp_reset_postdata();
                wp_reset_query();
                $args1 = array(
                  'post_type'		=> 'mentors',
                  // "post_status" => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private'),
                  "post_status" => array('pending', 'draft', 'auto-draft'),
                  "orderby" => "author",
                  "order" => "ASC",
                  'posts_per_page' => -1,
                  'meta_key' => 'mentoring_status',
                  'meta_value' => 'available'
                );
                $query1 = new WP_Query($args1);
                if( $query1->have_posts() ) : while ( $query1->have_posts() ) : $query1->the_post();
                    $theId = get_the_id();
                    $theMeta = get_post_meta($theId);
                    $mentorUserId = get_the_author_meta('ID');
                    $user_info = get_userdata($mentorUserId);
                    // echo "<pre>".print_r($theMeta, true)."</pre>";
                    // $expArr = unserialize($theMeta['areas_of_expertise_filter'][0]);
                    $activitiesArr = unserialize($theMeta['participating_activities'][0]);
                    // get_field('ACF_FIELD', $theId);
                    
                    if($mentorUserId) { 
                      $dbStatus = get_post_status($theId);
                      $status = get_post_status($theId);
                      if($status == "publish") {
                        $status = "Active";
                      } elseif($status == "draft") {
                        $status = "Inactive";
                      } else {
                        $status = "Inactive";
                      }
                    ?>
                    <div class="col-sm-12 col-md-6">
                      <div class="mentor-admin-item <?php echo strtolower($status); ?>" data-status="<?php echo strtolower($status); ?>" data-postid="<?php echo $theId; ?>" data-mentorid="<?php echo $mentorUserId; ?>">
                        <div class="row">
                          <div class="col-sm-12 col-md-6">
                            <?php /* <a target="_blank" href="<?php the_permalink(); ?>"></a> */ ?>
                            <h4><strong><?php echo $user_info->first_name." ".$user_info->last_name; ?></strong></h4>
                            <p><a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo $user_info->user_email; ?></a></p>
                            <!-- MENTOR INFO -->
                            <strong>State:</strong> <?php echo get_field('address_state', $theId); ?>
                            <br />
                            <strong>Status:</strong> <?php echo $status; ?>
                            <br />
                            <strong>Activities:</strong>
                            <ul>
                              <?php
                                foreach($activitiesArr as $activity) { ?>
                                  <li><?php echo $activity; ?></li>
                                <?php
                                }
                              ?>
                            </ul>
                          </div>
                          <div class="col-sm-12 col-md-6">
                            <?php
                              if($status == "Inactive") { ?>
                                <form method="post">
                                  <input type="hidden" name="activate_mentor" value="true" />
                                  <input type="hidden" name="mentor_post_id" value="<?php echo $theId; ?>" />
                    	            <input type="submit" value="Activate Mentor" />
                                </form>
                              <?php
                              }
                            ?>
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
        <?php
        } ?>
      </div>

      <br />
      
      <?php // echo do_shortcode( '[s2Member-Profile /]' ); ?>
      
    </div>
  </div>
<?php endwhile; ?>

<script type="text/javascript">
</script>
