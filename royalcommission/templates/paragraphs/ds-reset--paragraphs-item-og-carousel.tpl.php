<?php
/**
 * @file
 * Carousel template file.
 * 
 */
$carousel_id = 'carousel-' . $content['field_caro_slides']['#object']->item_id;
?>
<div id="<?php echo $carousel_id; ?>" class="image-carousel">
  <?php if(isset($content['field_caro_slides'])): ?>
    <?php foreach($content['field_caro_slides']['#items'] as $delta => $item): ?>
      <?php 
      $paragraph = $content['field_caro_slides'][$delta]['entity']['paragraphs_item'][$item['value']];

      // Background image.
      $bgimage_entity = $paragraph['field_caro_bgimage']['#items'][0];
      $bgimage_build = array(
        '#theme' => 'image_style',
        '#path' => $bgimage_entity['uri'],
        '#style_name' => 'carousel_610x340',
        '#alt' => $bgimage_entity['alt'],
      );
      ?>  
  
      <div class="item <?php print $delta === 0 ? 'active' : ''; ?>">
        <?php print render($bgimage_build); ?>
        <div class="image-carousel-content">
          <div class="carousel-image-caption">
            <h4><?php print render($paragraph['field_caro_title']); ?></h4>
            <div><?php print render($paragraph['field_caro_description']); ?></div>
          </div>
          <div class="share-wrapper hide-print">
            <div class="share-print">
              <div class="share-print-share"><a class="share-btn" href="#" title="Share">Share</a>
                <ul>
                  <li><a class="share-fb" href="#">Facebook</a></li>
                  <li><a class="share-tw" href="#">Twitter</a></li>
                  <li><a class="share-li" href="#">Linkedin</a></li>
                  <li><a class="share-em" href="#">Email</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
  
    <?php endforeach; ?>
  <?php endif; ?>
</div>
