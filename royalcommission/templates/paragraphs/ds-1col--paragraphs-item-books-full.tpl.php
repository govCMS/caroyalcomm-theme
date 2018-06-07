<?php

$book_items = isset($content['field_book_items']) ? $content['field_book_items'] : '';
if ($book_items !== ''):  
  //reset($book_items);
  $book_items = array_filter($book_items, function ($k) { return is_numeric($k); }, ARRAY_FILTER_USE_KEY);
  $perpage = 100;
  $totalrecord = count($book_items);
  //home many pages needed ?
  $totalpages = (int)ceil($totalrecord/$perpage);
  //get page query
  $urlquery = drupal_get_query_parameters();
  if(isset($urlquery['p'])) {
    $page = (int)$urlquery['p'];
    if($page == 1) {
      $page = 1;
      $start = 0;
      $end = ($perpage - 1);
    } else {
      if($page < 1 || $page > $totalpages) {
        $page = 1;
        $start = 0;
        $end = ($perpage - 1);
      } else {
        //page 1, 0 - 99
        $start = ($page - 1) * $perpage;
        $end = ($start - 1) + $perpage;
        //if this is last page
        if($page == $totalpages) {
          $end = $totalrecord;
        }
      }
    }
  } else {
    $page = 1;
    $start = 0;
    $end = ($perpage - 1);
  }

  $slide_rangemin = (($page-1) * $perpage) + 1;
  $slide_rangemax = $page*$perpage;
  if($slide_rangemax>=$totalrecord) $slide_rangemax = $totalrecord;
  $c_slidenum = $slide_rangemin;
  if(isset($urlquery['num'])) $c_slidenum = $urlquery['num'];

  
  reset($book_items);
  $books = array();
  
  // Get 1st and 2nd item.
  $early_item = array();
  
  foreach($book_items as $book_key => $book_item) {
  
    // Get 1st and 2nd item.
    if ($book_key == 0 || $book_key == 1) {
      $early_book_data = array_values($book_item['entity']['paragraphs_item']);
      $early_book_desc = isset($early_book_data[0]['field_book_description']) ? $early_book_data[0]['field_book_description'][0]['#markup'] : '';
      $early_book_thumbnail = '';
      $early_book_full = '';
      $early_book_img_alt = '';
      $early_book_img_title = '';
      if (isset($early_book_data[0]['field_book_image'])) {
        $early_book_image_file = (object) $early_book_data[0]['field_book_image']['#items'][0];
        $early_book_thumbnail = image_style_url('book_thumbnail', $early_book_image_file->uri);
        $early_book_full = image_style_url('book_full', $early_book_image_file->uri);
        $early_book_img_alt = $early_book_image_file->alt;
        $early_book_img_title = $early_book_image_file->title;
      }
      
      $early_item[] = array(
        'book_desc' => $early_book_desc,
        'book_thumbnail' => $early_book_thumbnail,
        'book_full' =>  $early_book_full,
        'book_alt' => $early_book_img_alt,
        'book_title' => $early_book_img_title,
        'book_image_url' => file_create_url($early_book_image_file->uri),
      );
    }
    
    if (is_numeric($book_key) && ($book_key >= $start)) {
      $book_data = array_values($book_item['entity']['paragraphs_item']);
      $book_desc = isset($book_data[0]['field_book_description']) ? $book_data[0]['field_book_description'][0]['#markup'] : '';
      $book_thumbnail = '';
      $book_full = '';
      $book_img_alt = '';
      $book_img_title = '';
      if (isset($book_data[0]['field_book_image'])) {
        $book_image_file = (object) $book_data[0]['field_book_image']['#items'][0];
        $book_thumbnail = image_style_url('book_thumbnail', $book_image_file->uri);
        $book_full = image_style_url('book_full', $book_image_file->uri);
        $book_img_alt = $book_image_file->alt;
        $book_img_title = $book_image_file->title;
      }
      
      $books[] = array(
        'book_desc' => $book_desc,
        'book_thumbnail' => $book_thumbnail,
        'book_full' =>  $book_full,
        'book_alt' => $book_img_alt,
        'book_title' => $book_img_title,
        'book_image_url' => file_create_url($book_image_file->uri),
      );
      if($book_key >= $end+2) {
        break;
      }
    }

  }
  
  if($page == $totalpages) {
    // Add 1st and 2nd slide to the end.
    $books = array_merge($books, $early_item);
  }
?>
<div class="paging hidden">
  <?php for($i = 1; $i <= $totalpages; $i++):  ?>
    <?php
    if($i == $page) {
      $currentclass = 'current';
    } else {
      $currentclass = '';
    }
    ?>
    <span class="<?php echo $currentclass; ?>"><a href="?p=<?php echo $i ?>"><?php echo $i; ?></a></span>
  <?php endfor; ?>
</div>

<div class="gotoslide-container" data-baseurl="/message-australia" data-itemperpage="<?php echo $perpage; ?>" data-rangemin="<?php echo $slide_rangemin; ?>" data-rangemax="<?php echo $slide_rangemax; ?>">
  <div class="wrap">
    <span class="text goto-title">Go to slide</span> 
    <input type="number" class="number" name="slidenum" size="3" value="<?php echo $c_slidenum; ?>"><input type="button" class="gotoslidenow" value="Go" /> 
    <span class="text">of total</span>
    <span class="totalrecord"><?php echo $totalrecord; ?></span>
  </div>
</div>

<div class="carousel-component-wrapper carousel-message-australia">
    <div class="slider slider-for">
        <?php
          $book_count = ($start + 1);
          foreach($books as $book) {
            ?>
            <div class="item">                            
              <div class="item-book-thumbnail">

                <img data-lazy="<?php print $book['book_full']; ?>" alt="<?php print $book['book_alt']; ?>" title="<?php print $book['book_title']; ?>" width="685" height="385">
                <a href="#" data-src="<?php print $book['book_image_url'];?>">Open Image</a>
              </div>
              <div class="rc-media-item-content">
                <div class="rc-media-item-caption">  
                  <div class="rc-media-count"><span class="book-count"><?php print $book_count; ?> of <?php print $totalrecord; ?></span></div>  
                  <div class="text-more-less">                
                    <?php print $book['book_desc']; ?>
                  </div>
                  <a href="#" class="read-more-btn">
                    <span class="more">Read more</span>
                    <span class="less">Read less</span>
                  </a>
                </div>
                <div class="rc-media-right-content">
                  <div class="rc-media-item-share share-wrapper hide-print">
                    <?php print _royalcommission_render_share(); ?>
                  </div>
                </div>  
              </div>     
            </div>
            <?php
            $book_count++;
          }
        ?>
    </div>
    <div class="slider slider-nav">
       <?php
          foreach($books as $book) {
            ?>
            <div class="item">
              <img data-lazy="<?php print $book['book_thumbnail']; ?>" alt="<?php print $book['book_alt']; ?>" title="<?php print $book['book_title']; ?>">                   
            </div>
            <?php
          }
        ?>
    </div>
</div>  
<?php endif; ?>


