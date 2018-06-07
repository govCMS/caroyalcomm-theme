<?php
if (isset($content['field_images_and_videos_hide'])) {
  $hide_title_desc = $content['field_images_and_videos_hide']['#items'][0]['value'];
  if ($hide_title_desc == 1) {
    $classes = $classes . ' hide-title-desc';
  }
}
?>
<<?php print $ds_content_wrapper; print $layout_attributes; ?> class="ds-1col <?php print $classes;?> clearfix">

  <?php if (isset($title_suffix['contextual_links'])): ?>
  <?php print render($title_suffix['contextual_links']); ?>
  <?php endif; ?>

  <?php print $ds_content; ?>
</<?php print $ds_content_wrapper ?>>

<?php if (!empty($drupal_render_children)): ?>
  <?php print $drupal_render_children ?>
<?php endif; ?>




