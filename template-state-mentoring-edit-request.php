
<?php
/**
 * Template Name: State Mentoring Edit Request
 */
?>

<?php
	$submission_id = get_query_var( 'form', null );
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  
  <style>
	  li#field_4_1 {
		  /* display: none !important; */
	  }
  </style>
  
  <?php
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
    $form_options = get_option( 'state_mentoring_option_name' );
	  $request_mentoring_form_id = $form_options['request_mentoring_form_id'];
  ?>
  
  <div class="container clearfix">
    <div id="" class="col-xs-12 clearfix">
      
      <?php
	      // GET REQUEST MENTORING SUBMISSIONS
	      $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
        $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
        // echo "<pre>".print_r($entries, true)."</pre>";
        
        if($submission_id && !empty($submission_id) && $entries && !empty($entries)) {
	        foreach($entries as $entry) {
		      	if($entry['id'] == $submission_id) {
			      	$editEntry = GFAPI::get_entry($submission_id);
			      	if($editEntry) {
				      	echo '<h1>'.$editEntry[1].'</h1>';
				      	// echo "<pre>".print_r($editEntry, true)."</pre>";
				      	?>
				      	
				      	<?php
				      		// gravity_form(4, $display_title=false, $display_description=false, $display_inactive=false, $field_values=null, $ajax=false, $tabindex);
				      		
				      		$values = array();
				      		$values['application_name'] = $editEntry[1];
				      		$values['type_of_mentoring_requested'] = $editEntry[2];
				      		$values['assistance_with'] = $editEntry[19];
				      		$values['when_need'] = $editEntry[21];
				      		$values['preferred_method'] = $editEntry[23];
				      		$values['preferred_mentor'] = $editEntry[24];
				      		$values['group_size'] = $editEntry[20];
				      		
									// ?
				      		$values['success_look_like'] = $editEntry[4];
				      		$values['list'] = unserialize($editEntry[5]);
				      		$values['need_from_mentor'] = $editEntry[6];
				      		$values['type_of_support'] = $editEntry[7];
				      		$values['do_not_complete'] = $editEntry[8];
				      		// ?
				      		
				      		$values['training_modules'] = array($editEntry['9.1'], $editEntry['9.2']);
				      		$values['submission_id'] = $submission_id;
				      		
									// echo "<pre>".print_r($values, true)."</pre>"; 
				      		
				      		gravity_form($request_mentoring_form_id, $display_title=false, $display_description=false, $display_inactive=false, $values, $ajax=false, $tabindex);
				      	?>
				      <?php
			      	}
		      	}
	        }
        }
      ?>
      
      <?php
	      // $form4 = GFAPI::get_form($request_mentoring_form_id);
	      // echo "<pre>".print_r($form4, true)."</pre>"; 
	    ?>
      
    </div>
  </div>
  
<?php endwhile; ?>