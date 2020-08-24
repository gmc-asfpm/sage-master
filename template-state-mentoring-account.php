<?php
/**
 * Template Name: State Mentoring Account Template
 */
?>

<?php
	// DELETE FORM FROM GET QUERY -> ?delete_form=ID is in functions.php 'wp' hook
	/*
	$delete_form = get_query_var( 'delete_form', null );
	if($delete_form && !empty($delete_form)) {
		global $current_user;
    get_currentuserinfo();
    $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
    $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
    if($delete_form && !empty($delete_form) && $entries && !empty($entries)) {
      foreach($entries as $entry) {
      	if($entry['id'] == $delete_form) {
					$deleteResult = GFAPI::delete_entry($delete_form);
	      }
	    }
	  }
	}
	*/
?>
<script type="text/javascript">
	function confirmDelete(title, id) {
		var diag = confirm("Delete form: "+title+"?");
		if(diag == true) {
			window.location = "?delete_form="+id;
		} else {}
	}
</script>
<script type="text/javascript">
  jQuery("label[for='ws-plugin--s2member-profile-display-name']").closest('tr').remove();
  jQuery("#ws-plugin--s2member-profile-submit").attr('value', 'Update Email/Password');
  jQuery(document).ready(function() {
    jQuery("label[for='ws-plugin--s2member-profile-display-name']").closest('tr').remove();
    jQuery("#ws-plugin--s2member-profile-submit").attr('value', 'Update Email/Password');
  });
</script>
<style>
  #ws-plugin--s2member-profile-password-strength { display: none !important; }
</style>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
  <?php
	  // ACF DATA
	  $project_homepage = get_field('project_homepage');
	  
    acf_form_head();
    
    global $current_user;
    get_currentuserinfo();
    
    $author_query = array(
      'posts_per_page' => '1',
      'author' => $current_user->ID,
      'post_type' => array('mentors', 'mentees'),
      'post_status' => 'any'
    );
    $author_posts = new WP_Query($author_query);
    $authorPostId = "";
    while($author_posts->have_posts()) : $author_posts->the_post(); 
      $authorPostId = get_the_id();
      $postType = get_post_type($authorPostId);
      break;
    endwhile;
    
		// GET REQUEST MENTORING FORM ID
    $form_options = get_option('state_mentoring_option_name');
	  $request_mentoring_form_id = $form_options['request_mentoring_form_id'];
	  $ask_a_mentor_form_id = $form_options['ask_a_mentor_form_id'];
  ?>
  
  <div class="container clearfix">
    <div id="" class="col-xs-12 clearfix">
      <ul class="centered-list">
        <li><a class="button" href="#account-settings-section">Account Settings</a></li>
        <li><a class="button" href="#profile-settings-section">Profile Information</a></li>
        <?php  
        if($postType == "mentees") { ?>
          <li><a class="button" href="#request-mentoring-section">Request Mentoring</a></li>
        <?php
        }
				if($project_homepage && !empty($project_homepage)) {
					$project_homepage_permalink = get_permalink($project_homepage->ID);
				}
				$logoutUrl = wp_logout_url()."?mentor_logout=true&test";
				if(isset($project_homepage_permalink)) {
					$logoutUrl = $project_homepage_permalink."?mentor_logout=true";
				} ?>
				<li><a class="button" href="<?php echo $logoutUrl; ?>">Log Out</a></li>
      </ul>        
      <br>
      <br>
    </div>
    <div id="account-settings-section" class="col-xs-12 col-sm-12 col-md-6" data-userid="<?php echo $authorPostId; ?>">
      <?php
        // echo do_shortcode( '[iscorrect]' . $text_to_be_wrapped_in_shortcode . '[/iscorrect]' );
        // ob_start();
        // ob_get_clean();
        // if( current_user_can('editor') ) {}
        /*
        if( current_user_is('s2member_level1') ) {
          // echo "You are a Mentee";
        }
        if( current_user_is('s2member_level2') ) {
          // echo "You are a Mentor";
        }
        if( current_user_is('s2member_level3') ) {
          // echo "You are a Mentor";
        }
        if( current_user_is( 's2member_level1', 's2member_level2', 's2member_level3') ) {
          // echo "This is both";
        }
        */
      ?>

      <br>
      <br>
      <br>
      <br>
      
      <div class="settings-inset">
        <h2>Account Settings</h2>
        <?php echo do_shortcode( '[s2Member-Profile /]' ); ?>
      </div>
      
      <br />
      
      <?php
        // MENTOR
        // GET MATCHED MENTORING REQUESTS
        $isMatches = false;
        if($postType == "mentors") {
          $entries = GFAPI::get_entries($request_mentoring_form_id);
  	      if($entries && !empty($entries)) { 
            foreach($entries as $entry) {
              if($entry['31'] && !empty($entry['31'])) {
                if($authorPostId == $entry['31']) {
                  // MATCHED REQUEST
                  // echo "<pre>".print_r($entry, true)."</pre>";
                  $menteeUserId = $entry['created_by'];
                  $menteeUserData = get_userdata($menteeUserId);
                  if($menteeUserData && !empty($menteeUserData)) {
                    $isMatches = true;
                    
                    $mentee_query = array(
                      'posts_per_page' => '1',
                      'author' => $menteeUserId,
                      'post_type' => array('mentees'),
                      'post_status' => 'any'
                    );
                    $mentee_posts = new WP_Query($mentee_query);
                    $menteePostId = "";
                    while($mentee_posts->have_posts()) : $mentee_posts->the_post(); 
                      $menteePostId = get_the_id();
                      break;
                    endwhile;
                    
                    break;  
                  }
                }  
              }
      	    }
            
            if($isMatches && $menteePostId) { ?>
              <div class="settings-inset">
                <h2>Mentoring Connections</h2> 
                 
                <?php  
                  foreach($entries as $entry) {
                    if($entry['31'] && !empty($entry['31'])) {
                      if($authorPostId == $entry['31']) {
                        // MATCHED REQUEST
                        // echo "<pre>".print_r($entry, true)."</pre>";
                        $menteeUserId = $entry['created_by'];
                        $menteeUserData = get_userdata($menteeUserId);
                        ?>
                        <div class="">
                          <?php // MENTEE INFO ?>
                          <?php // echo "<pre>".print_r($menteeUserData, true)."</pre>"; ?>                             
                          <div><strong>Mentee:</strong> <?php echo $menteeUserData->first_name." ".$menteeUserData->last_name; ?></div>
                          <div><strong>Email:</strong> <a href="mailto:<?php echo $menteeUserData->user_email; ?>"><?php echo $menteeUserData->user_email; ?></a></div>
                          <div><strong>Phone:</strong> <a href="tel:<?php echo get_field('cell_phone', $menteePostId); ?>"><?php echo get_field('cell_phone', $menteePostId); ?></a></div>
                          <div><strong>Application Name:</strong> <?php echo $entry['1']; ?></div>
                          <div><strong>Type of Mentoring:</strong> <?php echo $entry['2']; ?></div>
                          <?php
                            if($entry['19'] && !empty($entry['19'])) { ?>
                  	          <div><strong>Topic:</strong> <?php echo $entry['19']; ?></div>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['20'] && !empty($entry['20'])) { ?>
                  	          <div><strong>Size of Group:</strong> <?php echo $entry['20']; ?></div>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['21'] && !empty($entry['21'])) { ?>
                  	          <div><strong>When:</strong> <?php echo $entry['21']; ?></div>
                  	          <?php
                	          }
                          ?>
                          <?php
                            if($entry['22'] && !empty($entry['22'])) { ?>
                  	          <div><strong>Method:</strong> <?php echo $entry['22']; ?></div>
                  	          <?php
                	          }
                          ?>
                          <hr />
          	            </div>
                        <?php
                      }  
                    }
            	    } ?>
              </div>
              <?php
            } else {
            }
  	      }
        }
      ?>
      
      <br />
      
      <?php
        // MENTEE
	      // GET MATCHED REQUEST MENTORING SUBMISSIONS
	      $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
        $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
        $activeMatch = false;
        $inactiveMatch = false;
	      if($entries && !empty($entries)) {
	        foreach($entries as $entry) {
  	        if($entry['31'] && !empty($entry['31'])) {
    	        $activeMatch = true;
    	        break;
    	      }
          }
          foreach($entries as $entry) {
  	        if(empty($entry['31'])) {
    	        $inactiveMatch = true;
    	        break;
    	      }
          }    
          if($activeMatch) { ?>
            <div class="settings-inset">
    	        <h2>Active Mentoring Connections</h2>
    	        <ul class="mentee-submitted-forms clearfix">      	          
    		      <?php
  			        foreach($entries as $entry) {
    			        if($entry['31'] && !empty($entry['31'])) { ?>
    				      	<li class="clearfix">
    				      		<div class="col-xs-6 col-sm-6 col-sm-8 no-pad">
    					      		<?php /* <a href="edit-request?form=<?php echo $entry['id'] ?>"><?php echo $entry[1]; ?></a> */ ?>
                        <strong><?php echo $entry[1]; ?></strong>
                        <?php
                        // MENTOR DATA
                        $mentorPost = get_post($entry['31']);
                        $mentorUserData = get_userdata($mentorPost->post_author); ?>
                        <div>
                          You have been matched with:<br /><strong><a target="_blank" href="<?php echo get_the_permalink($mentorPost); ?>"><?php echo $mentorUserData->first_name." ".$mentorUserData->last_name; ?></a></strong>
                        </div>
    				      		</div>
    				      		<div class="col-xs-6 col-sm-6 col-md-4 no-pad text-right">
      				      		<?php /* <?php echo date("M. j, Y", strtotime($entry['date_created'])); ?>&nbsp;|&nbsp;<a href="edit-request?form=<?php echo $entry['id'] ?>">Edit <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;|&nbsp;<a onclick='confirmDelete("<?php echo $entry[1] ?>" ,"<?php echo $entry['id'] ?>");'><i class="fa fa-trash-o red" aria-hidden="true"></i></a> */ ?>
    					      		<?php echo date("M. j, Y", strtotime($entry['date_created'])); ?>&nbsp;|&nbsp;<a onclick='confirmDelete("<?php echo $entry[1] ?>" ,"<?php echo $entry['id'] ?>");'><i class="fa fa-trash-o red" aria-hidden="true"></i></a>
    				      		</div>
    				      	</li>
    				      <?php
    			        }
                } ?>
    		      </ul>
      		  </div>         
      		<?php  
          }
        } ?>
        
	    <br />
      
      <?php
        // MENTEE
	      // GET REQUEST MENTORING SUBMISSIONS
	      // $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
        // $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
	      if($entries && !empty($entries) && $inactiveMatch) { ?>
		  		<div class="settings-inset">
		        <h2>Submitted Mentoring Requests</h2>
		        <ul class="mentee-submitted-forms clearfix">
		        <?php
			        foreach($entries as $entry) {
			          if($entry['31'] && !empty($entry['31'])) {
  			        } else { ?>
  				      	<li class="clearfix">
  				      		<div class="col-xs-6 col-sm-6 col-sm-8 no-pad">
  					      		<?php /* <a href="edit-request?form=<?php echo $entry['id'] ?>"><?php echo $entry[1]; ?></a> */ ?>
                      <strong><?php echo $entry[1]; ?></strong>
  				      		</div>
  				      		<div class="col-xs-6 col-sm-6 col-md-4 no-pad text-right">
    				      		<?php /* <?php echo date("M. j, Y", strtotime($entry['date_created'])); ?>&nbsp;|&nbsp;<a href="edit-request?form=<?php echo $entry['id'] ?>">Edit <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;|&nbsp;<a onclick='confirmDelete("<?php echo $entry[1] ?>" ,"<?php echo $entry['id'] ?>");'><i class="fa fa-trash-o red" aria-hidden="true"></i></a> */ ?>
  					      		<?php echo date("M. j, Y", strtotime($entry['date_created'])); ?>&nbsp;|&nbsp;<a onclick='confirmDelete("<?php echo $entry[1] ?>" ,"<?php echo $entry['id'] ?>");'><i class="fa fa-trash-o red" aria-hidden="true"></i></a>
  				      		</div>
  				      	</li>
                <?php
  				      }
			        } ?>
		        </ul>
		      </div>         
		    <?php  
	      } ?>
	      
	    <br />
	    
	    <?php
  	    // MENTEE
	      // GET ASK A MENTOR SUBMISSIONS
	      $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
        $entries = GFAPI::get_entries($ask_a_mentor_form_id, $search_criteria);
	      if($entries && !empty($entries)) { ?>
		  		<div class="settings-inset">
		        <h2>Submitted Ask a Mentor Questions</h2>
		        <ul class="mentee-submitted-forms clearfix">
		        <?php
			        foreach($entries as $entry) { ?>
				      	<li class="clearfix">
				      			
				      		<div class="col-sm-6 col-xs-6 no-pad">
					      		<?php /* <a href="edit-request?form=<?php echo $entry['id'] ?>"></a> */ ?>
										<?php echo $entry[1]; ?>
					      		<?php // echo "<pre>".print_r($entry, true)."</pre>"; ?>
					      		<p style="display: none;"><?php echo $entry[2]; ?></p>
				      		</div>
				      		<div class="col-sm-6 col-xs-6 no-pad text-right">
					      		<?php echo date("M. j, Y", strtotime($entry['date_created'])); ?>&nbsp;|&nbsp;<a onclick='confirmDelete("<?php echo $entry[1] ?>" ,"<?php echo $entry['id'] ?>");'><i class="fa fa-trash-o red" aria-hidden="true"></i></a>
				      		</div>
				      	</li>
				      <?php
			        } ?>
		        </ul>
		      </div>         
		    <?php  
	      } ?>

    </div>
    
    <div id="profile-settings-section" class="col-xs-12 col-sm-12 col-md-6">
      <br>
      <br>
      <br>
      <br>
      <div class="settings-inset">
        <h2>Profile Information</h2>
        <?php
          if($authorPostId && !empty($authorPostId)) {
            if($postType == "mentors") {
              $options = array(
              	/* (string) Unique identifier for the form. Defaults to 'acf-form' */
              	// 'id' => 'acf-form',
              	'id' => 'acf-mentor-form',
              	/* (int|string) The post ID to load data from and save data to. Defaults to the current post ID. 
              	Can also be set to 'new_post' to create a new post on submit */
              	'post_id' => $authorPostId,
              	/* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
              	The above 'post_id' setting must contain a value of 'new_post' */
              	'new_post' => false,
              	/* (array) An array of field group IDs/keys to override the fields displayed in this form */
              	'field_groups' => false,
              	/* (array) An array of field IDs/keys to override the fields displayed in this form */
              	// 'fields' => false,
              	'fields' => array(
                	// 'first_name', // MANAGED BY S2 PROFILE
                	// 'last_name', // MANAGED BY S2 PROFILE
                	'mentoring_status',
                	'address_street',
                	'address_street_2',
                	'address_city',
                	'address_state',
                	'address_zip',
                	'address_country',
                	// 'work_email', // MANAGED BY S2 PROFILE
                	'work_phone',
                	'cell_phone',
                	'cfm',
                	'fema_region',
                	'time_in_state_floodplain',
                	'total_time_in_floodplain',
                	'time_in_state_hazard',
                	'total_time_in_hazard',
                	'areas_of_expertise_filter',
                	'participating_activities',
                	'professional_biography',
                	'headshot'
              	),
              	/* (boolean) Whether or not to show the post title text field. Defaults to false */
              	'post_title' => false,	
              	/* (boolean) Whether or not to show the post content editor field. Defaults to false */
              	'post_content' => false,
              	/* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
              	'form' => true,
              	/* (array) An array or HTML attributes for the form element */
              	'form_attributes' => array(),
              	/* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
              	A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
              	'return' => '',
              	/* (string) Extra HTML to add before the fields */
              	'html_before_fields' => '',
              	/* (string) Extra HTML to add after the fields */
              	'html_after_fields' => '',
              	/* (string) The text displayed on the submit button */
              	'submit_value' => __("Update Profile Information", 'acf'),
              	/* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
              	'updated_message' => __("Post updated", 'acf'),
              	/* (string) Determines where field labels are places in relation to fields. Defaults to 'top'. 
              	Choices of 'top' (Above fields) or 'left' (Beside fields) */
              	'label_placement' => 'top',
              	/* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'. 
              	Choices of 'label' (Below labels) or 'field' (Below fields) */
              	'instruction_placement' => 'label',
              	/* (string) Determines element used to wrap a field. Defaults to 'div' 
              	Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
              	'field_el' => 'div',
              	/* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp' 
              	Choices of 'wp' or 'basic'. Added in v5.2.4 */
              	'uploader' => 'wp',
              	/* (boolean) Whether to include a hidden input field to capture non human form submission. Defaults to true. Added in v5.3.4 */
              	'honeypot' => true
              );  
            } elseif($postType == "mentees") {
              $options = array(
              	/* (string) Unique identifier for the form. Defaults to 'acf-form' */
              	// 'id' => 'acf-form',
              	'id' => 'acf-mentor-form',
              	/* (int|string) The post ID to load data from and save data to. Defaults to the current post ID. 
              	Can also be set to 'new_post' to create a new post on submit */
              	'post_id' => $authorPostId,
              	/* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
              	The above 'post_id' setting must contain a value of 'new_post' */
              	'new_post' => false,
              	/* (array) An array of field group IDs/keys to override the fields displayed in this form */
              	'field_groups' => false,
              	/* (array) An array of field IDs/keys to override the fields displayed in this form */
              	// 'fields' => false,
              	'fields' => array(
                	// 'first_name', // MANAGED BY S2 PROFILE
                	// 'last_name', // MANAGED BY S2 PROFILE
                	'address_street',
                	'address_street_2',
                	'address_city',
                	'address_state',
                	'address_zip',
                	'address_country',
                	// 'work_email', // MANAGED BY S2 PROFILE
                	'work_phone',
                	'cell_phone',
                	'cfm',
                  'state_official',
                  'state_agency'
              	),
              	/* (boolean) Whether or not to show the post title text field. Defaults to false */
              	'post_title' => false,	
              	/* (boolean) Whether or not to show the post content editor field. Defaults to false */
              	'post_content' => false,
              	/* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
              	'form' => true,
              	/* (array) An array or HTML attributes for the form element */
              	'form_attributes' => array(),
              	/* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
              	A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
              	'return' => '',
              	/* (string) Extra HTML to add before the fields */
              	'html_before_fields' => '',
              	/* (string) Extra HTML to add after the fields */
              	'html_after_fields' => '',
              	/* (string) The text displayed on the submit button */
              	'submit_value' => __("Update", 'acf'),
              	/* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
              	'updated_message' => __("Post updated", 'acf'),
              	/* (string) Determines where field labels are places in relation to fields. Defaults to 'top'. 
              	Choices of 'top' (Above fields) or 'left' (Beside fields) */
              	'label_placement' => 'top',
              	/* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'. 
              	Choices of 'label' (Below labels) or 'field' (Below fields) */
              	'instruction_placement' => 'label',
              	/* (string) Determines element used to wrap a field. Defaults to 'div' 
              	Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
              	'field_el' => 'div',
              	/* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp' 
              	Choices of 'wp' or 'basic'. Added in v5.2.4 */
              	'uploader' => 'wp',
              	/* (boolean) Whether to include a hidden input field to capture non human form submission. Defaults to true. Added in v5.3.4 */
              	'honeypot' => true
              );
            }
            
            acf_form($options);
          }
          ?>
      </div>
    </div>
    
    <div id="request-mentoring-section" class="col-xs-12">
      <?php
        // MENTEE
        if($postType == "mentees") { ?>
          <br>
          <br>
          <div class="settings-inset">
            <h2>Request Mentoring</h2>
            <?php
            // echo do_shortcode('[gravityform id="4" title="false" description="false" ajax="false"]');
            // echo do_shortcode('[gravityform id="4"]');
            gravity_form($request_mentoring_form_id, $display_title=false, $display_description=false, $display_inactive=false, $field_values=null, $ajax=false, $tabindex);
            ?>
          </div>
        <?php
        } ?>
    </div>
  </div>
<?php endwhile; ?>