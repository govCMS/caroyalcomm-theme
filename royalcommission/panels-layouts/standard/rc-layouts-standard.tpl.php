<?php ?>

<?php if ($content['top_left'] || $content['top_right']): ?>
  <section class="page-top-wrapper" id="page-top-wrapper">
      <div class="container">
          <div class="row">
              <div class="col-sm-8 col-md-9"><?php print $content['top_left']; ?></div>
              <div class="col-sm-4 col-md-3"><?php print $content['top_right']; ?></div>
          </div>
      </div>
  </section>
<?php endif; ?>

<?php if ($content['content_top']): ?>
  <section class="page-main-top-wrapper" id="page-main-top-wrapper">
      <div class="container">
          <div class="row"><?php print $content['content_top']; ?></div>  
      </div>
  </section>
<?php endif; ?>

<?php if ($content['content']): ?>
  <section class="page-main-wrapper" id="page-main-wrapper">
      <div class="container">
          <div class="row"><?php print $content['content']; ?></div>  
      </div>
  </section>
<?php endif; ?>
