<?php
/**
 * @file
 * Display Suite reset template.
 * 
 * $fl_title: Title.
 * $fl_description: Description.
 * $fl_bg_color: Background color.
 */
?>

<?php
  $file_tag = isset($content['field_fl_tags']) ? $content['field_fl_tags'][0] : '';
  if ($file_tag) {
    $file_tag_id = explode("/", $file_tag['#href']);  
  }  
?>
<div class="file-list-wrapper">
  <h3 style="background-color: <?php print $fl_bg_color ?>"><?php print $fl_title ?></h3>

  <?php print render($content['field_fl_files']); ?>
  <?php
    if ($file_tag != '' && !isset($content['field_fl_files'])) {
      print views_embed_view('file_lists', 'block_file_lists', end($file_tag_id));
    }
  ?>
</div>
