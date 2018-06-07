<?php
/**
 * @file
 * Accordion template file.
 * 
 */
?>
<div class="panel panel-accordion">
  <a href="#" class="panel-heading toc-filter-processed" aria-expanded="false" role="button">
    <span class="panel-heading-title"><?php print render($content['field_acrd_title']); ?></span>    
  </a>
  <div class="panel-body">
    <div class="panel-description"><?php print render($content['field_acrd_description']); ?></div>  
    <?php print render($content['field_acrd_content']); ?>
  </div>
</div>