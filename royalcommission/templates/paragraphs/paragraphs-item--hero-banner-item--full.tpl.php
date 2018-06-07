<?php
$hero_image = isset($content['field_hero_image']) ? $content['field_hero_image'][0] : '';
$hero_video = isset($content['field_hero_video']) ? $content['field_hero_video']['#items'][0] : '';
$hero_title = isset($content['field_hero_title']) ? render($content['field_hero_title'][0]) : '';
$hero_desc = isset($content['field_hero_description']) ? render($content['field_hero_description'][0]) : '';

$hero_link = '';
if (isset($content['field_hero_link'])) {
  $content['field_hero_link'][0]['#element']['attributes']['aria-label'] = t('Read more - !title', array('!title'=> $hero_title));
  $hero_link = render($content['field_hero_link'][0]);
}

$hero_size = isset($content['field_hero_size']) ? render($content['field_hero_size'][0]) : '';
$hero_image_style = 'hero_image_large';
$hero_item_class = strtolower($hero_size);
if(strtolower($hero_size) == 'small') {  
  $hero_image_style = 'hero_image_small';
  $hero_image['#image_style'] = $hero_image_style;
}
?>
<div class="hero-banner-item <?php print $hero_item_class; ?>">
    <div class="bg-item">
      <?php print render($hero_image); ?>
    </div>
    <div class="hero-content-wrapper">
        <div class="hero-content-container">
            <div class="hero-content">
                <div class="hero-content-title"><h2><span><?php print $hero_title;?></span></h2></div>
                <?php if(strtolower($hero_size) == 'large'): ?>
                <div class="hero-content-desc"><?php print $hero_desc; ?></div>
                <?php if($hero_video != ''):
                  $video_title = $hero_video['filename'];
                  $video_desc = !empty($hero_video['field_video_description']) ? $hero_video['field_video_description'][LANGUAGE_NONE][0]['value'] : '';
                  $video_transcript = !empty($hero_video['field_video_transcript']) ? file_create_url($hero_video['field_video_transcript'][LANGUAGE_NONE][0]['uri']) : '';
                ?>
                <div class="hero-video-link">
                    <a href="#" class="video-btn" data-url="<?php print file_create_url($hero_video['uri']); ?>" data-transcript="<?php print $video_transcript; ?>" data-title="<?php print $video_title; ?>" data-description="<?php print $video_desc; ?>">Watch video</a>
                    <div class="share-wrapper hide hide-print">
                      <?php print _royalcommission_render_share(); ?>
                    </div> 
                </div>
                <?php endif; ?>
                <?php if($hero_link != ''): ?>
                <div class="hero-content-link"><?php print $hero_link; ?></div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>  
    </div>  
</div>
