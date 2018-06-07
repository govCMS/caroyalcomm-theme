<?php
?>

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

<?php if ($page['top_left'] || $page['top_right']): ?>
  <section class="page-top-wrapper" id="page-top-wrapper">
      <div class="container">
          <div class="row">
              <div class="col-sm-8 col-md-9"><?php print render($page['top_left']); ?></div>
              <div class="col-sm-4 col-md-3"><?php print render($page['top_right']); ?></div>
          </div>
      </div>
  </section>
<?php endif; ?>

<section class="page-main-top-wrapper" id="page-main-top-wrapper">
  <div class="container">
      <div class="row"><h1 class="page-title"><?php print $title ?></h1></div>  
  </div>
</section>

<?php if ($page['sidebar_first'] || $page['content']): ?>
  <section class="page-main-wrapper" id="page-main-wrapper">
      <div class="container">
          <div class="row">
              <div class="col-md-3 sidebar-region"><div class="sidebar-content-wrapper"><?php print render($page['sidebar_first']); ?></div></div>
              <div class="col-md-9"><div class="content-page-with-sidebar"><?php print render($page['content']); ?></div></div>
          </div>  
      </div>
  </section>
<?php endif; ?>

<?php include "includes/footer.tpl.php"; ?>
