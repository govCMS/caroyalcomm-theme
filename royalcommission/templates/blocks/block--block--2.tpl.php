<?php
?>
<div id="<?php print $block_html_id; ?>" class="explore-page menu-block-sidebar <?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if ($block->subject): ?>
    <?php print render($title_prefix); ?>
    <h2<?php print $title_attributes; ?>><a href="#" class="toggle-sidebar-navigation" aria-expanded="false" role="button"><?php print $block->subject ?></a></h2>
    <?php print render($title_suffix); ?>
  <?php endif;?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php print $content; ?>
  </div>
</div>
