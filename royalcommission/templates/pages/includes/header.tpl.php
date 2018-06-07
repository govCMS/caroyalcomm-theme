<header id="top">
  <div class="header-top">
    <div class="container">
      <div class="row">
        <div class="col-md-5 logos">
          <a href="/">          
            <img alt="Home - Royal Commission into Institutional Responses to Child Sexual Abuse - logo" class="logo" src="<?php print '/' . path_to_theme(); ?>/img/logo.png" />
          </a>

          <div class="navbar-header-mobile pull-right hidden-lg hidden-md">
            <div class="search-wrapper-mobile">
              <a href="#" class="btn-search" title="Search" aria-expanded="false" role="button">Search</a>
            </div>

            <a href="#" type="button" class="navbar-toggle" data-toggle="collapse" aria-expanded="false" role="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
          </div>
        </div>
        <div class="col-md-7">
          <div class="top-navigation">
            <?php
              // Navigation,
              // as per https://codyhouse.co/gem/stripe-navigation/

              // top level nav
              print render($page['header_menu']);

              // sub nav html node
              //print $main_menu;
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="main-navigation-wrapper">
        <div class="ribbon-bg"></div>
        <div class="main-navigation">
          <div class="hidden-md hidden-lg header-mobile-navigation">
            <a href="#" type="button" class="navbar-toggle navbar-close-btn" data-toggle="collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
            <div class="logos">
              <a href="/">          
                <img alt="Royal Commission" class="logo" src="<?php print '/' . path_to_theme(); ?>/img/logo.png" />
              </a>
            </div>
          </div>
          <div class="container">
            <?php
              // Navigation,
              // as per https://codyhouse.co/gem/stripe-navigation/

              // top level nav
              print render($page['header']);

              // sub nav html node
              //print $main_menu;
            ?>
            <div class="hidden-lg hidden-md mobile-top-nav">
              <?php
                // Navigation,
                // as per https://codyhouse.co/gem/stripe-navigation/

                // top level nav
                print render($page['header_menu']);

                // sub nav html node
                //print $main_menu;
              ?>
            </div>
            <!-- SEARCH BUTTON -->
            <div class="search-wrapper">
              <a href="#" class="btn-search" title="Search" aria-expanded="false" role="button">Search</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="form-search-wrapper">
        <div class="container">
          <div class="row">
            <?php
              // Navigation,
              // as per https://codyhouse.co/gem/stripe-navigation/

              // top level nav
              print render($page['search']);

              // sub nav html node
              //print $main_menu;
            ?>
            <a href="#" class="close-search">Close <span class="x-icon"></span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
