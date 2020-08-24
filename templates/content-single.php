<?php /*
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
*/ ?>


<?php // get_template_part('templates/subnav'); ?>

<?php use Roots\Sage\Titles; ?>

<div class="page-header-position">
  
  <? /* CONTENTS */ ?>
  <?php
    if(get_field('contents') && !empty(array_filter(get_field('contents')))) {
      $contents = get_field('contents');
      ?>
      <div class="side-contents">
        
        <div class="side-target">
          <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/contents.svg" />
        </div>
        
        <div class="side-close">
          <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/contents-close.svg" />
        </div>
        
        <div class="side-content">
          <div class="side-content-inset">
            <ul>
            <?php
              // print_r($contents);
              
              foreach($contents as $anc1) { ?>
                <li>
                  <a href="<?php echo get_permalink( $post->ID )."".$anc1['page_number']."/#".$anc1['anchor_id']; ?>"><?php echo $anc1['link_title']; ?></a>
                  <?php
                    if($anc1['sub_section_1'] && !empty(array_filter($anc1['sub_section_1']))) { ?>
                      <ul>
                      <?php  
                      foreach($anc1['sub_section_1'] as $anc2) { ?>
                        <li>
                          <a href="<?php echo get_permalink( $post->ID )."".$anc2['page_number']."/#".$anc2['anchor_id']; ?>"><?php echo $anc2['link_title']; ?></a>
                          <?php
                            if($anc2['sub_section_2'] && !empty(array_filter($anc2['sub_section_2']))) { ?>
                              <ul>
                              <?php
                              foreach($anc2['sub_section_2'] as $anc3) { ?>
                                <li>
                                  <a href="<?php echo get_permalink( $post->ID )."".$anc3['page_number']."/#".$anc3['anchor_id']; ?>"><?php echo $anc3['link_title']; ?></a>  
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
    <?php
    }  
  ?>
  <? /* END CONTENTS */ ?>
  
  
  
  
  <div class="container clearfix">
    <div class="col-xs-12">
            
      <div class="page-header">
        <h1 class="page-title"><?= Titles\title(); ?></h1>
        
        <?php if(get_the_author() && !is_search() && !is_home() && !is_front_page()) { ?>
          <p class="author-name"><?php echo get_the_author(); ?></p>
        <?php  
        } ?>
        
      </div>
  
    </div>
  </div>
  
</div>

<?php if(get_the_post_thumbnail_url()) { ?>
<div class="container clearfix">
  <div class="col-xs-12 text-center">
    <img class="max-width" src="<?php echo get_the_post_thumbnail_url();?>" />
  </div>
</div>
<?php
} ?>


<div class="container clearfix">
  <div class="col-xs-12">
    <div class="page-content">
      <?php the_content(); ?>
    </div>
  </div>
</div>


<div class="container clearfix">
  <div class="col-xs-12">
    
    <div class="paginated-nav">
      <?php
        wp_link_pages(
          array(
        		'before'           => '',
        		'after'            => '&nbsp;',
        		'link_before'      => '<span>',
        		'link_after'       => '</span>',
        		'next_or_number'   => 'next',
        		'separator'        => '',
        		'nextpagelink'     => '',
        		'previouspagelink' => '<',
        		'echo'             => 1
          )
        );
        
        wp_link_pages(
          array(
        		'before'           => '',
        		'after'            => '',
        		'link_before'      => '<span>',
        		'link_after'       => '</span>',
        		'next_or_number'   => 'number',
        		'separator'        => ' <span class="separator">&bull;</span> ',
        		'nextpagelink'     => '',
        		'previouspagelink' => '',
        		'pagelink'         => '%',
        		'echo'             => 1
          )
        );
        
        wp_link_pages(
          array(
        		'before'           => '&nbsp;',
        		'after'            => '',
        		'link_before'      => '<span>',
        		'link_after'       => '</span>',
        		'next_or_number'   => 'next',
        		'separator'        => '',
        		'nextpagelink'     => '>',
        		'previouspagelink' => '',
        		'echo'             => 1
          )
        );
      ?>
    </div>

  </div>
</div>
