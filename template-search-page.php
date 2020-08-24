<?php
/**
 * Template Name: Search Page Template
 */
?>
<?php
  session_start();

	$searchAll = false;
	$query = null;
	
	
	// CHECK GET SHOWALL
	if(isset($_GET['showall'])) {
		// error_log("SHOWALL VALUE: ".$_POST['showall'], 0);
		if($_GET['showall']) {
			if($_GET['showall'] == "true") {
				$searchAll = true;
			}
		} else {
		}
	} else {
	}
	
	// CHECK FOR GET QUERY
  if(isset($_GET['query']) && !empty($_GET['query'])) {
	  // error_log("EARLY QUERY IS: ".$_GET['query']);
	  // CHECK AGAIN FOR SEARCH ALL QUERIES
	  if($_GET['query'] == "Search All" || $_GET['query'] == "*") {
		  $searchAll = true;
	  } else {
			$query = $_GET['query'];
	  }
	}
	
	// CHECK FOR GET COLLECTION SET
  if(isset($_GET['set']) && !empty($_GET['set'])) {
    $wpTag = $_GET['set'];
  } else {
    $wpTag = false;
  }
  
  /*
	// CHECK FOR GET ADDITIONAL QUERY ?
  if(isset($_GET['set']) && !empty($_GET['set'])) {
    $wpTag = $_GET['set'];
  } else {
    $wpTag = false;
  }
  */
  
  //
  
  // CHECK POST SHOWALL
	if(isset($_POST['showall'])) {
		// error_log("SHOWALL VALUE: ".$_POST['showall'], 0);
		if($_POST['showall']) {
			if($_POST['showall'] == "true") {
				$searchAll = true;
			}
		} else {
		}
	} else {
	}
  
	// CHECK POST QUERY
  if(isset($_POST['query']) && !empty($_POST['query'])) {
	  // error_log("EARLY QUERY IS: ".$_POST['query']);
	  // CHECK AGAIN FOR SEARCH ALL QUERIES
	  if($_POST['query'] == "Search All" || $_POST['query'] == "*") {
		  $searchAll = true;
	  } else {
			$query = $_POST['query'];
	  }
	}

	// CHECK FOR POST COLLECTION SET
  if(isset($_POST['set']) && !empty($_POST['set'])) {
    $wpTag = trim($_POST['set']);
    $wpTag = explode(" ", $wpTag);
  } else {
    if(!$wpTag) {
      $wpTag = false;
    }
  }

  // IF SEARCHALL OR QUERY, DO SEARCH
  if($searchAll || $query) {
		if($searchAll) {
			$query = "*";
		}

	  $searchArr = koha_get_data_func(
	    array(
	      'action' => 'search',
	      'query' => html_entity_decode($query),
	      'wpTag' => $wpTag
	    )
	  );
	  
	  $query = trim(stripslashes($query) ,'"');
	  error_log("QUERY IS: ".$query, 0);
	  
	  $searchArr = array_filter($searchArr);
	} else {
    // NO SEARCH
    $query = null;
  }
  
  if(get_field('koha_wp_tag')) {
    $koha_wpTag = get_field('koha_wp_tag');
  } else {
    unset($koha_wpTag);
  }
  
  if(get_field('koha_wp_tag_array')) {
    $koha_wpTagArray = get_field('koha_wp_tag_array');
    $koha_wpTagArrayStr = "";
    foreach($koha_wpTagArray as $item) {
      $koha_wpTagArrayStr .= "".trim($item['koha_wp_tag'])." ";
    }
    $koha_wpTagArrayStr = trim($koha_wpTagArrayStr);
    //echo "<pre>".print_r($koha_wpTagArrayStr, true)."</pre>";
  } else {
    unset($koha_wpTagArrayStr);
  }
  
  if(get_field('search_box_placeholder_text')) {
    $search_placeholder = get_field('search_box_placeholder_text');
  } else {
    $search_placeholder = "Enter Search Terms";
  }
?> 

<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php get_template_part('templates/subnav'); ?>

<div class="container clearfix search-header">
  <h1 class="search-heading"><?php echo get_field('page_title'); ?></h1>
  
  <!-- ### -->
  <?php
    $searchTooltip = trim(get_field('search_tooltip'));
    if(isset($searchTooltip) && !empty($searchTooltip)) { ?>
      <div class="search_tooltip page-content">
        <?php echo $searchTooltip; ?>
      </div>
      <?php
    } 
  ?>
  <!-- ### -->
    
  <?php
    $topicMenuArr = get_field('topics_menu');
    // echo "<pre>".print_r($topicMenuArr[0]['links'][0], true)."</pre>";
    // echo "<pre>".print_r($topicMenuArr['links'], true)."</pre>";
    if(isset($topicMenuArr[0]['links'][0]) && !empty($topicMenuArr[0]['links'][0])) {
      $isTopics = TRUE;
    } else {
      unset($topicMenuArr);
      $isTopics = FALSE;
    }
  ?>
  <div class="search-topics-wrap">
    
    <div class="col-md-9 clearfix col-centered search-form-wrap">
      
      <?php
        if($isTopics) { ?>
      <div class="col-md-9 col-sm-9 clearfix left">
        <?php
        } else { ?>
      <div class="col-md-12 col-sm-12 clearfix left">  
        <?php  
        } ?>
        
        <div class="inset">
          
          <i class="fa fa-search"></i>
          
          <div class="input-wrap">
            <form method="POST" id="search-form">
	            <?php
		            if($searchAll) {
			          	$queryText = "";  
		            } else {
			            $queryText = $query;
		            }
	            ?>
              <input type="text" name="query" id="search-query" value="<?php if($query) { echo $queryText; } ?>" placeholder="<?php echo $search_placeholder; ?>" />
              <?php /* <input type="hidden" name="set" id="search-set" value="<?php echo $koha_wpTag; ?>"> */ ?>
              <input type="hidden" name="set" id="search-set" value="<?php echo $koha_wpTagArrayStr; ?>">
              <input type="hidden" name="showall" id="search-showall" value="false">
            </form>
          </div>
          
          <img class="go submit-form" src="<?php echo get_template_directory_uri(); ?>/assets/images/go.svg" />
          
        </div>

      </div>
      
      <?php
        if($isTopics) { ?>
      
		      <div class="col-md-3 col-sm-3 clearfix right">
		        <div class="inset">
		          <a class="view-topics">View Topics</a>
		        </div>
		      </div>

        <?php
        } else { ?>
        <?php  
        } ?>

    </div>
    
    <div class="col-xs-12 clearfix search-view-topics">
      <div class="inset clearfix">
        <div class="close-x"></div>
        
        <?php
          foreach ($topicMenuArr as $topic) { ?>
          
            <div class="col-md-3">
              <?php
                if($topic['list_title'] && !empty($topic['list_title'])) { ?>
                  <h2><?php echo $topic['list_title']; ?></h2>
                <?php
                } ?>
              <ul>
                <?php
                  foreach ($topic['links'] as $link) { ?>
                    <li>
                      <?php
                        /*
                        if($link['topic_id'] && !empty($link['topic_id'])) { ?>
                          <a href="topic?id=<?php echo $link['topic_id']; ?>&topic=<?php echo $link['topic_name']; ?>"><?php echo $link['link_text']; ?></a>
                        <?php
                        }
                        */
                      ?>
                      <?php
                        if($link['additional_search_text'] && !empty($link['link_text'])) { ?>
                          <a href="?query=<?php echo $link['additional_search_text']; ?>&set=<?php echo $topic['topic_wp_tag']; ?>"><?php echo $link['link_text']; ?></a>
                        <?php
                        }
                      ?>
                    </li>
                  <?php
                  } ?>
              </ul>
            </div>
          
          <?php
          }
        ?>
        
      </div>
    </div>
    
    <div class="col-md-9 col-centered clearfix">
	    <br />
	    <script type="text/javascript">
		    function showAllResults() {
					jQuery('#search-form #search-showall').val(true);
					jQuery('#search-form').submit();
		    }
		    function clearResults() {

		    }
	    </script>
			<a class="button" onclick="showAllResults();">SHOW ALL RESULTS</a>
			<?php
				if($query) { ?>
					<a class="button" href="?clear_results=true" onclick="clearResults();">CLEAR RESULTS</a>
				<?php
				} ?>
    </div>
    
    
  </div>
</div> 


<?php
  if($query) {
    
    if(!empty($searchArr)) { ?>
    
      <div class="container clearfix results-list-wrap">
        <div class="col-xs-12">
          
          <?php
	          if($searchAll) { ?>
		          <h2 class="">All Results</h2>
		        <?php
	          } else { ?>
		      		<h2 class="">Results</h2>
		      	<?php
	          } ?>

          <ul class="results-list">
            <?php
            foreach($searchArr as $item) { ?>
              <li>
                <div class="clearfix">
                  
                  <!-- 
                  <div class="col-md-12">
                    <a href="item?id=<?php echo $item['bibId']; ?>" class="title"><?php echo $item['title']; ?> <?php echo $item['subTitle']; ?></a>
                  </div>
                  -->
                  
                  
                  <?php
                    if($item['imgUrl'] && !empty($item['imgUrl'])) { ?>
                    
                      <div class="col-md-3">
	                      <?php // full-width ?>
                        <div class="full-width"><img class="max-width clearfix" src="<?php echo $item['imgUrl']; ?>" /></div>
                        <br />
                      </div>
                    
                    <div class="col-md-9">
                    <?php  
                    } else { ?>
                    
                    <div class="col-md-12">
                    <?php
                    }
                    
                      if(trim($item['electro']) && !empty($item['electro'])) {
                        // Electronic
                        $itemUrl = $item['electro'];
                        echo '<a href="'.$itemUrl.'" class="title" target="_BLANK">'.$item['title'].' '.$item['subTitle'].'</a>';
                        
                      } else {
                        $itemUrl = 'item?id='.$item['bibId'];
                        echo '<a href="'.$itemUrl.'" class="title">'.$item['title'].' '.$item['subTitle'].'</a>';
                      }
                      
                      if($item['type'] && !empty($item['type']) && $item['type'] != "") {
                        $linkText = $item['type'];
                      } else {
                        $linkText = "Read More";
                      }
                      
                      
                    if($item['date']) {
                    // if(true) {  
                    ?>
                      <p><strong>Date</strong>: <?php echo $item['date']; ?></p>
                    <?php
                    }
                    
                    if($item['author']) {
                    // if(true) {  
                    ?>
                      <p><strong>Author</strong>: <?php echo $item['author']; ?></p>
                    <?php
                    }
                    
                    if($item['publisher']) {
                    // if(true) {  
                    ?>
                      <p><strong>Publisher</strong>: <?php echo $item['publisher']; ?></p>
                    <?php
                    }
                    
                    if($item['note']) {
                    // if(false) {  
                    ?>
                      <p><strong>Note</strong>: <?php echo $item['note']; ?></p>
                    <?php
                    }
                    
                    if($item['summary']) {
                    // if(true) {  
                    ?>
                      <p><strong>Summary</strong>: <?php echo $item['summary']; ?></p>
                    <?php
                    }
                    
                    // if($item['bibId']) {
                    if(false) {  
                    ?>
                      <p><strong>ID</strong>: <?php echo $item['bibId']; ?></p>
                    <?php
                    }
                    
                    // if($item['lastUpdated']) {
                    if(false) {  
                    ?>
                      <p><strong>Updated</strong>: <?php echo $item['lastUpdated']; ?></p>
                    <?php
                    }
                    
                    
                    // if($item['topic']) {
                    if($item['topicArr'] && !empty($item['topicArr'])) {  
                    ?>
                      <?php
                        // echo "<pre>".print_r($item['topicArr'], true)."</pre>";
                        /*
                        foreach($item['topicArr'] as $topic) {
                          echo "<p>".$topic."</p><br />";
                        }
                        */
                      ?>
                      <?php /* <!-- <p><strong>Topic</strong>: <?php echo $item['topic']; ?></p> --> */ ?>
                      <p><strong>Keywords</strong>: <?php echo trim(implode(", ", $item['topicArr']), ", "); ?></p>
                    <?php
                    } ?>
                    
                    
                    <?php
                      if($itemUrl && !empty($itemUrl)) { ?>
                        <?php echo '<br /><a href="'.$itemUrl.'" class="button" target="_blank">'.$linkText.'</a>'; ?>
                      <?php  
                      } else { ?>
                      <?php  
                      }
                    ?>
              
                  </div>
                  
                
                  <!-- -->
                
                </div>
              </li>
            <?php
            } ?>
          </ul>
          
        <?php 
          //print_r($searchArr); ?>
          
        </div>
      </div>

    <?php  
    } else { ?>
    
    <div class="container clearfix">
      <div class="col-xs-12">
        <h2>No results</h2>
      </div>
    </div>
    
    <?php
    }
    
  } else {


    $topicArr = get_field('topics');
    if($topicArr && !empty($topicArr)) {


      $count = 1;
      foreach ($topicArr as $topic) { ?>
        
        <div class="container clearfix">
          <div class="col-xs-12">
            <h2 class="search-topic-title"><?php echo $topic['title']; ?></h2>
          </div>
        </div>
        
        <div class="container clearfix">
          <div class="col-xs-12 search-slider-wrap">
            
              <?php
                $total = count($topic['slider_boxes']);
              ?>
              <ul class="bxslider bxslider-<?php if($count > 4) { echo 'min'; } else { echo 'max'; } ?>">
                <?php
                  foreach ($topic['slider_boxes'] as $box) {
                    
                    $boxData = koha_get_data_func(
                      array(
                        'action' => 'single',
                        'id' => $box['koha_record']
                      )
                    );
  
                    if($boxData[0]['electro'] && $boxData[0]['electro'] != "") {
                      // Electronic
                      $itemUrl = $boxData[0]['electro'];
                      
                      if($boxData[0]['type'] && !empty($boxData[0]['type'])) {
                        $linkText = $boxData[0]['type'];
                      } else {
                        $linkText = "Read More";
                      }
                    } else {
                      $itemUrl = 'item?id='.$boxData[0]['bibId'];
                    }
                    
                    // $itemUrl
                    if($box['external_link'] && !empty($box['external_link'])) {
                      $itemUrl = $box['external_link'];
                    }
                    
                    // $linkText
                    if($box['link_text'] && !empty($box['link_text'])) {
                      $linkText = $box['link_text'];
                    }
                  ?>
                  	
                    <li class="<?php echo $box['koha_record']; ?>">
                    
                    	<a target="_blank" class="cleafix" style="display: block;" href="<?php echo $itemUrl; ?>">
	                    	
                    		<?php /* onclick='window.location = "<?php echo $itemUrl; ?>"' */ ?>
                        <div class="search-slider-box">

                          <?php /* onclick="window.open('<?php echo $itemUrl; ?>');" */ ?>
                          <div class="search-slider-img img-cover" style="background-image: url(<?php echo $box['image']['url']; ?>);">
                            <?php /* <img src="<?php echo $box['image']['url']; ?>" /> */ ?>
                            <?php /*
                            <a class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></a>
                            */ ?>
                            <?php
	                            if($box['image']['caption'] && !empty($box['image']['caption'])) { ?>
	                            	<span class="img-attr" data-toggle="tooltip" data-placement="top" title="<?php echo $box['image']['caption']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/attribution.svg" alt="<?php echo $box['image']['alt']; ?>" /></span>
		                          <?php
	                            } else { ?>
	                            <?php
															} ?>
                          </div>

                          <div class="slider-inset">
                            <div class="slider-title">
                              <?php
                                // echo '<a href="'.$itemUrl.'" target="_blank">'.$boxData[0]['title'].'</a>';
                              ?>
                                <?php
                                  if(strlen($boxData[0]['title']) >= 48) {
                                    $truncatedTitle = trim(substr($boxData[0]['title'], 0, 48))."...";
                                    echo $truncatedTitle;  
                                  } else {
                                    echo $boxData[0]['title'];
                                  }
                                ?>
                              
                              <div class="subtitle">
                                <!-- <a target="_blank" href="<?php echo $itemUrl; ?>"> -->
                                  <?php
                                    $subTitle = ucfirst($boxData[0]['subTitle']);
                                    if(strlen($subTitle) >= 120) {
                                      $truncatedCaption = trim(substr($subTitle, 0, 120))."...";
                                      echo $truncatedCaption;  
                                    } else {
                                      echo $subTitle;
                                    }
                                  ?>
                                  <?php // echo ucfirst($boxData[0]['subTitle']); ?>
                                <!-- </a> -->
                              </div>
                            </div>
                            
                            <div class="slider-caption">
                              <?php
                              ?>
                            </div>
                            
                            <div>  
                              <?php // echo '<a href="'.$itemUrl.'" class="slider-link" target="_blank">'.$linkText.'</a>'; ?>
                              <!-- <a href="<?php echo $itemUrl; ?>" class="slider-link" target="_blank"> -->
                              <span class="slider-link">
	                            	<?php echo $linkText; ?>
                              </span>
	                            <!-- </a> -->
                            </div>
                          </div>
                        </div>
                        
                    	</a>

                    </li>
                     
                  <?php
                  }
                ?>
              </ul>
          </div>
        </div>
        <?php
        $count++;
      }
    }
  }    
?>

<script type="text/javascript">
  jQuery(document).ready(function() {
    
    jQuery('.bxslider-max').bxSlider({
      minSlides: 1,
      maxSlides: 4,
      slideMargin: 5,
      autoReload: true,
      infiniteLoop: false,
      hideControlOnEnd: true,
      breaks: [{screen:0, slides:1, pager:true},{screen:460, slides:2, pager:true},{screen:768, slides:2, pager:true},{screen:990, slides:4, pager:true}]
    });
    
    jQuery('.bxslider-min').bxSlider({
      minSlides: 1,
      maxSlides: 4,
      slideMargin: 5,
      autoReload: true,
      infiniteLoop: false,
      hideControlOnEnd: true,
      breaks: [{screen:0, slides:1, pager:false},{screen:460, slides:2, pager:false},{screen:768, slides:2, pager:false},{screen:990, slides:4, pager:false}]
    });
    
    jQuery("a.view-topics, .close-x").click(function() {
      jQuery(".search-topics-wrap").toggleClass("show-topics");
    });
    
    jQuery(".submit-form").click(function() {
      jQuery("#search-form").submit();  
    });
    
    jQuery(".img-attr").click(function(e) {
	    e.preventDefault();
	    e.stopPropagation();
    })
    
  });
</script>


<?php /*    
  <div class="container clearfix">
    <div class="col-xs-12">
      <?php get_template_part('templates/content', 'page'); ?>
    </div>
  </div>
*/ ?>

<?php endwhile; ?>