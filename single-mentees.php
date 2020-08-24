<?php // MENTEES ?>

<div class="container clearfix">
  <div class="col-xs-12">
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

    <br />
    
    <?php // echo do_shortcode( '[s2Member-Profile /]' ); ?>
    
  </div>
</div>

<?php // get_template_part('templates/content-single', get_post_type()); ?>
