<?php
?>
<?php get_template_part('templates/subnav'); ?>

<?php use Roots\Sage\Titles; ?>

<div class="page-header-position">
  
  <? /* CONTENTS */ ?>
  <?php
    if(get_field('contents') && !empty(array_filter(get_field('contents')))) {
      $contents_style = get_field('contents_style');
      $contents_style_class = "numerical-alpha-roman";
      if($contents_style == "numerical_alpha_roman" || $contents_style == "" || empty($contents_style)) {
        $contents_style_class = "numerical-alpha-roman";
      } elseif($contents_style == "disc_circle_square") {
        $contents_style_class = "disc-circle-square";
      }
      $contents = get_field('contents');
      ?>
      
      <div class="side-contents <?php echo $contents_style_class; ?>">
        
        <div class="side-target">
          <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/contents.svg" />
        </div>
        
        <div class="side-close">
          <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/contents-close.svg" />
        </div>
        
        <div class="side-content">
          <div class="side-content-inset">

            <?php
              // GET CURRENT PAGE NUMBER
              $page = (get_query_var('page')) ? get_query_var('page') : 1;
            ?>

            <ul>
            <?php
              // print_r($contents);
              foreach($contents as $anc1) { ?>
                <li>
                  <?php
                    if($page == 1 && $anc1['page_number'] == $page) {
                      $anc1Page = "#";
                      // $anc1Page = $anc1['page_number']."/#";
                    } else {
                      $anc1Page = $anc1['page_number']."/#";
                    }
                  ?>
                  <a href="<?php echo get_permalink( $post->ID )."".$anc1Page.$anc1['anchor_id']; ?>"><?php echo $anc1['link_title']; ?></a>
                  <?php
                    if($anc1['sub_section_1'] && !empty(array_filter($anc1['sub_section_1']))) { ?>
                      <ul>
                      <?php  
                      foreach($anc1['sub_section_1'] as $anc2) { ?>
                        <?php
                          if($page == 1 && $anc2['page_number'] == $page) {
                            $anc2Page = "#";
                            // $anc2Page = $anc2['page_number']."/#";
                          } else {
                            $anc2Page = $anc2['page_number']."/#";
                          }
                        ?>
                        <li>
                          <a href="<?php echo get_permalink( $post->ID )."".$anc2Page.$anc2['anchor_id']; ?>"><?php echo $anc2['link_title']; ?></a>
                          <?php
                            if($anc2['sub_section_2'] && !empty(array_filter($anc2['sub_section_2']))) { ?>
                              <ul>
                              <?php
                              foreach($anc2['sub_section_2'] as $anc3) { ?>
                                <?php
                                  if($page == 1 && $anc3['page_number'] == $page) {
                                    $anc3Page = "#";
                                    // $anc3Page = $anc3['page_number']."/#";
                                  } else {
                                    $anc3Page = $anc3['page_number']."/#";
                                  }
                                ?>
                                <li>
                                  <a href="<?php echo get_permalink( $post->ID )."".$anc3Page.$anc3['anchor_id']; ?>"><?php echo $anc3['link_title']; ?></a>  
                                </li>
                              <?php
                              } ?>
                              </ul>
                            <?php
                            } ?>
                        </li>
                      <?php
                      } ?>
                      </ul>
                    <?php  
                    }
                  ?>
                </li>
              <?php
              }
            ?>
            
            </ul>
          </div>
        </div>
      </div>
      <script type="text/javascript">
	      jQuery(document).ready(function() {
	      });
      </script>
    <?php
    }  
  ?>
  <?php /* END CONTENTS */ ?>
  
  <div class="container clearfix">
    <div class="col-xs-12">
            
      <div class="page-header">
        <h1 class="page-title"><?= Titles\title(); ?></h1>

        <?php
          if(!get_field('hide_author')) {
            if(get_the_author() && !is_search() && !is_home() && !is_front_page()) { ?>
              <p class="author-name"><?php echo get_the_author(); ?></p>
            <?php 
            }
          } ?>        
      </div>
  
    </div>
  </div>
  
</div>