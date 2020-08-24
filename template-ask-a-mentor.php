<?php
/**
 * Template Name: Ask A Mentor Template
 */
?>

<?php
	if(isset($_GET['mentor']) && !empty($_GET['mentor'])) {
		// echo "<pre>".print_r($_GET['mentor'], true)."</pre>";
		$mentorId = $_GET['mentor'];
		// echo $mentorId;
		
		global $current_user;
    get_currentuserinfo();
    
    $mentor_query = array(
      'posts_per_page' => '1',
      'p' => $mentorId,
      'post_type' => array('mentors'),
      'post_status' => 'any'
    );
    
    $mentor_post = new WP_Query($mentor_query);
    while($mentor_post->have_posts()) : $mentor_post->the_post(); 
			$mentorFirstName = get_field('first_name');
			$mentorLastName = get_field('last_name');
			$mentorEmail = get_field('work_email');
      break;
    endwhile;

		// echo $mentorEmail."<br />";		
	}
?>

<?php while (have_posts()) : the_post(); ?>
  
  
	<?php
	  if($mentorEmail && !empty($mentorEmail)) { ?>
		  <?php get_template_part('templates/page', 'header'); ?>
		  
	  	<div class="container">
				<div class="">
					<div class="col-xs-12">
						
						<div class="page-content">
							<div>
								<h2>Asking: <strong><?php echo $mentorFirstName." ".$mentorLastName; ?></strong></h2>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<?php get_template_part('templates/content', 'page'); ?>
	  <?php
	  } else {
		  // ERROR
	  }
	?>
    
  
<?php endwhile; ?>
