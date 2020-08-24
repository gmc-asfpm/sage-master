
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
        		//'link_before'      => '<span class="page-nav-left">',
        		//'link_after'       => '</span>',

        		'link_before'      => '',
        		'link_after'       => '',
        		
        		'next_or_number'   => 'next',
        		'separator'        => '',
        		'nextpagelink'     => '',
        		'previouspagelink' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
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
        		//'link_before'      => '<span class="page-nav-right">',
        		//'link_after'       => '</span>',
        		
        		'link_before'      => '',
        		'link_after'       => '',
        		
        		'next_or_number'   => 'next',
        		'separator'        => '',
        		'nextpagelink'     => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
        		'previouspagelink' => '',
        		'echo'             => 1
          )
        );
      ?>
    </div>

  </div>
</div>
