<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      get_template_part('templates/header');
    ?>
    <?php /* <div class="wrap container" role="document"> */ ?>
    <div class="wrap" role="document">
      <div class="content">
        <?php /* <main class="main"> */ ?>
        <main class="">
          <div class="">
            <?php include Wrapper\template_path(); ?>
          </div>
        </main><!-- /.main -->
        
        <?php /*
        <?php if (Setup\display_sidebar()) : ?>
          <aside class="sidebar">
            <?php include Wrapper\sidebar_path(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
        */ ?>
        
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>
    <?php
	    $envHost = (string) $_SERVER['HTTP_HOST'];
	    if(strpos($envHost, 'www.floodsciencecenter.org') !== false) { ?>
	    	<script>
					(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');				
					ga('create', 'UA-97649997-1', 'auto');
					ga('send', 'pageview');
				</script>
		  <?php
	    } else { ?>
		    <!-- host: <?php echo $envHost; ?> -->
			<?php
	    } ?>
  </body>
</html>
