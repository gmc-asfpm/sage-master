<div class="sidr" id="sidr-left" style="display: none;">
  <div class="sidr-left-close">
  </div>
  
  <ul class="primary-nav-mobile">
    <li><a href="/">Home</a></li>
  </ul>
  
  <?php
    if (has_nav_menu('mobile_navigation')) :
      wp_nav_menu(['theme_location' => 'mobile_navigation', 'menu_class' => 'primary-nav-mobile']);
    endif;
  ?>  
</div>

<header class="banner">
  
  <div class="mobile-search">
    <div class="mobile-search-close"></div>
    
    <div class="container clearfix">
      <div class="col-xs-12">
        
        <form class="mobile-search-form" role="form" method="get" action="/">
          <div class="input-group">
            <input name="s" class="form-control" placeholder="" type="text">
            <span class="input-group-addon mobile-search-submit"><button type="submit" style="padding: initial!important; border: inherit!important; color: inherit!important ;margin: inherit!important;"><i class="fa fa-search"></i></button></span>
          </div>
        </form>
      
      </div>
    </div>
  
  </div>
  
  <div class="mobile-header">
    <div class="menu-icon" id="mobile-menu">
      <i class="fa fa-bars"></i>
    </div>
    <div class="search-icon">
      <i class="fa fa-search"></i>
    </div>
  </div>
  
  <div class="top-header">
    <div class="container">
      
      <div class="clearfix">
        
        <div class="text-left col-md-4 col-sm-5">
          <span class="asfpm-title"><a href="/">Association of State Floodplain Managers</a></span>
        </div>
        
        <div class="text-right col-md-8 col-sm-7">
          <ul class="top-header-tools clearfix">
            
            <li class="has-form">

              <form class="top-search" role="form" method="get" action="/">
                <div class="input-group">
                  <input name="s" class="form-control" placeholder="" type="text">
                  <span class="input-group-addon"><button type="submit" style="padding: initial!important; border: inherit!important; color: inherit!important ;margin: inherit!important;"><i class="fa fa-search"></i></button></span>
                </div>
              </form>
            
            </li>
            <?php /*
            <li><a href="https://twitter.com/FloodsOrg"><i class="fa fa-twitter"></i></a></li>
            <li><a href="https://www.facebook.com/ASFPM/"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#">Jobs</a></li>
            <li><a href="#">Become a Member</a></li>
            */ ?>
          </ul>
        </div>
        
      </div>
      
    </div>
  </div><!-- .top-header -->
    
    
  <div class="primary-header">
    <div class="container">
      
      <div class="clearfix">
        <div class="col-md-2 col-sm-2 logo-wrap">
      
          <a class="brand" href="<?= esc_url(home_url('/')); ?>">
            <img class="site-logo" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/logo.png" />
          </a>
          
        </div>
        
        <div class="col-md-10 col-sm-10 nav-primary-wrap">
          
          <nav class="navbar navbar-default nav-primary">

            <?php /* <div class="container-fluid"> */ ?>
            <div class="">

              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <!-- <a class="navbar-brand" href="#">MENU</a> -->
                <!-- <span class="navbar-brand" href="#">MENU</span> -->
              </div>
          
              <?php /* <div class="collapse navbar-collapse" id="bs-navbar-collapse-1"> */ ?>
              <div class="" id="bs-navbar-collapse-1">
                <?php
                if (has_nav_menu('primary_navigation')) :
                  wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav navbar-right']);
                endif;
                ?>                    
              </div>
              
            </div>
            
          </nav>
        
        </div>
      </div>
      
    </div>
  </div><!-- .primary-header -->

</header>
