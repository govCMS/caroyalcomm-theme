<?php ?>
<div id="top-and-first-wrapper">
    <?php include "includes/header.tpl.php"; ?>	  
</div>

<?php if($messages || $tabs || $actions_links): ?>
<div class="cms-menu-wrapper">
  <div class="container">
      <div class="row">
        <?php if ($messages): ?>
          <div id="messages-console" class="clearfix">
              <div class="row">
                  <div class="col-md-12">
                      <?php print $messages; ?>
                  </div>
              </div>
          </div>
        <?php endif; ?>

        <?php if ($tabs = render($tabs)): ?>
          <div class="tabs">
              <?php print render($tabs); ?>
          </div>
        <?php endif; ?>

        <?php if ($action_links): ?>
          <ul class="action-links">
              <?php print render($action_links); ?>
          </ul>
        <?php endif; ?>
      </div>    
  </div>         
</div>    
<?php endif; ?>

<?php if ($page['content']): ?>
  <?php print render($page['content']); ?>
<?php endif; ?>

<?php include "includes/footer.tpl.php"; ?>
