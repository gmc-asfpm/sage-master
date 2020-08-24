<?php
  // error_log("", 0); 
  if(get_field('project_homepage') || get_field('sub_nav')) {
    $project_homepage = get_field('project_homepage');
    ?>
    
    <div class="landing-header-position">
    
      <div class="landing-header">
        <div class="container">
          
          <div class="clearfix">

              <?php
              if($project_homepage && !empty($project_homepage) && $project_homepage != "") { ?>
                <div class="col-md-2 sub-nav-title clearfix">
                      
                  <div class="sub-nav-title-inset clearfix">
                    <?php /* <a href="<?php echo $project_homepage->guid; ?>"><?php echo $project_homepage->post_title; ?></a> */ ?>
										<?php
											$project_homepage_permalink = get_permalink($project_homepage->ID);
										?>
                    <a href="<?php echo $project_homepage_permalink; ?>"><?php echo $project_homepage->post_title; ?></a>
                  </div>
                
                </div>
              <?php  
              } else { ?>
                <div class="col-md-2 clearfix">
                </div>
              <?php
              }
              ?>
            
            <div class="col-md-10 sub-nav-nav">
  
              <?php
              if(get_field('sub_nav') && !empty(get_field('sub_nav'))) { ?>
                
                <?php
                  if(get_field('show_login_in_sub_nav') && get_field('account_page')) { ?>
                
                    <ul id="sub-nav-login" class="nav sub-nav">
                      <li id="" class="menu-item menu-item-type-custom menu-item-object-custom">
                        <?php
                          if(is_user_logged_in()) {
	                        	/*  
														if(isset($project_homepage_permalink)) {
															$logoutUrl = $project_homepage_permalink."?mentor_logout=true";
														} else {
															$logoutUrl = wp_logout_url()."?mentor_logout=true";
														}
														*/
													?>
                            <?php /* <a href="<?php echo $logoutUrl; ?>">Log Out</a> */ ?>
                            
                            <?php
	                            if(get_field('account_page')) { ?>
																<a href="<?php echo get_field('account_page'); ?>">My Profile</a>
															<?php
															} ?>
                          <?php
                          } else {   
			                      $login_page = get_field('login_page');
			                      if(empty($login_page)) {
				                      $login_page = wp_login_url();
			                      } ?>
                            <a href="<?php echo $login_page; ?>">Log In</a>
                          <?php
                          } ?>
                      </li>
                    </ul>
                  
                  <?php
                  } ?>
                
                <?php
                  function my_nav_wrap() {
                    // default value of 'items_wrap' is <ul id="%1$s" class="%2$s">%3$s</ul>'
                    // open the <ul>, set 'menu_class' and 'menu_id' values
                    $wrap  = '<ul id="%1$s" class="%2$s">';
                    // get nav items as configured in /wp-admin/
                    $wrap .= '%3$s';
                    //
                    // the static link 
                    $wrap .= '<li class="my-static-link"><a href="#">My Static Link</a></li>';
                    //
                    // close the <ul>
                    $wrap .= '</ul>';
                    // return the result
                    return $wrap;
                    
                    /*
	                    * // 'items_wrap' => my_nav_wrap()
	                    */
                  }

                  wp_nav_menu(array(
                    'menu' => get_field('sub_nav'),
                    'menu_class' => 'nav sub-nav',
                    'reverse' => true
                    // 'items_wrap' => my_nav_wrap()
                  ));
                ?>
                  
                <?php 
                  if($GLOBALS['curret_nav_title']) {
                    // echo $GLOBALS['curret_nav_title'];  
                  } else {
                  }
                ?>
                
                <?php
                  if(get_field('sub_nav')) {
                    $menuArr = wp_get_nav_menu_items( get_field('sub_nav') );
                    // print_r($menuArr);
                  }
                ?>

              <?php
              } ?>

            </div>


            <?php
              if(get_field('sub_nav')) {
                // $menuArr = array_reverse( wp_get_nav_menu_items(get_field('sub_nav')) );
                $menuArr = wp_get_nav_menu_items(get_field('sub_nav'));
                
                global $post;
                $postId = $post->ID;
                // print_r($menuArr); ?>
                
                <div class="mobile-sub-nav-select-wrap">
                  <select class="sub-nav-select">
                    <?php
                    foreach($menuArr as $item) {
                      if($postId == $item->object_id) {
                        $current = "selected";
                      } else {
                        $current = "";
                      } ?>
                      <option <?php echo $current; ?> value="<?php echo $item->url; ?>"><?php echo $item->title; ?></option>
                    <?php
                    } ?>
                    
                    <?php /*
                    <?php
                      if(is_user_logged_in()) { ?>
                        <option value="<?php echo wp_logout_url(); ?>">Log Out</option>
                      <?php
                      } else {	                      
												$login_page = get_field('login_page');
	                      if(empty($login_page)) {
		                      $login_page = wp_login_url();
	                      }
                      ?>
                        <option value="<?php echo $login_page; ?>">Log In</option>
                      <?php
                      } ?>
                    */ ?>
                    
                    
                    <?php // ?>
                    	
                    	<?php
                        if(is_user_logged_in()) {
                        	/*  
													if(isset($project_homepage_permalink)) {
														$logoutUrl = $project_homepage_permalink."?mentor_logout=true";
													} else {
														$logoutUrl = wp_logout_url()."?mentor_logout=true";
													}
													*/
												?>
                          <?php /* <a href="<?php echo $logoutUrl; ?>">Log Out</a> */ ?>
                          
                          <?php
                            if(get_field('account_page')) { ?>
															<option value="<?php echo get_field('account_page'); ?>">My Profile</option>
														<?php
														} ?>
                        <?php
                        } else {   
		                      $login_page = get_field('login_page');
		                      if(empty($login_page)) {
			                      $login_page = wp_login_url();
		                      } ?>
                          <option value="<?php echo $login_page; ?>">Log In</option>
                        <?php
                        } ?>
                    	
                    <?php // ?>
                    
                    
                  </select>
                </div>
                
              <?php
              }
            ?>


          </div>
          
        </div>
      </div><!-- .landing-header -->


    </div>
    
    
  <?php
  } ?>
  
  <?php
  if(get_field('koha_wp_tag')) {
    $koha_wpTag = get_field('koha_wp_tag');
  } else {
    unset($koha_wpTag);
  }
?>

<div class="container clearfix">
  <div class="col-xs-12">
    <?php
      if(isset($existing_breadcrumbs) && !empty($existing_breadcrumbs)) {  
        echo $existing_breadcrumbs;
      } else {
        if(function_exists('yoast_breadcrumb')) {
          yoast_breadcrumb('<div id="breadcrumbs">','</div>');
        }  
      }      
    ?>
  </div>
</div>




<?php // TEST ?>
<?php
  /*
  wp_nav_menu([
    'menu' => get_field('sub_nav'),
    'menu_class' => 'nav sub-nav',
    'container' => ''
  ]);
  */
?>

