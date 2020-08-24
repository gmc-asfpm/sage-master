<?php get_template_part('templates/subnav'); ?>
<?php // MENTORS ?>
<?php
  $theId = get_the_id();
  $theMeta = get_post_meta($theId);
  // print_r($theMeta);
?>

<div class="container clearfix">
  <div class="">
    <?php
      // echo do_shortcode( '[iscorrect]' . $text_to_be_wrapped_in_shortcode . '[/iscorrect]' );
      // ob_start();
      // ob_get_clean();
      // if( current_user_can('editor') ) {}
      
      /*
      [s2If current_user_is(s2member_level2)]
        Some premium content for Level 2 Members.
      [/s2If]
      
      [s2If current_user_is(s2member_level1)]
          Some premium content for Level 1 Members.
      [/s2If]
      */
    ?>
    
    <?php /*
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
        
        if( current_user_is('s2member_level3') ||  current_user_is('administrator')  ) {
          echo "You are admin";
        }
      ?>
    </div>
    */ ?>
    
    <?php // echo do_shortcode( '[s2Member-Profile /]' ); ?>
    
    <div class="mentor-profile clearfix">
      
      <div class="col-sm-4 col-xs-12 mentor-profile-img">
        <?php $headshot = get_field('headshot'); ?>
        <img src="<?php echo $headshot['url']; ?>" />
        
        <br />
        
        <h1 class="visible-xs">
          <?php the_field('first_name'); ?> <?php the_field('last_name'); ?>
        </h1>
        
        <?php
          $status = get_field('mentoring_status');
          if($status && !empty($status)) {
            echo '<h4 class="visible-xs">Status: <strong>'.ucfirst($status).'</strong></h4>';
          }
        ?>
        
        <br />
        
        <?php
          $street_address = get_field('address_street');
          
          if($street_address && !empty($street_address)) { ?>
            <div>
              <h4>Address</h4>
                <?php echo $street_address; ?><br />
                
                <?php
                  $street_address2 = get_field('address_street2');
                  if($street_address2 && !empty($street_address2)) { ?>
                    <?php echo $street_address2; ?><br />
                  <?php  
                  } ?>
                  
                <?php
                  $address_city = get_field('address_city');
                  if($address_city && !empty($address_city)) {
                    echo $address_city;
                  } 
                  $address_state = get_field('address_state');
                  if($address_state && !empty($address_state)) {
                    echo ", ".$address_state; ?><br />
                  <?php  
                  } ?>
                  
                <?php
                  $address_zip = get_field('address_zip');
                  if($address_zip && !empty($address_zip)) { ?>
                    <?php echo $address_zip; ?><br />
                  <?php  
                  } ?>
                
                <?php
                  $address_country = get_field('address_country');
                  if($address_country && !empty($address_country)) { ?>
                    <?php echo $address_country; ?><br />
                  <?php  
                  } ?>
            </div>
            <br />
          <?php
          } ?>
        
        
        <?php
          $work_email = get_field('work_email');
          if($work_email && !empty($work_email)) { ?>          
            <div>
              <h4>Work Email</h4>
              <a href="mailto:<?php echo $work_email; ?>"><?php echo $work_email; ?></a>
            </div>
            <br />
          <?php  
          } ?>
        
        
        <?php
          $work_phone = get_field('work_phone');
          if($work_phone && !empty($work_phone)) { ?>          
            <div>
              <h4>Work Phone</h4>
              <a href="tel:<?php echo $work_phone; ?>"><?php echo $work_phone; ?></a>
            </div>
            <br />
          <?php  
          } ?>
          
        
        <?php
          $cell_phone = get_field('cell_phone');
          if($cell_phone && !empty($cell_phone)) { ?>          
            <div>
              <h4>Cell Phone</h4>
              <a href="tel:<?php echo $cell_phone; ?>"><?php echo $cell_phone; ?></a>
            </div>
            <br />
          <?php  
          } ?>
        
      </div>
      
      <div class="col-sm-8 col-xs-12 mentor-profile-name">
        <h1 class="hidden-xs">
          <?php the_field('first_name'); ?> <?php the_field('last_name'); ?>
        </h1>
        <?php
          $status = get_field('mentoring_status');
          if($status && !empty($status)) {
            echo '<h4 class="hidden-xs">Status: <strong>'.ucfirst($status).'</strong></h4>';
          }
        ?>
          
        <br />  

        <div>
          <h4>Areas of Expertise</h4>
          <ul class="exp-list">
            <?php
              $expArr = get_field('areas_of_expertise_filter');
              foreach($expArr as $item) {
                echo "<li>".$item."</li>";
              }
            ?>
          </ul>
        </div>
        <br />
        
        <?php /* */ ?>
        <div>
          <h4>Participating Activities</h4>
          <ul class="exp-list">
            <?php
              $actArr = get_field('participating_activities');
              foreach($actArr as $item) {
                echo "<li>".$item."</li>";
              }
            ?>
          </ul>
        </div>
        <br />
        <?php /* */ ?>
        
        <?php
          $fema_region = get_field('fema_region');
          if($fema_region && !empty($fema_region)) { ?>
            <div>
              <h4>FEMA Region</h4>
              <?php echo $fema_region; ?>
            </div>
            <br />
          <?php  
          } ?>
          
        <?php
          $time_in_state_floodplain = get_field('time_in_state_floodplain');
          if($time_in_state_floodplain && !empty($time_in_state_floodplain)) { ?>
            <div>
              <h4>Time Worked in State Floodplain Management</h4>
              <?php echo nl2br($time_in_state_floodplain); ?>
            </div>
            <br />
          <?php  
          } ?>
          
        <?php
          $total_time_in_floodplain = get_field('total_time_in_floodplain');
          if($total_time_in_floodplain && !empty($total_time_in_floodplain)) { ?>
            <div>
              <h4>Total Time in Floodplain Management</h4>
              <?php echo nl2br($total_time_in_floodplain); ?>
            </div>
            <br />
          <?php  
          } ?>
          
        <?php
          $time_in_state_hazard = get_field('time_in_state_hazard');
          if($time_in_state_hazard && !empty($time_in_state_hazard)) { ?>
            <div>
              <h4>Time Worked in State Hazard Mitigation</h4>
              <?php echo nl2br($time_in_state_hazard); ?>
            </div>
            <br />
          <?php  
          } ?>
          
        <?php
          $total_time_in_hazard = get_field('total_time_in_hazard');
          if($total_time_in_hazard && !empty($total_time_in_hazard)) { ?>
            <div>
              <h4>Total Time in Hazard Mitigation</h4>
              <?php echo nl2br($total_time_in_hazard); ?>
            </div>
            <br />
          <?php  
          } ?>
        
        <?php
          $professional_biography = get_field('professional_biography');
          if($professional_biography && !empty($professional_biography)) { ?>
            <div>
              <h4>Professional Biography</h4>
              <?php echo nl2br($professional_biography); ?>
            </div>
            <br />
          <?php  
          } ?>
                  
      </div>
      
    </div>
    
  </div>
</div>
  
<?php // get_template_part('templates/content-single', get_post_type()); ?>