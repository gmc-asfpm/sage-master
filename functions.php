<?php
if(!session_id()) {
  session_start();
}
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


/* EARTHLING INTERACTIVE */
show_admin_bar(false);

wp_enqueue_style(
  'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', null, null
);

wp_enqueue_script (
  'bxslider-js', get_template_directory_uri().'/assets/bxslider_resposive/jquery.bxslider-rahisified.min.js', array('jquery'), null
);
wp_enqueue_style(
  'bxslider-css', get_template_directory_uri().'/assets/bxslider_resposive/jquery.bxslider.css', null, null
);

wp_enqueue_style(
  'sidr-css', get_template_directory_uri().'/assets/sidr/stylesheets/jquery.sidr.bare.css', null, null
);
wp_enqueue_script (
  'sidr-js', get_template_directory_uri().'/assets/sidr/jquery.sidr.min.js', array('jquery'), null
);

wp_enqueue_style(
  'asfpm-additional-css', get_template_directory_uri().'/assets/styles/additional.css', null, null
);


add_filter( 'gform_enable_password_field', '__return_true' );


function add_query_vars_filter( $vars ){
  $vars[] = "form";
  $vars[] = "delete_form";
  $vars[] = "mentor_logout";
  $vars[] = "clear_results";
  $vars[] = "mentor";
  $vars[] = "event_ics";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


function cc_mime_types($mimes = array()) {
  $mimes['svg'] = 'image/svg+xml';
  $mimes['svgz'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


// [video_embed type="youtube" id="123"]
function video_embed_func( $atts ) {
  if($atts['type'] && $atts['id']) {
    
    if($atts['type'] == 'youtube') {
      $src = 'https://www.youtube.com/embed/'.$atts['id'];
      $embed_iframe = '<iframe class="embed-responsive-item" src="'.$src.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    } elseif($atts['type'] == 'vimeo') {
      $src =  'https://player.vimeo.com/video/'.$atts['id'];
      $embed_iframe = '<iframe class="embed-responsive-item" src="'.$src.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    }

    $embed = '';
    $embed .= '<div class="embed-responsive embed-responsive-16by9">';
    $embed .= $embed_iframe;
    $embed .= '</div>';
    return $embed;
  } else {
    return;
  }
}
add_shortcode( 'video_embed', 'video_embed_func' );


// [anchor_link id="anchor1"]
function anchor_link_func( $atts ) {
  if($atts['id'] && !empty($atts['id'])) {
    return '<a id="'.$atts['id'].'"></a>';
  } else {
    return;
  }
}
add_shortcode( 'anchor_link', 'anchor_link_func' );

/**
* Enables a 'reverse' option for wp_nav_menu to reverse the order of menu
* items. Usage:
*
* wp_nav_menu(array('reverse' => TRUE, ...));
*/
function my_reverse_nav_menu($menu, $args) {
	if (isset($args->reverse) && $args->reverse) {
		return array_reverse($menu);
	}
	return $menu;
}
add_filter('wp_nav_menu_objects', 'my_reverse_nav_menu', 10, 2);


add_filter('wp_nav_menu_objects', 'set_curret_nav_func');
function set_curret_nav_func($sorted_menu_items) {
  foreach($sorted_menu_items as $menu_item) {
    if($menu_item->current) {
      $GLOBALS['curret_nav_title'] = $menu_item->title;
      break;
    }
  }
  return $sorted_menu_items;
}


function delete_form() {
	$delete_form = get_query_var('delete_form', null);
	if($delete_form && !empty($delete_form)) {
		global $current_user;
    get_currentuserinfo();
    $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
    $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
    if($delete_form && !empty($delete_form) && $entries && !empty($entries)) {
      foreach($entries as $entry) {
      	if($entry['id'] == $delete_form) {
					$deleteResult = GFAPI::delete_entry($delete_form);	
					wp_redirect(remove_query_arg('delete_form', false));
					exit();
	      }
	    }
	  }
	}
}
add_action( 'wp', 'delete_form' );

function mentor_logout() {
	$mentor_logout = get_query_var('mentor_logout', null);
	if($mentor_logout && !empty($mentor_logout)) {
		error_log("LOGGING OUT", 0);
		wp_logout();
		wp_redirect(remove_query_arg('mentor_logout', false));
		exit();
	}
}
add_action( 'wp', 'mentor_logout' );

function clear_results() {
	$clear_results = get_query_var('clear_results', null);
	if($clear_results && !empty($clear_results)) {
		wp_reset_postdata();
		wp_redirect(remove_query_arg('clear_results', false));
		exit();
	}
}
add_action( 'wp', 'clear_results' );



function download_ics() {
	// error_log("RUNNING download_ics", 0);
	// $download_ics = get_query_var('event_ics', null);
	$download_ics = $_GET['event_ics'];
	
	if($download_ics && !empty($download_ics)) {
		// GENERATE ICS FILE
		error_log("GENERATING ICS FILE", 0);
		if(!session_id()) {
      session_start();
    }
		
		$calPost = get_post($download_ics);
			
		if($calPost) {
			error_log("EVENT POST EXISTS", 0);
			// echo "TEST";
			
			$calPostId = $calPost->ID;
			
			global $ai1ec_registry;
			$event1 = new Ai1ec_Event($ai1ec_registry);
			$event1->initialize_from_id( $calPost->ID );
			
			$event_s = $event1->get('start');
			$event_e = $event1->get('end');
			
			$event_start_date_formatted = $event_s->format('Ymd');
			$event_end_date_formatted = $event_e->format('Ymd');
			$event_start_time_formatted = $event_s->format('His');
			$event_end_time_formatted = $event_e->format('His');
			
			$event_title = $event1->get('post')->post_title;
      $event_name = $event1->get('post')->post_name;
      $event_allday = $event1->get('allday');
      $event_venue = $event1->get('venue');
      $event_country = $event1->get('country');
      $event_address = $event1->get('address');
      $event_city = $event1->get('city');
      $event_province = $event1->get('province');
      $event_contact_name = $event1->get('contact_name');
      $event_contact_phone = $event1->get('contact_phone');
      $event_contact_email = $event1->get('contact_email');
      $event_contact_url = $event1->get('contact_url');
      $event_cost = $event1->get('cost');
      $event_ticket_url = $event1->get('ticket_url');
      $event_ical_feed_url = $event1->get('ical_feed_url');
      $event_ical_source_url = $event1->get('ical_source_url');
      $event_ical_organizer = $event1->get('ical_organizer');
      $event_ical_contact = $event1->get('ical_contact');
      $event_show_coordinates = $event1->get('show_coordinates');
      $event_latitude = $event1->get('latitude');
      $event_longitude = $event1->get('longitude');
			$content = $calPost->post_content;

			// Strip HTML Tags
			$clean = strip_tags( $content );
			// Clean up things like &amp;
			$clean = html_entity_decode( $clean );
			// Strip out any url-encoded stuff
			$clean = urldecode( $clean );
			// Replace everything except certain special characters, alphabetical letters, and numbers with space
			$clean = preg_replace( '/[^A-Za-z0-9.,?!:\"\']/', ' ', $clean );
			// Replace multiple spaces with single space
			$clean = preg_replace( '/ +/', ' ', $clean );
			// Trim the string of leading/trailing space
			$clean_content = trim( $clean );
			
			header("Content-Type: text/Calendar");
			header("Content-Disposition: inline; filename=calendar.ics");
			
			echo "BEGIN:VCALENDAR\n";
			echo "VERSION:2.0\n";
			echo "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";
			echo "BEGIN:VEVENT\n";
			echo "DTSTART:" . $event_start_date_formatted . "T" . $event_start_time_formatted . "Z\n";
			echo "DTEND:" . $event_end_date_formatted . "T" . $event_end_time_formatted . "Z\n";
			echo "SUMMARY:" . $event_title . "\n";
			echo "LOCATION:";
			echo $event_venue . " ";
			echo $event_address . " ";
			echo $event_city . " ";
			echo $event_state . "\n";
			echo "DESCRIPTION:" . $clean_content . "\n";
			echo "END:VEVENT\n";
			echo "END:VCALENDAR\n";
			
			exit();
		} else {
			exit();
		}
		// wp_redirect(remove_query_arg('event_ics', false));
		exit();
	}
}
add_action( 'init', 'download_ics', 0 );



// AJAX EVENT SHARE
add_action('wp_ajax_share_event_action', 'share_event_action_callback');
add_action('wp_ajax_nopriv_share_event_action', 'share_event_action_callback');
function share_event_action_callback() {
	//error_log("RUNNING share_event_action", 0);
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$message = $_POST['message'];
	$eventName = $_POST['eventName'];
	$eventUrl = $_POST['eventUrl'];
	
	$res = array();
	
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
  	$body = "";
  	$body .= "Here is a Floods.org event from ".$name."\n\n";
  	if($message) {
      $body .= $name." says: ".$message."\n\n";	
  	}
  	$body .= "Event Name: ".$eventName."\n";
  	$body .= "Event Link: ".$eventUrl."\n";
  	
    wp_mail($email, 'Floods.org Event from '.$name, $body );
    $res['success'] = true;
    
	} else {
  	$res['success'] = false;
	}
  
  echo json_encode($res);
	wp_die();
}

/* LOGIN REDIRECT */
/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function my_login_redirect( $redirect_to, $request, $user ) {
  error_log("my_login_redirect", 0);
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			// return $redirect_to;
		} else {
			// return home_url();
		}
		if ( in_array( 's2member_level3', $user->roles ) ) {
			// redirect them to the default place
			// return $redirect_to;
			echo "s2member_level3";
			die();
			// return home_url();
		} else {
			return home_url();
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );

/* GRAVITY FORMS */
add_action( 'gform_pre_submission', 'pre_submission_func' );
function pre_submission_func($form) {
	//error_log('gform_pre_submission', 0);
	// error_log("gform_pre_submission FORM ID: ".$form['id'], 0);
	
	$form_options = get_option( 'state_mentoring_option_name' );
  $ask_a_mentor_form_id = $form_options['ask_a_mentor_form_id'];
	
	if($form['id'] == $ask_a_mentor_form_id) {
		//error_log("gform_pre_submission ask_a_mentor_form_id");
		// MATCH MENTOR ID FIELD
		$mentorId = rgpost('input_3');
		//error_log("Mentor ID: ".$mentorId, 0);
		$mentorEmail = get_field("work_email", $mentorId);
		//error_log("Mentor Email: ".$mentorEmail, 0);
		
		// MATCH MENTOR EMAIL FIELD
		$_POST['input_4'] = $mentorEmail;
		$_POST['input_5'] = $mentorEmail;
		
		return $form;
	} else {
		return $form;	
	}
	
	
}

/*
add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
    if( $form['id'] == '101' ) {
        $confirmation = array( 'redirect' => 'http://www.google.com' );
    } elseif( $form['id'] == '102' ) {
        $confirmation = "Thanks for contacting us. We will get in touch with you soon";
    }
    return $confirmation;
}
*/

$form_options = get_option( 'state_mentoring_option_name' );
$request_mentoring_form_id = $form_options['request_mentoring_form_id'];

// UPDATE REQUEST MENTORING SUBMISSION?
add_filter( 'gform_entry_id_pre_save_lead', 'my_update_entry_on_form_submission', 10, 2 );
function my_update_entry_on_form_submission( $entry_id, $form ) {
	global $current_user;
  get_currentuserinfo();
  
	$form_options = get_option( 'state_mentoring_option_name' );
	$request_mentoring_form_id = $form_options['request_mentoring_form_id'];
	
	if($form['id'] == $request_mentoring_form_id) {	
		// INPUT NAME OF submission_id
    $update_entry_id = rgpost('input_11');
    return $update_entry_id ? $update_entry_id : $entry_id;
	} else {
		return $entry_id;
	}
}

/* MENTOR/MENTEE FORMS */
add_action('gform_after_submission', 'gform_after_submission_action', 10, 2);
function gform_after_submission_action( $entry, $form ) {
  if(!session_id()) {
    session_start();
  }
  error_log("gform_after_submission");
  
  function cleanAcf($item) {
    // return strtolower(str_replace("__", "_", str_replace(array(" ", "-"), "_", str_replace(array("/", "(", ")", ","), "", $item))));
    return $item;
  }
  // echo "<pre>".print_r($entry, true)."</pre>";
  
  $form_options = get_option( 'state_mentoring_option_name' );
  $mentor_form_id = $form_options['mentor_form_id'];
  $mentee_form_id = $form_options['mentee_form_id'];
  $request_mentoring_form_id = $form_options['request_mentoring_form_id'];
  $login_form_id = $form_options['login_form_id'];
  
  if($form["id"] == $mentor_form_id) {
    // MENTOR /////
    // s2member_level2 ////////////////////////////////////////
    ///////////////////////////////////////////////////////////
    
    // error_log("Form ID: ".$form["id"], 0);
    // $fullEntry = print_r($entry);
    
    // S2 MEMBER
    /////////////////

    $username = rgar($entry, '1');
    $password = rgar($entry, '4');
    
    
    // CUSTOM POST
    /////////////////
  
    $firstName = rgar($entry, '2.3');
    $lastName = rgar($entry, '2.6');
    $fullName = $firstName." ".$lastName;
    
    $address_street = rgar($entry, '9.1');
    $address_street_2 = rgar($entry, '9.2');
    $address_city = rgar($entry, '9.3');
    $address_state = rgar($entry, '9.4');
    $address_zip = rgar($entry, '9.5');
    $address_country = rgar($entry, '9.6');

    $workEmail = rgar($entry, '10');
    
    $workPhone = rgar($entry, '11');
    $cellPhone = rgar($entry, '12');
    
    $cfm = rgar($entry, '31.1');
    error_log("CFM IS: ", 0);
    error_log($cfm, 0);
    if($cfm && !empty($cfm)) {
	    // $cfm = true;
	    $cfm = "Yes";
	    error_log("CFM TRUE", 0);
    } else {
	    // $cfm = false;
	    $cfm = "No";
	    error_log("CFM FALSE", 0);
    } 
    
    $femaRegion = rgar($entry, '39');
    
    // $timeStateFloodplain = rgar($entry, '15');
    // $totalFloodplain = rgar($entry, '16');
    $timeStateFloodplain = rgar($entry, '40');
    $totalFloodplain = rgar($entry, '41');
    
    // $timeStateHazard = rgar($entry, '18');
    // $totalHazard = rgar($entry, '19');
    $timeStateHazard = rgar($entry, '43');
    $totalHazard = rgar($entry, '42');

    $areasExpertiseArray = array();
    $expertiseFields = array();
    
    $expertise1 = rgar($entry, '21.1');
    if($expertise1 && !empty($expertise1)) {
      $areasExpertiseArray[] = cleanAcf($expertise1);
    }
    
    $expertise2 = rgar($entry, '21.2');
    if($expertise2 && !empty($expertise2)) {
      $areasExpertiseArray[] = cleanAcf($expertise2);
    }
    
    $expertise3 = rgar($entry, '21.3');
    if($expertise3 && !empty($expertise3)) {
      $areasExpertiseArray[] = cleanAcf($expertise3);
    }
    
    $expertise4 = rgar($entry, '21.4');
    if($expertise4 && !empty($expertise4)) {
      $areasExpertiseArray[] = cleanAcf($expertise4);
    }
    
    $expertise5 = rgar($entry, '21.5');
    if($expertise5 && !empty($expertise5)) {
      $areasExpertiseArray[] = cleanAcf($expertise5);
    }
    
    $expertise6 = rgar($entry, '21.6');
    if($expertise6 && !empty($expertise6)) {
      $areasExpertiseArray[] = cleanAcf($expertise6);
    }
    
    $expertise7 = rgar($entry, '21.7');
    if($expertise7 && !empty($expertise7)) {
      $areasExpertiseArray[] = cleanAcf($expertise7);
    }
    
    $expertise8 = rgar($entry, '21.8');
    if($expertise8 && !empty($expertise8)) {
      $areasExpertiseArray[] = cleanAcf($expertise8);
    }
    
    $expertise9 = rgar($entry, '21.9');
    if($expertise9 && !empty($expertise9)) {
      $areasExpertiseArray[] = cleanAcf($expertise9);
    }
    
    $expertise11 = rgar($entry, '21.11');
    if($expertise11 && !empty($expertise11)) {
      $areasExpertiseArray[] = cleanAcf($expertise11);
    }
    
    $expertise12 = rgar($entry, '21.12');
    if($expertise12 && !empty($expertise12)) {
      $areasExpertiseArray[] = cleanAcf($expertise12);
    }
    
    $expertise13 = rgar($entry, '21.13');
    if($expertise13 && !empty($expertise13)) {
      $areasExpertiseArray[] = cleanAcf($expertise13);
    }
    
    $expertise14 = rgar($entry, '21.14');
    if($expertise14 && !empty($expertise14)) {
      $areasExpertiseArray[] = cleanAcf($expertise14);
    }
    
    $expertise15 = rgar($entry, '21.15');
    if($expertise15 && !empty($expertise15)) {
      $areasExpertiseArray[] = cleanAcf($expertise15);
    }
    
    $expertise16 = rgar($entry, '21.16');
    if($expertise16 && !empty($expertise16)) {
      $areasExpertiseArray[] = cleanAcf($expertise16);
    }
    
    $expertise17 = rgar($entry, '21.17');
    if($expertise17 && !empty($expertise17)) {
      $areasExpertiseArray[] = cleanAcf($expertise17);
    }
    
    $expertise18 = rgar($entry, '21.18');
    if($expertise18 && !empty($expertise18)) {
      $areasExpertiseArray[] = cleanAcf($expertise18);
    }
    
    $expertise19 = rgar($entry, '21.19');
    if($expertise19 && !empty($expertise19)) {
      $areasExpertiseArray[] = cleanAcf($expertise19);
    }
    
    $expertise21 = rgar($entry, '21.21');
    if($expertise21 && !empty($expertise21)) {
      $areasExpertiseArray[] = cleanAcf($expertise21);
    }
    
    $expertise22 = rgar($entry, '21.22');
    if($expertise22 && !empty($expertise22)) {
      $areasExpertiseArray[] = cleanAcf($expertise22);
    }
    
    $expertise23 = rgar($entry, '21.23');
    if($expertise23 && !empty($expertise23)) {
      $areasExpertiseArray[] = cleanAcf($expertise23);
    }
    
    $expertise24 = rgar($entry, '21.24');
    if($expertise24 && !empty($expertise24)) {
      $areasExpertiseArray[] = cleanAcf($expertise24);
    }
    
    $expertise25 = rgar($entry, '21.25');
    if($expertise25 && !empty($expertise25)) {
      $areasExpertiseArray[] = cleanAcf($expertise25);
    }
    
    $expertiseFieldsSerialized = serialize($expertiseFields);
    $expertiseFieldsSerializedAcf = serialize($areasExpertiseArray);
    
    $activitiesArray = array();
    $activitiesFields = array();
    
    $activities1 = rgar($entry, '22.1');
    if($activities1 && !empty($activities1)) {
      $activitiesArray[] = cleanAcf($activities1);
    }
    
    $activities2 = rgar($entry, '22.2');
    if($activities2 && !empty($activities2)) {
      $activitiesArray[] = cleanAcf($activities2);
    }
    
    $activities3 = rgar($entry, '22.3');
    if($activities3 && !empty($activities3)) {
      $activitiesArray[] = cleanAcf($activities3);
    }
    
    $activities4 = rgar($entry, '22.4');
    if($activities4 && !empty($activities4)) {
      $activitiesArray[] = cleanAcf($activities4);
    }
    
    $biography = rgar($entry, '23');
    $headshot = rgar($entry, '24');
    
    $user_id = wp_create_user($username, $password, $email);
    if(!is_wp_error($user_id)) {
      $user = get_user_by('id', $user_id);
      // MUST MATCH S2 LEVEL FOR MENTOR
      $user->set_role('s2member_level2');
      error_log("Returned User ID: ".$user_id, 0);
    } else {}
    
    wp_update_user(
      array(
        'ID' => $user_id,
        'user_email' => $workEmail,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'user_nicename' => $firstName,
        'display_name' => $lastName
      )
    );
    
    // wp_insert_post();
    $id = wp_insert_post(array(
      'post_title'=>$fullName,
      'post_type'=>'mentors',
      'post_content'=>'',
      'post_status'=>'draft',
      'post_parent'=>'',
      'post_author' => $user_id
    ));
    
    if($id) {
      error_log("Returned Post ID: ".$id."", 0);
      update_field('first_name', $firstName, $id);
      update_field('last_name', $lastName, $id);
      
      update_field('address_street', $address_street, $id);
      update_field('address_street_2', $address_street_2, $id);
      update_field('address_city', $address_city, $id);
      update_field('address_state', $address_state, $id);
      update_field('address_zip', $address_zip, $id);
      update_field('address_country', $address_country, $id);
      
      update_field('work_email', $workEmail, $id);
      update_field('work_phone', $workPhone, $id);
      update_field('cell_phone', $cellPhone, $id);
      
      update_field('cfm', $cfm, $id);
      
      update_field('fema_region', $femaRegion, $id);
      
      update_field('time_in_state_floodplain', $timeStateFloodplain, $id);
      update_field('total_time_in_floodplain', $totalFloodplain, $id);
      update_field('time_in_state_hazard', $timeStateHazard, $id);
      update_field('total_time_in_hazard', $totalHazard, $id);
      update_field('professional_biography', $biography, $id);
      
      update_field('mentoring_status', 'available', $id);
      
      // ATTACHEMENT CODE
      $filename = $headshot;
      $parent_post_id = $id;
      $filetype = wp_check_filetype( basename( $filename ), null );
      $attachment = array(
      	'guid'           => $filename, 
      	'post_mime_type' => $filetype['type'],
      	'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
      	'post_content'   => '',
      	'post_status'    => 'inherit',
      	'post_author'    => $user_id
      );
      $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
      update_field('headshot', $attach_id, $id);
      // END ATTACHEMENT CODE
      
      update_field('areas_of_expertise_filter', $areasExpertiseArray, $id);
      update_field('participating_activities', $activitiesArray, $id);
      update_field('state_filter', $address_state, $id);
			
			// ####################
			// ####################
			// HARDCODED FIELDS
      // FORCE MENTORING SUBNAV // USE OPTION ?
      update_field('sub_nav', "11", $id);
      // FORCE MENTORING PROJECT HOMEPAGE // USE OPTION ?
      update_field('project_homepage', "384", $id);
      update_field('hide_author', "1", $id);
      // FORCE LOGIN PAGE AND PROFILE PAGE // USE OPTION ? 
      update_field('show_login_in_sub_nav', "1", $id);
      update_field('login_page', "1184", $id);
      update_field('account_page', "443", $id);
      // ####################
    }
    
    
  } elseif($form["id"] == $mentee_form_id) {
    // MENTEE /////
    // s2member_level1 ////////////////////////////////////////
    ///////////////////////////////////////////////////////////
    
    // error_log("Form ID: ".$form["id"], 0);
    // $fullEntry = print_r($entry);
    
    // S2 MEMBER
    /////////////////
    
    $username = rgar($entry, '1');
    $password = rgar($entry, '4');
    
    // CUSTOM POST
    /////////////////
  
    $firstName = rgar($entry, '2.3');
    $lastName = rgar($entry, '2.6');
    $fullName = $firstName." ".$lastName;

    $address_street = rgar($entry, '9.1');
    $address_street_2 = rgar($entry, '9.2');
    $address_city = rgar($entry, '9.3');
    $address_state = rgar($entry, '9.4');
    $address_zip = rgar($entry, '9.5');
    $address_country = rgar($entry, '9.6');
  
    $workEmail = rgar($entry, '3');
    
    $workPhone = rgar($entry, '8');
    $cellPhone = rgar($entry, '13');
    
    $stateOfficial = rgar($entry, '19.1');
    error_log("stateOfficial IS: ", 0);
    error_log($stateOfficial, 0);
    if($stateOfficial && !empty($stateOfficial)) {
	    // $stateOfficial = true;
	    $stateOfficial = "Yes";
	    error_log("stateOfficial TRUE", 0);
    } else {
	    // $stateOfficial = false;
	    $stateOfficial = "No";
	    error_log("stateOfficial FALSE", 0);
    }
    
    $cfm = rgar($entry, '17.1');
    error_log("CFM IS: ", 0);
    error_log($cfm, 0);
    if($cfm && !empty($cfm)) {
	    // $cfm = true;
	    $cfm = "Yes";
	    error_log("CFM TRUE", 0);
    } else {
	    // $cfm = false;
	    $cfm = "No";
	    error_log("CFM FALSE", 0);
    }
    
    $agency = rgar($entry, '10');

    $user_id = wp_create_user($username, $password, $email);
    if(!is_wp_error($user_id)) {
      $user = get_user_by('id', $user_id);
      // MUST MATCH S2 LEVEL FOR MENTEE
      $user->set_role('s2member_level1');
      error_log("Returned User ID: ".$user_id, 0);
    } else {}
    
    wp_update_user(
      array(
        'ID' => $user_id,
        'user_email' => $workEmail,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'user_nicename' => $firstName,
        'display_name' => $lastName
      )
    );
    
    $id = wp_insert_post(array(
      'post_title'=>$fullName,
      'post_type'=>'mentees',
      'post_content'=>'',
      'post_status'=>'draft',
      'post_parent'=>'',
      'post_author'=> $user_id
    ));
    
    if($id) {
      error_log("Returned Post ID: ".$id."", 0);
      
      update_field('first_name', $firstName, $id);
      update_field('last_name', $lastName, $id);
      
      update_field('cfm', $cfm, $id);
      
      update_field('address_street', $address_street, $id);
      update_field('address_street_2', $address_street_2, $id);
      update_field('address_city', $address_city, $id);
      update_field('address_state', $address_state, $id);
      update_field('address_zip', $address_zip, $id);
      update_field('address_country', $address_country, $id);
      
      update_field('work_email', $workEmail, $id);
      update_field('work_phone', $workPhone, $id);
      update_field('cell_phone', $cellPhone, $id);
      
      update_field('state_official', $stateOfficial, $id);
      update_field('state_agency', $agency, $id);
    }

  } elseif($form["id"] == $login_form_id) {
    if(!session_id()) {
      session_start();
    }
    global $s2member_level3_login;
    
	  error_log("BEFORE CUSTOM LOGIN");
	  // error_log(print_r($entry['id'], true), 0);
	  // echo "<pre>".print_r($entry, true)."</pre>";
	  
    // CUSTOM LOGIN FORM
    // MUST PASS VALIDATION FIRST
    // get the username and pass
  	$username = $entry[1];
  	$pass = $entry[2];
  	$creds = array();
  	// create the credentials array
  	$creds['user_login'] = $username;
  	$creds['user_password'] = $pass;
  	
  	// DELETE ENTRY
  	$deleteResult = GFAPI::delete_entry($entry['id']);	
					
    $_SESSION['s2member_level3_login'] = true;
    
    // echo "<pre>".print_r("gform_after_submission", true)."</pre>";
    // echo "<pre>".print_r($_SESSION, true)."</pre>";
    
  	// sign in the user and set him as the logged in user
  	// error_log("BEFORE CUSTOM SIGNON");
  	$user = wp_signon($creds);
  	wp_set_current_user($user->ID);
  	
  	// error_log("AFTER CUSTOM LOGIN");
  } else {}
}

function redirect_mentor_admin() {
  //error_log('redirect_mentor_admin');
  if(!session_id()) {
    session_start();
  }
  
  $current_user = wp_get_current_user();
  
  // echo "<pre>".print_r("redirect_mentor_admin", true)."</pre>";
  // echo "<pre>".print_r($_SESSION, true)."</pre>";
  
  if($current_user && !empty($current_user)) {
    // echo "<pre>".print_r($current_user->roles, true)."</pre>";
    if(in_array('s2member_level3', $current_user->roles)) {
      if($_SESSION['s2member_level3_login']) {
        $_SESSION['s2member_level3_login'] = false;
        unset($_SESSION['s2member_level3_login']);
        // echo "<pre>".print_r(trim(get_home_url(), "/")."/products/state-mentoring-program/state-mentoring-admin", true)."</pre>";
        wp_redirect(trim(get_home_url(), "/")."/products/state-mentoring-program/state-mentoring-admin");
        exit();
      }
    }
  }
}
add_action('wp_loaded', 'redirect_mentor_admin');

// ################################################################################


add_filter( 'gform_save_field_value', 'save_field_value_func', 10, 4 );
function save_field_value_func( $value, $lead, $field, $form ) {
	error_log("gform_save_field_value", 0);
	
	$form_options = get_option( 'state_mentoring_option_name' );
  $ask_a_mentor_form_id = $form_options['ask_a_mentor_form_id'];
  
  if(absint($form->id) == $ask_a_mentor_form_id) {
	  /*
	  if($field->id == ) {
	  }
	  */
    return $value;
  } else {
	  return $value;
  }
  
  /*       
  //array of field ids to encode
  $encode_fields = array( 1, 2, 3 );
  //see if the current field id is in the array of fields to encode; encode if so, otherwise return unaltered value
  if ( in_array( $field->id, $encode_fields ) ) {
          return base64_encode( $value );
  } else {
          return $value;
  }
  */
}


function edit_user_profile_func($user) {
  // echo "<pre>".print_r($user, true)."</pre>";
  
  global $current_user;
  get_currentuserinfo();
  $author_query = array(
    'posts_per_page' => '1',
    'author' => $current_user->ID,
    'post_type' => 'mentors',
    'post_status' => 'any'
  );
  $author_posts = new WP_Query($author_query);
  $mentorPostId = "";
  while($author_posts->have_posts()) : $author_posts->the_post(); 
    $mentorPostId = get_the_id();
    break;
  endwhile;
  
  update_field('first_name', $current_user->user_firstname, $mentorPostId);
  update_field('last_name', $current_user->user_lastname, $mentorPostId);
  update_field('work_email', $current_user->user_email, $mentorPostId); 
  
  error_log("CURRENT WP EMAIL: ".$current_user->user_email, 0);
  // error_log("CURRENT WP FIRST NAME: ".$current_user->user_firstname, 0);
  // error_log("CURRENT WP LAST NAME: ".$current_user->user_lastname, 0);
}
// add_action ('ws_plugin__s2member_during_handle_profile_modifications', 'edit_user_profile_func');
add_action ('ws_plugin__s2member_after_handle_profile_modifications', 'edit_user_profile_func');


function my_pre_save_post($post_id) {
  error_log("RUNNING ACF PRE-SAVE POST", 0);
  error_log($post_id, 0);
  // echo "<pre>".print_r($_POST, true)."</pre>";
  // echo "<br />".$post_id;
  
  global $current_user;
  get_currentuserinfo();
  
  $post_type = get_post_type($post_id);
  if($post_type == "mentors" || $post_type == "mentees") {
    // $work_email = get_field('work_email', $post_id);
    /*
    wp_update_user(
      array(
        'ID' => $current_user->ID,
        'user_email' => $work_email,
        // 'first_name' => '',
        // 'last_name' => '',
        // 'user_nicename' => '',
        // 'display_name' => ''
      )
    );
    */
  }
  
  return $post_id;
}
add_filter('acf/pre_save_post' , 'my_pre_save_post', 10, 1 );


function my_save_post_ten($post_id) {
  // session_start();
  if(!session_id()) {
    session_start();
  }
  unset($_SESSION['toc_errors_1']);
  error_log("RUNNING ACF SAVE POST AT 10", 0);
  /*
  $thePost = print_r($_POST, true);
  $thePostAcf = print_r($_POST['acf'], true);
  error_log( print_r($_POST['acf'], true), 0);
  error_log("POST ACF");
  error_log($thePostAcf, 0);
	$thePostKeys = array_keys($_POST);
	foreach($thePostKeys as $item) {
		// error_log($item, 0);
	}
	*/
	$theContent = get_post_field('post_content', $post_id);
	// error_log("POST CONTENT", 0);
	// error_log(print_r($theContent, true), 0);
	$contentArr = explode("<!--nextpage-->", $theContent);
	// error_log(print_r(count($contentArr), true), 0);
	// error_log(" ", 0);
	// <!--nextpage-->
	
	// ####################
	
	$theContents = get_field('contents', $post_id);
	error_log("POST CONTENTS", 0);
	// error_log(print_r($theContents, true), 0);
	// error_log(" ", 0);
	
	// IF CONTENTS
	if($theContents && !empty($theContents)) {
		$segIndex = (int) 0; // 0 based
		$totalSeg = count($contentArr);
		error_log("Total Segments: ".$totalSeg, 0);
		
		// error_log("SEGMENTS", 0);
		// error_log(print_r($contentArr[$segIndex], true), 0);

    $contentsErr = false;
    $errText = "";
    $errAnchors = array();
		
		$l1i = 1; // 1 based
		foreach($theContents as $key => $value) {
			// LEVEL 1
			error_log("LEVEL 1 ##### INDEX: ".$l1i, 0);
			error_log("L1 Key: ".print_r($key, true));
			error_log("L1 Link Title: ".print_r($value['link_title'], true));
			error_log("L1 Anchor ID: ".print_r($value['anchor_id'], true));
			error_log("L1 Page Number: ".print_r($value['page_number'], true));
			
			// FIND ANCHOR ID
      // $l1Target = '<a id="'.$value['anchor_id'].'">';
      $l1Target = 'id="'.$value['anchor_id'].'"';
			// foreach($contentArr as $key => $segement) {}
			error_log("SEARCHING WITH: ".$l1Target, 0);
			// error_log("SEARCHING IN: ", 0);
			// error_log($contentArr[$segIndex], 0);
			
			// $thisResult = strpos($contentArr[$segIndex], $l1Target);
			// error_log("thisResult", 0);
			// error_log($thisResult, 0);
			
			for (; ; ) {
				if($segIndex > $totalSeg) {
					$segIndex = 0;
          $contentsErr = true;
          array_push($errAnchors, $value['anchor_id']);
          error_log("NOT FOUND: ".$value['anchor_id']);
          update_sub_field( array('contents', $l1i, 'page_number'), null, $post_id);	
					break;
				}
				
				if(strpos($contentArr[$segIndex], $l1Target) !== false) {
          $theContent = str_replace($l1Target, $l1Target.' class="body-anchor"', $theContent);
					$pageNum = (int) $segIndex + 1;
					update_sub_field( array('contents', $l1i, 'page_number'), $pageNum, $post_id);	
					error_log("ATTEMPTING SAVE WITH: ".$l1Target, 0);
					break;
				} else {
					$segIndex++;
					error_log("SEG INDEX INCREMENTED", 0);
				}	
			}
			
			// UPDATE PAGE NUMBER	
			// update_sub_field( array('contents', $l1i, 'link_title'), 'test index: '.$l1i, $post_id);
			
			// LEVEL 2
			if(isset($value['sub_section_1']) && !empty($value['sub_section_1'])) {
				error_log("LEVEL 2 #####", 0);
				$level2 = $value['sub_section_1'];
				$l2i = 1; // 1 based
				foreach($level2 as $key => $value) {
					error_log("L2 Key: ".print_r($key, true));
					error_log("L2 Link Title: ".print_r($value['link_title'], true));
					error_log("L2 Anchor ID: ".print_r($value['anchor_id'], true));
					error_log("L2 Page Number: ".print_r($value['page_number'], true));
					
					// FIND ANCHOR ID
					// $l2Target = '<a id="'.$value['anchor_id'].'">';
          $l2Target = 'id="'.$value['anchor_id'].'"';
					// foreach($contentArr as $key => $segement) {}
					error_log("SEARCHING WITH: ".$l2Target, 0);
					
					for (; ; ) {
						if($segIndex > $totalSeg) {
							$segIndex = 0;
              $contentsErr = true;
              array_push($errAnchors, $value['anchor_id']);
              error_log("NOT FOUND: ".$value['anchor_id']);
              update_sub_field( array('contents', $l1i, 'sub_section_1', $l2i, 'page_number'), null, $post_id);
							break;
						}
						if(strpos($contentArr[$segIndex], $l2Target) !== false) {
              $theContent = str_replace($l2Target, $l2Target.' class="body-anchor"', $theContent);
							$pageNum = (int) $segIndex + 1;
							update_sub_field( array('contents', $l1i, 'sub_section_1', $l2i, 'page_number'), $pageNum, $post_id);
							error_log("ATTEMPTING SAVE WITH: ".$l2Target, 0);
							break;
						} else {
							$segIndex++;
							error_log("SEG INDEX INCREMENTED", 0);
						}	
					}

					// UPDATE PAGE NUMBER	
					// update_sub_field( array('contents', $l1i, 'sub_section_1', $l2i, 'link_title'), 'test index: '.$l2i, $post_id);
					
					// LEVEL 3
					if(isset($value['sub_section_2']) && !empty($value['sub_section_2'])) {
						error_log("LEVEL 3 #####", 0);
						$level3 = $value['sub_section_2'];
						$l3i = 1; // 1 based
						foreach($level3 as $key => $value) {
							error_log("L3 Key: ".print_r($key, true));
							error_log("L3 Link Title: ".print_r($value['link_title'], true));
							error_log("L3 Anchor ID: ".print_r($value['anchor_id'], true));
							error_log("L3 Page Number: ".print_r($value['page_number'], true));
													
							// FIND ANCHOR ID
							// $l3Target = '<a id="'.$value['anchor_id'].'">';
              $l3Target = 'id="'.$value['anchor_id'].'"';
							// foreach($contentArr as $key => $segement) {}
							error_log("SEARCHING WITH: ".$l3Target, 0);
							
							for (; ; ) {
								if($segIndex > $totalSeg) {
									$segIndex = 0;
                  $contentsErr = true;
                  array_push($errAnchors, $value['anchor_id']);
                  error_log("NOT FOUND: ".$value['anchor_id']);
                  update_sub_field( array('contents', $l1i, 'sub_section_1', $l2i, 'sub_section_2', $l3i, 'page_number'), null, $post_id);
									break;
								}
								if(strpos($contentArr[$segIndex], $l3Target) !== false) {
                  $theContent = str_replace($l3Target, $l3Target.' class="body-anchor"', $theContent);
									$pageNum = (int) $segIndex + 1;
									update_sub_field( array('contents', $l1i, 'sub_section_1', $l2i, 'sub_section_2', $l3i, 'page_number'), $pageNum, $post_id);
									error_log("ATTEMPTING SAVE WITH: ".$l3Target, 0);
									break;
								} else {
									$segIndex++;
									error_log("SEG INDEX INCREMENTED", 0);
								}	
							}
							
							// UPDATE PAGE NUMBER	
							// update_sub_field( array('contents', $l1i, 'sub_section_1', $l2i, 'sub_section_2', $l3i, 'link_title'), 'test index: '.$l3i, $post_id);
							
							$l3i++;	
						}	
					}						
					$l2i++;	
				}
			}
			$l1i++;
		}

    // UPDATE POST  
    $update_post = array(
      'ID' => $post_id,
      'post_content' => $theContent
    );
    wp_update_post($update_post);
	}

  if($contentsErr && !empty($errAnchors)) {
    error_log("SETTING TRANSIENT: ".'toc_errors_'.$post_id, 0);
    error_log(print_r($errAnchors, true), 0);
    set_transient('toc_errors', $errAnchors);
    $_SESSION['toc_errors_1'] = $errAnchors;
  } else {
    delete_transient('toc_errors');
    unset($_SESSION['toc_errors_1']);
  }
	
  $post_type = get_post_type($post_id);
  if($post_type == "mentors" || $post_type == "mentees") {
    $work_email = get_field('work_email', $post_id);
  }

  return $post_id;
}
add_filter('acf/save_post' , 'my_save_post_ten', 10, 1 );


function admin_notice__toc_error() {
  error_log("admin_notice__toc_error", 0);

  $transient_toc_errors = get_transient('toc_errors');
  error_log(print_r($transient_toc_errors, true), 0);

  $session_toc_errors = $_SESSION['toc_errors_1'];
  if(isset($session_toc_errors) && !empty($session_toc_errors)) {
    $class = 'notice notice-error';
    $message = 'Warning: The following anchors have been defined in the Table of Contents but do not occur in the content: "'.implode(", ", $transient_toc_errors).'" The Table of Contents will contain incorrect links.';
    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
  }
  unset($_SESSION['toc_errors_1']);
}
add_action( 'admin_notices', 'admin_notice__toc_error' );


function my_save_post_twenty($post_id) {
  error_log("RUNNING ACF SAVE POST AT 20", 0);

  return $post_id;
}
add_filter('acf/save_post' , 'my_save_post_twenty', 20, 1 );


add_filter('gform_validation', 'gform_validate_action');
function gform_validate_action($validation_result) {
  $form = $validation_result['form'];
  //error_log("Running validation: Validation: Form ID: ".$form['id'], 0);
  
  $form_options = get_option( 'state_mentoring_option_name' );
  $mentor_form_id = $form_options['mentor_form_id'];
  $mentee_form_id = $form_options['mentee_form_id'];
  $request_mentoring_form_id = $form_options['request_mentoring_form_id'];
  $login_form_id = $form_options['login_form_id'];
  
  if($form['id'] == $mentor_form_id) {
    // error_log("Running Mentor Validation", 0);
    // $validation_result['is_valid'] = true;
  
    foreach($form['fields'] as &$field) {
      if($field['id'] == 1) {
        // error_log("Checking username", 0);
        $form_username = rgpost( "input_{$field['id']}" );
        if(username_exists($form_username)) {
          $validation_result['is_valid'] = false;
          $field->failed_validation = true;
          $field->validation_message = 'This username is already in use.';
          // error_log("Username in use", 0);
        } else {
          if(!$form_username || empty($form_username)) {
            $validation_result['is_valid'] = false;
            $field->failed_validation = true;
          }
        }
        // error_log("Username: ".$form_username."", 0);    
      }
      if($field['id'] == 10) {
        // error_log("Checking email", 0);
        $form_email = rgpost( "input_{$field['id']}" );
        if(email_exists($form_email)) {
          $validation_result['is_valid'] = false;
          $field->failed_validation = true;
          $field->validation_message = 'This email is already in use.';
          // error_log("Email in use", 0);
        } else {
          if(!$form_email || empty($form_email)) {
            $validation_result['is_valid'] = false;
            $field->failed_validation = true;
          }
        }
        // error_log("Email: ".$form_email."", 0);    
      }
    }
    
    $validation_result['form'] = $form;
    return $validation_result;
    
  } elseif($form['id'] == $mentee_form_id) {
    // $validation_result['is_valid'] = true;
  
    foreach($form['fields'] as &$field) {
      if($field['id'] == 1) {
        $form_username = rgpost( "input_{$field['id']}" );
        if(username_exists($form_username)) {
          $validation_result['is_valid'] = false;
          $field->failed_validation = true;
          $field->validation_message = 'This username is already in use.';
          // error_log("Username in use.", 0);
        } else {
          if(!$form_username || empty($form_username)) {
            $validation_result['is_valid'] = false;
            $field->failed_validation = true;
          }
        }
        // error_log("Username: ".$form_username."", 0);    
      }
      if($field['id'] == 3) {
        $form_email = rgpost( "input_{$field['id']}" );
        if(email_exists($form_email)) {
          $validation_result['is_valid'] = false;
          $field->failed_validation = true;
          $field->validation_message = 'This email is already in use.';
          // error_log("Email in use.", 0);
        } else {
          if(!$form_email || empty($form_email)) {
            $validation_result['is_valid'] = false;
            $field->failed_validation = true;
          }
        }
        // error_log("Email: ".$form_email."", 0);    
      }      
    }
    
    $validation_result['form'] = $form;
    return $validation_result;
    
  } elseif($form['id'] == $login_form_id) {
    // VALIDATION FOR CUSTOM LOGIN FORM
    error_log("Running validation for CUSTOM LOGIN.", 0);
    
    $validation_result['is_valid'] = true;    
  	global $user;

    foreach($form['fields'] as &$field) {

      // error_log($field['cssClass'], 0);
      
      // validate username
    	if($field['cssClass'] === 'username') {
      	// error_log(rgpost("input_{$field['id']}"), 0);
    		$user = get_user_by('login', rgpost("input_{$field['id']}"));
    		if(empty($user->user_login)) {
      		error_log("FAILED USERNAME", 0);
    			$validation_result["is_valid"] = false;
    			$validation_result["message"] = "Invalid username provided.";
    			$field->failed_validation = true;
    			$field->validation_message = "Invalid username provided.";
    		}
    	}
    	// validate password
    	if($field['cssClass'] === 'password') {
      	// error_log(rgpost("input_{$field['id']}"), 0);
    		if(!$user or !wp_check_password(rgpost("input_{$field['id']}"), $user->data->user_pass, $user->ID)) {
      		error_log("FAILED PASSWORD", 0);
    			$validation_result["is_valid"] = false;
    			$validation_result["message"] = "Invalid password provided.";
    			$field->failed_validation = true;
    			$field->validation_message = "Invalid password provided.";
    		}
    	}
      
    }

    $validation_result['form'] = $form;
    return $validation_result;
    
  } elseif($form['id'] == $request_mentoring_form_id) {
	  
		$update_entry_id = rgpost('input_11');
		error_log("update_entry_id raw: ".rgpost('input_11'), 0 );
    if($update_entry_id) {
	    
	    global $current_user;
			get_currentuserinfo();
	    
	    // GET REQUEST MENTORING SUBMISSIONS BY CURRENT USER
      $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
      $entries = GFAPI::get_entries($request_mentoring_form_id, $search_criteria);
	    
	    if($update_entry_id && !empty($update_entry_id) && $entries && !empty($entries)) {
		    $validMatch = false;
        foreach($entries as $entry) {
	      	if($entry['id'] == $update_entry_id) {
		      	// USER HAS ACCESS TO POST ID
		      	$validMatch = true;
		      	$validation_result["is_valid"] = true;
		  			return $validation_result;
		    	}
		  	}
		  	if(!$validMatch) {
				  $validation_result["is_valid"] = false;
	  			$validation_result["message"] = "An error has occurred.";
	  			$form['fields'][0]->failed_validation = true;
	  			$form['fields'][0]->validation_message = 'An error has occurred.';
	  			$validation_result['form'] = $form;
	  			return $validation_result;	
		  	}
		  } else {
			  $validation_result["is_valid"] = false;
  			$validation_result["message"] = "An error has occurred.";
  			$form['fields'][0]->failed_validation = true;
  			$form['fields'][0]->validation_message = 'An error has occurred.';
  			$validation_result['form'] = $form;
  			return $validation_result;
		  }
    } else {
	    // $validation_result["is_valid"] = true;
		  return $validation_result;
    } 
	} else {
    return $validation_result;
  }

}


function my_facetwp_result_count( $output, $params ) {
    // $output = $params['lower'] . '-' . $params['upper'] . ' of ' . $params['total'] . ' results';
    // return $output;
    return $params['total'];
}
add_filter( 'facetwp_result_count', 'my_facetwp_result_count', 10, 2 );


// STATE MENTORING SETTINGS
class stateMentoringSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'State Mentoring Settings', 
            'State Mentoring Settings', 
            'manage_options', 
            'state-mentoring-form-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'state_mentoring_option_name' );
        ?>
        <div class="wrap">   
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'state_mentoring_option_group' );   
                do_settings_sections( 'state-mentoring-form-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'state_mentoring_option_group', // Option group
            'state_mentoring_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'State Mentoring Form Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'state-mentoring-form-admin' // Page
        );  

        add_settings_field(
            'mentor_form_id', // ID
            'Mentor Form ID', // Title 
            array( $this, 'mentor_form_id_callback' ), // Callback
            'state-mentoring-form-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'mentee_form_id', 
            'Mentee Form ID', 
            array( $this, 'mentee_form_id_callback' ), 
            'state-mentoring-form-admin', 
            'setting_section_id'
        );
        
        add_settings_field(
            'request_mentoring_form_id', 
            'Request Mentoring Form ID', 
            array( $this, 'request_mentoring_form_id_callback' ), 
            'state-mentoring-form-admin', 
            'setting_section_id'
        );
        
        add_settings_field(
            'ask_a_mentor_form_id', 
            'Ask A Mentor Form ID', 
            array( $this, 'ask_a_mentor_form_id_callback' ), 
            'state-mentoring-form-admin', 
            'setting_section_id'
        );
        
        add_settings_field(
            'login_form_id', 
            'Login Form ID', 
            array( $this, 'login_form_id_callback' ), 
            'state-mentoring-form-admin', 
            'setting_section_id'
        );
        
        add_settings_field(
            'dashboard_url_id', 
            'Dashboard URL', 
            array( $this, 'dashboard_url_id_callback' ), 
            'state-mentoring-form-admin', 
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['mentor_form_id'] ) )
            $new_input['mentor_form_id'] = absint( $input['mentor_form_id'] );
            
        if( isset( $input['mentee_form_id'] ) )
            $new_input['mentee_form_id'] = absint( $input['mentee_form_id'] );
        
        if( isset( $input['request_mentoring_form_id'] ) )
            $new_input['request_mentoring_form_id'] = absint( $input['request_mentoring_form_id'] );
            
        if( isset( $input['ask_a_mentor_form_id'] ) )
            $new_input['ask_a_mentor_form_id'] = absint( $input['ask_a_mentor_form_id'] );

        if( isset( $input['login_form_id'] ) )
            $new_input['login_form_id'] = absint( $input['login_form_id'] );
        
        if( isset( $input['dashboard_url_id'] ) )
            $new_input['dashboard_url_id'] = sanitize_text_field( $input['dashboard_url_id'] );    

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function mentor_form_id_callback()
    {
        printf(
            '<input type="text" id="mentor_form_id" name="state_mentoring_option_name[mentor_form_id]" value="%s" />',
            isset( $this->options['mentor_form_id'] ) ? esc_attr( $this->options['mentor_form_id']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function mentee_form_id_callback()
    {
        printf(
            '<input type="text" id="mentee_form_id" name="state_mentoring_option_name[mentee_form_id]" value="%s" />',
            isset( $this->options['mentee_form_id'] ) ? esc_attr( $this->options['mentee_form_id']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function request_mentoring_form_id_callback()
    {
        printf(
            '<input type="text" id="request_mentoring_form_id" name="state_mentoring_option_name[request_mentoring_form_id]" value="%s" />',
            isset( $this->options['request_mentoring_form_id'] ) ? esc_attr( $this->options['request_mentoring_form_id']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ask_a_mentor_form_id_callback()
    {
        printf(
            '<input type="text" id="ask_a_mentor_form_id" name="state_mentoring_option_name[ask_a_mentor_form_id]" value="%s" />',
            isset( $this->options['ask_a_mentor_form_id'] ) ? esc_attr( $this->options['ask_a_mentor_form_id']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function login_form_id_callback()
    {
        printf(
            '<input type="text" id="login_form_id" name="state_mentoring_option_name[login_form_id]" value="%s" />',
            isset( $this->options['login_form_id'] ) ? esc_attr( $this->options['login_form_id']) : ''
        );
    }
    
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function dashboard_url_id_callback()
    {
        printf(
            '<div class="option-tooltip">Use relative URL and remove trailing <strong>"/"</strong></div><input type="text" id="dashboard_url_id" name="state_mentoring_option_name[dashboard_url_id]" value="%s" />',
            isset( $this->options['dashboard_url_id'] ) ? esc_attr( $this->options['dashboard_url_id']) : ''
        );
    }
}
if( is_admin() ) {
  $my_settings_page = new stateMentoringSettings();
}
