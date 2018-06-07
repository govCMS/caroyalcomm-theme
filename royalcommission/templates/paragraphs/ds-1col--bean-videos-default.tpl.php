<?php
$video_items = isset($content['field_video_items']) ? array_values($content['field_video_items'][0]['entity']['paragraphs_item']) : '';
if ($video_items !== ''):  
  $video_files = $video_items[0]['field_video_files']['#items'];
  $video_tags = isset($video_items[0]['field_video_tags']) ? $video_items[0]['field_video_tags']['#items'][0]['tid'] : '';  
  if ($video_tags != '') {
    $view = views_get_view('video_list');
    $view->set_display('block_video_list');
    $view->args = array($video_tags);
    $view->pre_execute();
    $view->execute();
    $result = $view->result;
    
    $video_files = array();
    if (!empty($result)) {
      foreach($result as $file) {
        $video_files[] = (array) file_load($file->fid);
      }
    }    
  }
  $videos = array();
  foreach($video_files as $video_file) {
    $video_file = (object) $video_file;
    
    $video_description = !empty($video_file->field_media_description) ? $video_file->field_media_description[LANGUAGE_NONE][0]['value'] : $video_file->filename;
    $video_transcript = !empty($video_file->field_video_transcript) ? file_create_url($video_file->field_video_transcript[LANGUAGE_NONE][0]['uri']) : '';
    $video_video_poster = !empty($video_file->field_video_image_poster) ? $video_file->field_video_image_poster[LANGUAGE_NONE][0] : '';    
    $video_url = file_create_url($video_file->uri);
    $video_thumbnail = '';
    $video_full = '';
    if ($video_video_poster != '') {
      $video_thumbnail = image_style_url('video_thumbnail', $video_video_poster['uri']);
      $video_full = image_style_url('video_full', $video_video_poster['uri']);
      $video_poster = $video_full;
    }
    else {      
      $video_full =  base_path() . drupal_get_path('theme', 'royalcommission') . '/img/defaultvideothumb.png';
      $video_poster = base_path() . drupal_get_path('theme', 'royalcommission') . '/img/defaultvideofull.png';
    }
    
    $videos[] = array(
      'video_transcript' => $video_transcript,
      'video_poster' => $video_poster,      
      'video_thumbnail' => $video_thumbnail,
      'video_url' => $video_url,
      'video_mime' => $video_file->filemime,
      'video_title' => $video_file->filename,
      'video_description' => $video_description,
    );
  }
?>
<div class="carousel-component-wrapper">
    <div class="slider slider-for">
        <?php
          foreach($videos as $video) {
            ?>
            <div class="item">
              <div class="rc-media-item-img">
                <img data-lazy="<?php print $video['video_poster'];?>" alt="<?php print $video['video_title']; ?>" title="<?php print $video['video_title']; ?>" width="685" height="385">              
                <a href="#" class="video-btn" data-url="<?php print $video['video_url']; ?>" data-transcript="<?php print $video['video_transcript']; ?>" data-title="<?php print $video['video_title']; ?>" data-description="<?php print $video['video_description']; ?>">Play</a>
              </div>
              <div class="rc-media-item-content">
                <div class="rc-media-item-caption">
                  <h4><?php print $video['video_title']; ?></h4>
                  <p><?php print $video['video_description']; ?></p>
                  <a href="<?php print $video['video_transcript']; ?>">Download transcript</a>
                </div>
                <div class="rc-media-item-share share-wrapper hide-print">
                  <?php print _royalcommission_render_share(); ?>
                </div>
              </div>     
            </div>
            <?php
          }
        ?>
    </div>
    <div class="slider slider-nav">
       <?php
          foreach($videos as $video) {
            ?>
            <div class="item">
              <div class="thumb-img">
                <img data-lazy="<?php print $video['video_thumbnail'];?>" alt="<?php print $video['video_title']; ?>" title="<?php print $video['video_title']; ?>">
                <span class="play-icon-small"></span>
              </div>

              <div class="carousel-thumb-content"><p><?php print $video['video_description']; ?></p></div>
            </div>
            <?php
          }
        ?>
    </div>
</div>
<?php endif; ?>
