<?php
/**
 * Template Name: Koha Item Template
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
    $bibId = $_GET['id'];
    
    // get record data
    $record = koha_get_data_func(
      array(
        'action' => 'single',
        'id' => $bibId
      )
    );
    $record = array_filter($record);
    
    if(empty($record)) {
      // no record found
      unset($record);
    }
  } else {
    // no record specified
    unset($record);
  } ?>  
  
<div class="container clearfix single-record-wrap">
  <div class="col-xs-12">
    
      
      <?php
        if($record) {
          // display record data
          // print_r($record); 
          
          if($record[0]['electro'] && !empty($record[0]['electro'])) {
            // Electronic
            $itemUrl = $record[0]['electro'];
          } else {
            unset($itemUrl);
          }
          
          if($record[0]['type'] && !empty($record[0]['type']) && $record[0]['type'] != "") {
            $linkText = $record[0]['type'];
          } else {
            $linkText = "Read More";
          }
        ?>
          
          <div class="col-md-12 no-pad">
            <h1 class="title">
              <?php
                if($itemUrl && !empty($itemUrl)) { ?>
                  <a target="_blank" href="<?php echo $itemUrl; ?>"><?php echo $record[0]['title']; ?> <?php echo $record[0]['subTitle']; ?></a>
                <?php  
                } else { ?>
                  <?php echo $record[0]['title']; ?> <?php echo $record[0]['subTitle']; ?>
                <?php  
                } ?>
            </h1>
          </div>
          
          <div class="col-md-8 no-pad">
              <?php
              // if($record[0]['author']) {
              if($record[0]['author']) {
              ?>
                <p><strong>Author</strong>: <?php echo $record[0]['author']; ?></p>
              <?php
              }
              
              // if($record[0]['date']) {
              if($record[0]['date']) {
              ?>
                <p><strong>Date</strong>: <?php echo $record[0]['date']; ?></p>
              <?php
              }
              
              // if($record[0]['publisher']) {
              if($record[0]['publisher']) {
              ?>
                <p><strong>Publisher</strong>: <?php echo $record[0]['publisher']; ?></p>
              <?php
              }
              
              // if($record[0]['note']) {
              if(false) {
              ?>
                <p><strong>Note</strong>: <?php echo $record[0]['note']; ?></p>
              <?php
              }
              
              // if($record[0]['summary']) {
              if($record[0]['summary']) {  
              ?>
                <p><strong>Summary</strong>: <?php echo $record[0]['summary']; ?></p>
              <?php
              }
              
              // if($record[0]['bibId']) {
              if(false) {  
              ?>
                <p><strong>ID</strong>: <?php echo $record[0]['bibId']; ?></p>
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
          <div class="col-md-4 no-pad">
              <?php
              // if($record[0]['imgUrl']) {
              if(true) {  
              ?>
                <div class="full-width"><img class="img-contain" src="<?php echo $record[0]['imgUrl']; ?>" /></div>
              <?php
              }
              ?>
          
          </div>
        <?php
        } else {
          // no record found ?>    
          <h2>No record found</h2>
        <?php  
        }
      ?>
    
    
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