<?php

$timeline_title = isset($content['field_timeline_title']) ? render($content['field_timeline_title'][0]) : '';

$timeline_image = '';
if (isset($content['field_timeline_image'])) {
  $content['field_timeline_image'][0]['#item']['alt'] = $timeline_title;
  $timeline_image = render($content['field_timeline_image']);
}

$timeline_date = isset($content['field_timeline_date']) ? render($content['field_timeline_date'][0]) : '';
$timeline_desc = isset($content['field_timeline_description']) ? render($content['field_timeline_description'][0]) : '';

$timeline_link = '';
if (isset($content['field_timeline_link'])) {
  $content['field_timeline_link'][0]['#element']['attributes']['aria-label'] = t('Read more - !title', array('!title'=> $timeline_title));
  $timeline_link = render($content['field_timeline_link'][0]);
}

$timeline_link_url = isset($content['field_timeline_link']) ? render($content['field_timeline_link'][0]['#element']['display_url']) : ''; 
$timeline_data_year = '';
if (!empty($timeline_date)) {
  $timeline_arr_year = explode(" ", strip_tags($timeline_date));  
  if($timeline_arr_year) {
    $timeline_data_year = $timeline_arr_year[1];
  }
}
?>
<div class="timeline-item" data-year="<?php print $timeline_data_year; ?>" data-url="<?php print $timeline_link_url; ?>">
    <div class="timeline-item-wrapper">
        <div class="timeline-item-yearlabel"></div>
        <div class="timeline-item-image"><a href="<?php print $timeline_link_url; ?>"><?php print $timeline_image; ?></a></div>
        <div class="timeline-item-content">
            <div class="timeline-item-date"><?php print strip_tags($timeline_date); ?></div>
            <div class="timeline-item-title"><h3><a href="<?php print $timeline_link_url; ?>"><?php print $timeline_title; ?></a></h3></div>
            <div class="timeline-item-desc"><?php print $timeline_desc; ?></div>
            <div class="timeline-item-link"><?php print $timeline_link; ?></div>
        </div>
    </div>
</div>
