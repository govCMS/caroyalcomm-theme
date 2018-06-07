<?php
$media_image = isset($content['field_media_image']) ? render($content['field_media_image'][0]) : '';
$media_video = isset($content['field_media_video']) ? $content['field_media_video']['#items'][0] : '';
$media_title = isset($content['field_image_video_title']) ? render($content['field_image_video_title'][0]) : '';
$media_desc = isset($content['field_image_video_desc']) ? render($content['field_image_video_desc'][0]) : '';

$media_link = '';
if (isset($content['field_image_video_link'])) {
  $content['field_image_video_link'][0]['#element']['attributes']['aria-label'] = t('Read more - !title', array('!title'=> $media_title));
  $media_link = render($content['field_image_video_link'][0]);
}

$media_link_url = isset($content['field_image_video_link']) ? render($content['field_image_video_link'][0]['#element']['display_url']) : ''; 
?>
<div class="image-video-item">
    <div class="image-video-image">
      <a href="<?php print $media_link_url; ?>"><?php print $media_image; ?></a>
      <?php if($media_video != ''):         
        $video_title = $media_video['filename'];
        $video_desc = !empty($media_video['field_media_description']) ? $media_video['field_media_description'][LANGUAGE_NONE][0]['value'] : '';
        $video_transcript = !empty($media_video['field_video_transcript']) ? file_create_url($media_video['field_video_transcript'][LANGUAGE_NONE][0]['uri']) : '';          
      ?>  
      <a href="#" class="video-btn" data-url="<?php print file_create_url($media_video['uri']); ?>" data-transcript="<?php print $video_transcript; ?>" data-title="<?php print $video_title; ?>" data-description="<?php print $video_desc; ?>">Play</a>
      <div class="share-wrapper hide hide-print">
          <?php print _royalcommission_render_share(); ?>
      </div>
      <?php endif; ?>  
    </div>
    <div class="image-video-content">
        <div class="image-video-title"><h3><a href="<?php print $media_link_url; ?>"><?php print $media_title; ?></a></h3></div>
        <div class="image-video-desc"><?php print $media_desc; ?></div>
        <div class="image-video-link"><?php print $media_link; ?></div>
    </div>
  
</div>