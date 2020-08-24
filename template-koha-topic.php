<?php
/**
 * Template Name: Koha Topic Template
 */
?>
<?php
  session_start();
?>
<?php while (have_posts()) : the_post(); ?>
<?php // get_template_part('templates/page', 'header'); ?>
<?php get_template_part('templates/subnav'); ?>

<?php
  if(isset($_GET['id']) && $_GET['id'] != "") {
    $topicTag = $_GET['id'];
    
    if(isset($_GET['topic']) && $_GET['topic'] != "") {
      $topicName = $_GET['topic'];
    } else {
      $topicName = "";
    }
    
    // get record data
    $record = koha_get_data_func(
      array(
        'action' => 'topic',
        'topicTag' => $topicTag
      )
    );
    $records = array_filter($record);
    
    if(empty($records)) {
      // no record found
      unset($records);
    }
  } else {
    // no record specified
    unset($records);
  } ?>


<div class="container clearfix results-list-wrap">
  <div class="col-xs-12">
      
    <h2 class=""><?php echo ucwords(strtolower($topicName)); ?></h2>
    <ul class="results-list">
      <?php
      foreach($records as $item) { ?>
        <li>
          <div class="clearfix">
            
            <!-- 
            <div class="col-md-12">
              <a href="item?id=<?php echo $item['bibId']; ?>" class="title"><?php echo $item['title']; ?> <?php echo $item['subTitle']; ?></a>
            </div>
            -->
            
            <div class="col-md-9">
              
              <?php
                if($item['electro'] && $item['electro'] != "") {
                  // Electronic
                  $itemUrl = $item['electro'];
                  echo '<a href="'.$itemUrl.'" class="title" target="_BLANK">'.$item['title'].' '.$item['subTitle'].'</a>';
                  
                } else {
                  $itemUrl = 'item?id='.$item['bibId'];
                  echo '<a href="'.$itemUrl.'" class="title">'.$item['title'].' '.$item['subTitle'].'</a>';
                  
                }
                
                
              if($item['author'] && !empty($item['author'])) {
              // if(true) {  
              ?>
                <p><strong>Author</strong>: <?php echo $item['author']; ?></p>
              <?php
              }

              if($item['date'] && !empty($item['date'])) {
              // if(true) {  
              ?>
                <p><strong>Date</strong>: <?php echo $item['date']; ?></p>
              <?php
              }
              
              if($item['publisher'] && !empty($item['publisher'])) {
              // if(true) {  
              ?>
                <p><strong>Publisher</strong>: <?php echo $item['publisher']; ?></p>
              <?php
              }
              
              // if($item['note'] && !empty($item['note'])) {
              if(false) {  
              ?>
                <p><strong>Note</strong>: <?php echo $item['note']; ?></p>
              <?php
              }
              
              if($item['summary'] && !empty($item['summary'])) {
              // if(true) {  
              ?>
                <p><strong>Summary</strong>: <?php echo $item['summary']; ?></p>
              <?php
              }
              
              // if($item['bibId'] && !empty($item['bibId'])) {
              if(false) {  
              ?>
                <p><strong>ID</strong>: <?php echo $item['bibId']; ?></p>
              <?php
              } ?>
            </div>
            
          
            <div class="col-md-3">
              <?php
              // if($item['imgUrl']) {
              if(true) {
              ?>
                <div class="full-width"><img class="full-width" src="<?php echo $item['imgUrl']; ?>" /></div>
              <?php
              }
              ?>
            </div>
          
          </div>
        </li>
      <?php
      } ?>
    </ul>
    
  <?php 
    //print_r($searchArr); ?>
    
  </div>
</div>


<?php /*    
  <div class="container clearfix">
    <div class="col-xs-12">
      <?php get_template_part('templates/content', 'page'); ?>
    </div>
  </div>
*/ ?>

<?php endwhile; ?>