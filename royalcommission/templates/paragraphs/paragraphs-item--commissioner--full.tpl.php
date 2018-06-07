<?php
$about_image = isset($content['field_commissioner_image']) ? render($content['field_commissioner_image'][0]) : '';
$about_title = isset($content['field_commissioner_title']) ? render($content['field_commissioner_title'][0]) . '<br/>' : '';
$about_name = isset($content['field_commissioner_name']) ? render($content['field_commissioner_name'][0]) : '';
$about_desc = isset($content['field_commissioner_desc']) ? render($content['field_commissioner_desc'][0]) : '';

$about_link = '';
if (isset($content['field_commissioner_link'])) {
  $content['field_commissioner_link'][0]['#element']['attributes']['aria-label'] = t('Read more - !title - !name', array(
      '!title'=> strip_tags($about_title),
      '!name' => $about_name,
    ));
  $about_link = render($content['field_commissioner_link'][0]);
}

$about_link_url = isset($content['field_commissioner_link']) ? $content['field_commissioner_link'][0]['#element']['display_url'] : '';
?>
<div class="about-commissioner-item" data-url="<?php print $about_link_url;?>">
    <div class="about-commissioner-item-wrapper">
        <div class="about-comm-image"><?php print $about_image ?></div>
        <div class="about-comm-content">
            <div class="about-comm-title"><h3><?php print $about_title; ?><?php print $about_name; ?></h3></div>
            <div class="about-comm-desc"><?php print $about_desc; ?></div>
            <div class="about-comm-link"><?php print $about_link;?></div>
        </div>
    </div>
</div>
