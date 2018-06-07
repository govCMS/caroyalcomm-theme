<?php
?>

<?php if ($content['homepage_hero']): ?>
  <section class="homepage-hero" id="homepage-hero">
    <div class="container-fluid">
      <?php print $content['homepage_hero']; ?>
    </div>
  </section>
<?php endif; ?>


<?php if ($content['homepage_fastfact']): ?>
  <section class="homepage-fastfact" id="homepage-fastfact">
    <div class="container">
      <div class="row">
        <?php print $content['homepage_fastfact']; ?>
      </div>
    </div>
  </section>
<?php endif; ?>


<?php if ($content['homepage_commissioner']): ?>
  <section class="homepage-commissioner" id="homepage-commissioner">
    <div class="container">
	  <div class="row">
		<?php print $content['homepage_commissioner']; ?>
       </div>  
    </div>
  </section>
<?php endif; ?>

<?php if ($content['homepage_timeline']): ?>
  <section class="homepage-timeline" id="homepage-timeline">
    <div class="container">
      <div class="row">
        <?php print $content['homepage_timeline']; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ($content['homepage_media']): ?>
  <section class="homepage-media" id="homepage-media">
    <div class="container">
      <div class="row">
        <?php print $content['homepage_media']; ?>
      </div>
    </div>
  </section>
<?php endif; ?>
