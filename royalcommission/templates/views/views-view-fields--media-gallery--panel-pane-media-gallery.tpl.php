<?php
$fid = isset($fields['fid']) ? $fields['fid']->raw : '';
if ($fid != ''):
  $file_object = file_load($fid);  
  $file_url = image_style_url('media_full_image', $file_object->uri);
  $file_description = !empty($file_object->field_media_description) ? $file_object->field_media_description[LANGUAGE_NONE][0]['value'] : $file_object->filename;
  $file_transcript = '';
  $media_thumbnail = image_style_url('media_thumb_image', $file_object->uri);
  $media_full = '';
  $media_video_poster = '';  
  if ($file_object->type == 'image') {
    $media_full = image_style_url('media_full_image', $file_object->uri);
  }
  
  if ($file_object->type == 'video') {    
    $file_transcript = !empty($file_object->field_video_transcript) ? file_create_url($file_object->field_video_transcript[LANGUAGE_NONE][0]['uri']) : '';
    $file_video_poster = !empty($file_object->field_video_image_poster) ? $file_object->field_video_image_poster[LANGUAGE_NONE][0] : '';    
    $file_url = file_create_url($file_object->uri);
    if ($file_video_poster != '') {
      $media_thumbnail = image_style_url('media_thumb_image', $file_video_poster['uri']);
      $media_full = image_style_url('media_full_image', $file_video_poster['uri']);
      $media_video_poster = $media_full;
    }
    else {      
      $media_thumbnail =  base_path() . drupal_get_path('theme', 'royalcommission') . '/img/defaultvideothumb.png';
      $media_video_poster = base_path() . drupal_get_path('theme', 'royalcommission') . '/img/defaultvideofull.png';
    }
    // Media play video
    $media_play_video =  base_path() . drupal_get_path('theme', 'royalcommission') . '/img/icon-play.png';
  }
  ?>
  <div class="media-item-wrapper"> 
      <div class="media-item-thumbnail <?php print $file_object->type; ?>">
          <img src="<?php print $media_thumbnail; ?>" data-type="<?php print $file_object->type; ?>" data-url="<?php print $file_url; ?>" data-poster="<?php print $media_video_poster; ?>" data-transcript="<?php print $file_transcript; ?>" data-description="<?php print $file_description; ?>" alt="<?php print $file_object->alt; ?>" title="<?php print $file_object->title; ?>">
          <a href="#" class="open-detail-gallery <?php if ($file_object->type == 'video') print 'video-btn'; ?>">
            <span class="sr-only">Open Detail Media</span>
            <?php
              if ($file_object->type == 'video') {
              ?>
                <img src="<?php print $media_play_video; ?>" alt="Open Detail Media" aria-hidden="true" focusable="false">
              <?php
              }
            ?>
          </a>
          <div class="share-wrapper hide hide-print">
            <?php print _royalcommission_render_share(); ?>
          </div>
      </div>
      <div class="media-item-description"><p><?php print $file_description; ?></p></div>
  </div>
<?php endif; ?>