(function ($, Drupal) {
  // Global variables
  var scrollTimeout;
  var carouselAboutCommOpt;
  var carouselTimelineOpt;
  var carouselHeroOpt;
  var carouselImgOpt;
  var carouselThumbnailOpt;

  Drupal.behaviors.form = {
    attach: function (context, settings) {
      Drupal.behaviors.form.initialSelectDropdown();

      // Add class no-option
      if (!$('.views-exposed-form .form-type-radio').length) {
        $('.views-exposed-form').addClass('no-option');
      }

      // Add highlight function to views filter and global search.
      if ($.fn.highlight) {
        var keyword = '';
        var $highlightTarget = null;

        // If this is on page search.
        if ($('body.page-search').length > 0) {
          keyword = $('.search-info-wrapper strong').text();
          $highlightTarget = $('.search-result-content');
        }
        else {
          // Else, assume views filter.
          keyword = $('.views-exposed-form .form-item-title input, .views-exposed-form .form-type-textfield input').val();
          $highlightTarget = $('.view-content');
        }

        if (typeof keyword !== 'undefined') {
          if (keyword.length > 0 && $highlightTarget.length > 0) {
            $highlightTarget.highlight(keyword);
          }
        }
      }
    },

    initialSelectDropdown: function() {
      // Change year text.
      $('#edit-field-rc-date-value-value-year option:first').text('Year');
      $('select').selectpicker();
    }
  };

  // First pager issue on Views
  if (typeof Drupal.views !== 'undefined') {


        Drupal.views.ajaxView.prototype.attachPagerLinkAjax = function(id, link) {
        var $link = $(link);
        var viewData = {};
        var href = $link.attr('href');
        // Construct an object using the settings defaults and then overriding
        // with data specific to the link.
        $.extend(
          viewData,
          this.settings,
          Drupal.Views.parseQueryString(href),
          // Extract argument data from the URL.
          Drupal.Views.parseViewArgs(href, this.settings.view_base_path)
        );

        // For anchor tags, these will go to the target of the anchor rather
        // than the usual location.
        $.extend(viewData, Drupal.Views.parseViewArgs(href, this.settings.view_base_path));

        if (typeof(viewData.page) === 'undefined'){
          viewData.page = 0;
        }

        this.element_settings.submit = viewData;
        this.pagerAjax = new Drupal.ajax(false, $link, this.element_settings);
        this.links.push(this.pagerAjax);
      };

  }
  

  Drupal.behaviors.footer = {
    attach: function (context, settings) {
      Drupal.behaviors.footer.eventDropdownFooterMobile();
    },

    eventDropdownFooterMobile: function() {
      $('.dropdown-footer').once('footer-toggle').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('open')) {
          $(this).removeClass('open');
          $(this).next('.menu').slideUp();
        }
        else {
          $(this).addClass('open');
          $(this).next('.menu').slideDown();
        }
      });
    },

    removeAllInlineStyleOnResize: function() {
      if ($(window).width() > 991) {
        $('.footer-top').find('.menu').attr('style', '');
        $('.dropdown-footer').removeClass('open');
      }
    }
  };

  Drupal.behaviors.header = {
    attach: function (context, settings) {
      // Code here

      // Event navbar toggle on mobile
      $('.navbar-toggle').once('navbar-toggle').on('click', function(e) {
        e.preventDefault();

        $('body').toggleClass('open-nav-mobile');
        $('.navbar-toggle').attr('aria-expanded', 'false');
        $('.form-search-wrapper').slideUp();
        $('.btn-search').removeClass('open');
        // Change attribute aria-expanded value from true to false
        $('.btn-search').attr('aria-expanded', 'false');

        if ($('body').hasClass('open-nav-mobile')) {
          $('.navbar-toggle').attr('aria-expanded', 'true');
        } 
      });

      // Open child menu on main navigation
      $('.main-navigation .main-nav > .expanded > a').once('main-navigation-toggle').on('click', function(e) {
        if (!$(this).parent().hasClass('open')) {
          e.preventDefault();
          $('.form-search-wrapper').slideUp();
          $('.btn-search').removeClass('open');
          $('.main-navigation .submenu-wrapper').slideUp();
          $('.main-navigation .submenu-wrapper').parent().removeClass('open');
          $(this).parent().find('.submenu-wrapper').slideDown();
          $(this).parent().addClass('open');
          // Change attribute aria-expanded value from false to true
          $('.main-navigation .main-nav > .expanded > a').attr('aria-expanded', 'false');
          $(this).attr('aria-expanded', 'true');
          $('.btn-search').attr('aria-expanded', 'false');
        }
        else {
          return true;
        }
      });

      // Close child menu;
      $('.close-submenu').once('close-submenu').on('click', function(e) {
        e.preventDefault();
        $(this).parents('.submenu-wrapper').slideUp();
        $(this).parents('li.open').removeClass('open');
        // Change attribute aria-expanded value from true to false
        $(this).parent('.submenu-wrapper').prev().attr('aria-expanded', 'false');
      });

      // Open Search Field
      $('.btn-search').once('button-search-toggle').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('open')) {
          $('.form-search-wrapper').slideUp();
          $('.btn-search').removeClass('open');
          // Change attribute aria-expanded value from true to false
          $(this).attr('aria-expanded', 'false');
        }
        else {
          $('.main-navigation .submenu-wrapper').slideUp();
          $('.main-navigation .submenu-wrapper').parent().removeClass('open');
          $('.form-search-wrapper').slideDown(400, function() {
            $('#search-block-form .form-type-textfield input').focus();
          });
          $('.btn-search').addClass('open');
          // Change attribute aria-expanded value from false to true
          $(this).attr('aria-expanded', 'true');
          $('.main-navigation .submenu-wrapper').prev().attr('aria-expanded', 'false');
        }
      });

      // Close search field
      $('.close-search').once('event-close-search').on('click', function(e) {
        e.preventDefault();
        $('.form-search-wrapper').slideUp();
        $('.btn-search').removeClass('open');
        // Change attribute aria-expanded value from true to false
        $('.btn-search').attr('aria-expanded', 'false');
      });
    },

    matchHeightChildMenuPerRow: function() {
      if ($(window).width() > 991) {
        setTimeout(function(){
          $('.main-navigation .submenu-wrapper').hide();
          $('.main-navigation .submenu-wrapper').parent().removeClass('open');
          // Change attribute aria-expanded value from true to false
          $('.main-navigation .submenu-wrapper').prev().attr('aria-expanded', 'false');

          $('.main-navigation .submenu-wrapper').each(function() {
            wrapper = $(this);
            wrapper.addClass('show');
            var heightRow = [];

            wrapper.find('.sub-menu').each(function() {
              var _el = $(this);
              _el.find('li').each(function(index, ele) {
                if (heightRow[index] != undefined) {
                  _heightEl = $(this).outerHeight();
                  if (heightRow[index] < _heightEl) {
                    heightRow[index] = _heightEl;
                  }
                }
                else {
                  heightRow[index] = $(this).outerHeight();
                }
              });
            });

            wrapper.find('.sub-menu').each(function() {
              var _el = $(this);
              _el.find('li').each(function(index, ele) {
                $(this).css('height', heightRow[index]);
              });
            });

            wrapper.removeClass('show');
          });
        }, 100);
      } else {
        $('.main-navigation .submenu-wrapper').find('.sub-menu li').each(function() {
          $(this).css('height', '');
        });
      }
    },

    stickyNavigation: function() {
      if ($(window).width() > 991) {
        var changeToStickyOn = 70;

        if ((document.documentElement.scrollTo > changeToStickyOn) || ($(document).scrollTop() > changeToStickyOn)) {
          $('body').addClass('sticky-header');
        } else {
          $('body').removeClass('sticky-header');
        }
      }
    },

    closeAllMenu: function() {
      $('.main-navigation .submenu-wrapper').slideUp();
      $('.main-navigation .submenu-wrapper').parent().removeClass('open');
      // Change attribute aria-expanded value from true to false
      $('.main-navigation .submenu-wrapper').prev().attr('aria-expanded', 'false');
    }
  };

  Drupal.behaviors.fastFact = {
    attach: function (context, settings) {
      $('.bean-fast-facts').once('bean-ff-init').each(function() {
        var numbrOfItems = $(this).find('.fast-fact-item').length;
        $(this).addClass('column-'+numbrOfItems);

        if ($(this).hasClass('no-animation')) {
          // Put comma for thousands.
          $(this).find('.fast-fact-number').once('fastFact-thousands').each(function(){
            $(this).text(Drupal.behaviors.globalFunction.numberWithCommas($(this).text()))
          });
        }
        else {
          $(this).find('.fast-fact-number').once('fastFact-animate').each(function(){
            var elementToAnimate = $(this);
            elementToAnimate.data('value-animate', elementToAnimate.text());
            elementToAnimate.text('0');
          });
        }
      })
    },

    animateTextOnViewport: function() {
      $('.bean-fast-facts').each(function(){
        if (Drupal.behaviors.globalFunction.isElementInViewport($(this)) && !$(this).hasClass('no-animation')) {
          $(this).find('.fast-fact-number').each(function (index, ele) {
            if (!$(this).hasClass('animateText-started')) {
              var timeoutTime = index * 4000;
              var elementToAnimate = $(this);

              setTimeout(function() {
                Drupal.behaviors.globalFunction.animateText(elementToAnimate, 4000, elementToAnimate.data('value-animate'));
              }, timeoutTime);

              elementToAnimate.addClass('animateText-started');
            }
          });
        }
      });
    }
  };

  Drupal.behaviors.aboutCommissioner = {
    attach: function(context, settings) {
      carouselAboutCommOpt = {
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
      };

      Drupal.behaviors.aboutCommissioner.commissionerItem();

      var isDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

      if (isDevice && $(window).width() < 992) {
        $('.paragraphs-items-field-commissioners').once('initialize-slick-commissioners').each(function() {
          if (!$(this).hasClass('slick-initialized')) {
            $(this).slick(carouselAboutCommOpt);
          }
        });
      }
    },

    carouselOnResize: function() {
      var isDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
      
      $('.paragraphs-items-field-commissioners').each(function() {
        if ($(window).width() > 991) {
          if ($(this).hasClass('slick-initialized')) {
            $(this).slick('unslick');
          }
        }
        else if (isDevice && !$(this).hasClass('slick-initialized')) {
          $(this).slick(carouselAboutCommOpt);
        }
      });
    },

    commissionerItem: function() {
      $('.about-comm-link a').on('focus', function(e){
        var thisElm = $(this),
            contentElm = thisElm.closest('.about-comm-content'),
            parentElm = thisElm.closest('.about-commissioner-item');

        contentElm.addClass('hover');
      });

      $('.about-comm-link a').on('blur', function(e){
        var thisElm = $(this),
            contentElm = thisElm.closest('.about-comm-content'),
            parentElm = thisElm.closest('.about-commissioner-item');

        contentElm.removeClass('hover');
      });
    }
  };

  Drupal.behaviors.timelineSection = {
    attach: function (context, settings) {
      carouselTimelineOpt = {
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        responsive: [
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            }
          }
        ]
      }

      // Put year label to first item in year group
      $('.paragraphs-items-field-timelines').once('initialize-slick-timelines').each(function() {
        elWrapper = $(this);

        var yearLabel = '';

        elWrapper.find('.timeline-item').each(function() {
          if ($(this).data('year') != yearLabel) {
            yearLabel = $(this).data('year');
            $(this).find('.timeline-item-yearlabel').html('<h3>' + yearLabel + '</h3>');
          }
        });

        if (!elWrapper.hasClass('slick-initialized')) {
          elWrapper.slick(carouselTimelineOpt);
        }
      });
    },
  };

  Drupal.behaviors.imageVideoPanel = {
    attach: function (context, settings) {
      $('.bean-image-and-video:not(.hide-title-desc) .image-video-item .image-video-content').once('matchHeight-panel-img-video').matchHeight();

      $('.bean-image-and-video').once('columns-count').each(function() {
        var totalItems = ($(this).find('.image-video-item').length > 3) ? 3 : $(this).find('.image-video-item').length;
        $(this).addClass('column-'+totalItems);
      });
    },
  };


  Drupal.behaviors.sidebar = {
    attach: function (context, settings) {
      $('.sidebar-region .pane-block, .sidebar-region .block-menu-block').addClass('menu-block-sidebar');
      if ($('.toggle-sidebar-navigation').closest('.open')) {
        $('.toggle-sidebar-navigation').attr('aria-expanded', 'true');
      }

      $('a.toggle-sidebar-navigation').once('show-hide-sidebar').on('click', function (e) {
        e.preventDefault();
        var blockMenu = $(this).parents('.menu-block-sidebar');

        if (blockMenu.hasClass('open')) {
          Drupal.behaviors.sidebar.closeSidebar(blockMenu);
        }
        else {
          Drupal.behaviors.sidebar.openSidebar(blockMenu);
        }
      });

      Drupal.behaviors.sidebar.onResize();
      Drupal.behaviors.sidebar.addExplorePageElements();

      // RCWBUA-156.

      // Add toggle element.
      $('.sidebar-region li.expanded > a')
        .once('collapsible-toggle')
        .append('<span>&nbsp;</span>');

      $('.sidebar-region li.expanded > a > span')
        .once('rc-collapsible')
        .on('click', function(e){
          var $submenu = $(this).parent().next('.submenu-wrapper').children('.container');
          if ($submenu.length > 0) {
            if ($submenu.hasClass('sub-closed')) {
              // Already closed, then open.
              $submenu.slideDown(400, function(){
                $(window).scroll();
              })
              .removeClass('sub-closed').closest('li.expanded').removeClass('state-closed');
            }
            else {
              // Open, then close.
              $submenu.slideUp(400, function(){
                $(window).scroll();
              })
              .addClass('sub-closed').closest('li.expanded').addClass('state-closed');
            }
          }
          e.stopPropagation();
          return false;
        });

      // RCWBUA-139. Using javascript approach.
      if ($('body.media-releases-speeches').length > 0) {
        $('#block-menu-block-9 > .content ul li.active-trail').removeClass('active-trail');
        $('#block-menu-block-9 > .content ul li a.active-trail').removeClass('active-trail');
        $('#block-menu-block-9 > .content ul li a.menu-item-speeches').addClass('active-trail').parent('li').addClass('active-trail');
      }

      //Expanded aria label for active-trail class sidebar
      $('.menu-block-sidebar .active-trail a').attr('aria-expanded', 'false');
      if (!$('.menu-block-sidebar .active-trail').hasClass('state-closed')) {
        $('.menu-block-sidebar .active-trail a').attr('aria-expanded', 'true');
      }

      $('.menu-block-sidebar .active-trail a .rc-collapsible-processed').on('click', function(e){
        var thisElm = $(this),
            linkElm = thisElm.parent(),
            trailElm = thisElm.closest('.active-trail');
          
        linkElm.attr('aria-expanded', 'false');
        if (!trailElm.hasClass('state-closed')) {
          linkElm.attr('aria-expanded', 'true');
        }
      });
    },

    openSidebar: function (el) {
      el.addClass('open');
      el.find('.menu-block-wrapper').slideDown(400, function() {
        $(window).scroll();
      });
      el.find('.toggle-sidebar-navigation').attr('aria-expanded', 'true');
    },

    closeSidebar: function (el) {
      el.removeClass('open');
      el.find('.menu-block-wrapper').slideUp(400, function() {
        $(window).scroll();
      });
      el.find('.toggle-sidebar-navigation').attr('aria-expanded', 'false');
    },

    onResize: function () {
      if ($(window).width() >= 991) {
        Drupal.behaviors.sidebar.openSidebar($('.menu-block-sidebar'));
      }
      else {
        Drupal.behaviors.sidebar.closeSidebar($('.menu-block-sidebar'));
      }
    },

    initStickySidebar: function () {
      if ($('.sidebar-region .sidebar-content-wrapper div').length) {
        var headerHeight = 140,
            topOffset = $('.sidebar-region').offset().top,
            bottomOffset = $('.footer').outerHeight();

        // check if logged in
        if ($('body').hasClass('logged-in')) {
          headerHeight += 45;
        }

        if ($('body').hasClass('navbar-tray-open')) {
          headerHeight += 40;
        }

        $('.sidebar-region .sidebar-content-wrapper').once('sticky-sidebar').affix({
          offset: {
            top: function () {
              return topOffset - headerHeight;
            },
            bottom: function () {
              return bottomOffset;
            }
          }
        });
      }
    },

    stickySidebar: function () {
      if ($('.sidebar-region .sidebar-content-wrapper div').length) {
        if ($('.sidebar-content-wrapper').outerHeight() > $('.content-page-with-sidebar').outerHeight()) {
          $('.sidebar-content-wrapper').attr('style', '');
          $('.sidebar-content-wrapper').removeClass('affix affix-bottom');
          $('.sidebar-content-wrapper').addClass('affix-off');
        } else {
          $('.sidebar-content-wrapper').removeClass('affix-off');
        }
      }
    },

    addExplorePageElements: function() {
      if ($('.sidebar-region') && $('.explore-page').length < 1 && $('.col-md-9 h2:not(.element-invisible)').length) {
        $('.sidebar-region').append('')
      }

      if ($('.explore-page').length) {
        if ($('.content-page-with-sidebar h2:not(.element-invisible)').length) {
           $('.content-page-with-sidebar h2:not(.element-invisible)').once('populated-anchor-link').each(function(index, ele) {
            if ($(this).text() !== "" && $(this).parents('.view-empty').length < 1) {
              $(this).attr('id', 'exploreID'+index);
              $('.explore-page ul.menu').append('<li><a href="#exploreID'+index+'">'+$(this).text()+'</a></li>')
            }
          });
        }
        else {
          $('.explore-page').addClass('hide');
        }
      }
    }
  };


  Drupal.behaviors.heroBanner = {
    attach: function (context, settings) {
      carouselHeroOpt = {
        speed: 800,
        autoplaySpeed: 6000,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        autoplay: false,
        dots: true,
        prevArrow: false,
        nextArrow: false,
      };

      // Use img as background image and initialized carousel
      $('.hero-banner-wrapper').once('slick-initialized-hero').each(function() {
        var elWrapper = $(this);

        // Adding hidden text
        elWrapper.parent().prepend('<h2 class="sr-only">Slideshow</h2>');

        if (!elWrapper.hasClass('slick-initialized')) {
          elWrapper.find('.hero-banner-item .bg-item').each(function() {
            imageSource = $(this).children('img').attr('src');
            $(this).css('background-image', 'url('+imageSource+')');

          });

          if (elWrapper.find('.hero-banner-item:first-child').hasClass('small')) {
            elWrapper.addClass('small-caro');
          }

          elWrapper.slick(carouselHeroOpt);

          if (elWrapper.find('.hero-banner-item').length > 1) {
            Drupal.behaviors.heroBanner.groupingCarouselControls(elWrapper);
          }

          // Drupal.behaviors.heroBanner.createButtonScrollDown(elWrapper);

          elWrapper.on('beforeChange', function(event, slick, currentSlide, nextSlide){
            classNameNextSlide = slick.$slides[nextSlide].className;
            if (classNameNextSlide.indexOf('small') > -1) {
              elWrapper.addClass('small-caro');
            }
            else {
              elWrapper.removeClass('small-caro');
            }

            elWrapper.find('.slick-dots li').find('button').attr('aria-pressed', 'false');
            elWrapper.find('.slick-dots li').eq(nextSlide).find('button').attr('aria-pressed', 'true');
          });

          // Remove attribute role such as listbox and option
          elWrapper.find('[role="listbox"]').removeAttr('role');
          elWrapper.find('[role="option"]').removeAttr('role');
        }
      });
    },

    groupingCarouselControls: function (elCarousel) {

      var prevButtonElmString = '<a href="#previous" data-role="none" class="slick-prev slick-arrow" aria-label="Slideshow Previous" role="button">' +
                                '<span class="active-control-default">' +
                                '<span class="sr-only">Slideshow Previous</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-prev-circle.png" alt="Previous" focusable="false">' +
                                '</span>' +
                                '<span class="active-control-hover hide">' +
                                '<span class="sr-only">Slideshow Previous</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-prev-circle-hover.png" alt="Previous" focusable="false">' +
                                '</span>' +
                                '</a>';

      var nextButtonElmString = '<a href="#next" data-role="none" class="slick-next slick-arrow" aria-label="Slideshow Next" role="button">' +
                                '<span class="active-control-default">' +
                                '<span class="sr-only">Slideshow Next</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-next-circle.png" alt="Next" focusable="false">' +
                                '</span>' +
                                '<span class="active-control-hover hide">' +
                                '<span class="sr-only">Slideshow Next</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-next-circle-hover.png" alt="Next" focusable="false">' +
                                '</span>' +
                                '</a>';

      var playButtonElmString = '<a href="#play" data-role="none" class="slick-play slick-arrow" aria-label="Slideshow Play" role="button">' +
                                '<span class="active-control-default">' +
                                '<span class="sr-only">Slideshow Play</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-play-circle.png" alt="Play" focusable="false">' +
                                '</span>' +
                                '<span class="active-control-hover hide">' +
                                '<span class="sr-only">Slideshow Play</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-play-circle-hover.png" alt="Play" focusable="false">' +
                                '</span>' +
                                '</a>';

      var pauseButtonElmString = '<a href="#play" data-role="none" class="slick-pause slick-arrow" aria-label="Slideshow Pause" role="button">' +
                                '<span class="active-control-default">' +
                                '<span class="sr-only">Slideshow Pause</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-pause-circle.png" alt="Pause" focusable="false">' +
                                '</span>' +
                                '<span class="active-control-hover hide">' +
                                '<span class="sr-only">Slideshow Pause</span>' +
                                '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-pause-circle-hover.png" alt="Pause" focusable="false">' +
                                '</span>' +
                                '</a>';

      elCarousel.append('<div class="carousel-controls-wrapper"><div class="carousel-controls slick-active-control"></div></div>');
      prevBtn = $(prevButtonElmString);
      nextBtn = $(nextButtonElmString);
      dots    = elCarousel.find('.slick-dots');
      playButton = $(playButtonElmString);
      pauseButton = $(pauseButtonElmString);

      dots.appendTo(elCarousel.find('.carousel-controls'));
      prevBtn.appendTo(elCarousel.find('.carousel-controls'));
      nextBtn.appendTo(elCarousel.find('.carousel-controls'));
      playButton.appendTo(elCarousel.find('.carousel-controls'));
      pauseButton.appendTo(elCarousel.find('.carousel-controls'));

      // Add event on play/pause, prev, and next button
      elCarousel.find('.slick-pause').on('click', function(e) {
        e.preventDefault();
        elCarousel.slick('slickPause');
        elCarousel.removeClass('autoplay');
      });

      elCarousel.find('.slick-play').on('click', function(e) {
        e.preventDefault();
        elCarousel.slick('slickPlay');
        elCarousel.addClass('autoplay');
      });

      elCarousel.find('.slick-prev').on('click', function(e) {
        e.preventDefault();
        elCarousel.slick('slickPrev');
      });

      elCarousel.find('.slick-next').on('click', function(e) {
        e.preventDefault();
        elCarousel.slick('slickNext');
      });

      // Add event on hover
      elCarousel.find('.slick-arrow').hover(function () {
          var thisElm = $(this);
          thisElm.find('.active-control-default').addClass('hide');
          thisElm.find('.active-control-hover').removeClass('hide');
        }, function () {
          var thisElm = $(this);
          thisElm.find('.active-control-hover').addClass('hide');
          thisElm.find('.active-control-default').removeClass('hide');
        }
      );

      // Add attribute aria-pressed
      elCarousel.find('.slick-dots li').find('button').attr('aria-pressed', 'false');
      elCarousel.find('.slick-dots li.slick-active').find('button').attr('aria-pressed', 'true');

      // Remove attribute role such as listbox and option
      $('.slick-initialized[role="toolbar"]').removeAttr('role');
      $('.slick-initialized [aria-hidden="true"]').removeAttr('aria-hidden');
      $('.slick-initialized [aria-hidden="false"]').removeAttr('aria-hidden');
      $('.carousel-controls-wrapper [role="tablist"]').removeAttr('role');
      $('.carousel-controls-wrapper [role="presentation"]').removeAttr('role');
      $('.carousel-controls-wrapper [aria-selected]').removeAttr('aria-selected');
      $('.carousel-controls-wrapper [aria-hidden="false"]').removeAttr('aria-hidden');
      $('.carousel-controls-wrapper [aria-controls]').removeAttr('aria-controls');

      // Adding attribute for aria-hidden at image control
      elCarousel.find('.slick-active-control .slick-arrow img').attr('aria-hidden', 'true');

      // Adding text Slide
      elCarousel.find('.carousel-controls .slick-dots li').each(function(){
        var thisElm = $(this),
            btnElm = thisElm.find('button'),
            btnText = btnElm.text();
        
        btnElm.text('Slide ' + btnText);
      });

      // Adding text control
      elCarousel.find('.carousel-controls').prepend('<p class="sr-only">Slideshow controls</p>')
    },

    // createButtonScrollDown: function (elCarousel) {
    //   if (!elCarousel.hasClass('small-caro')) {
    //     scrollDownBtn = $('<button type="button" data-role="none" class="btn-scroll-down" aria-label="Scroll Down" role="button">Scroll Down</button>');
    //     elCarousel.append(scrollDownBtn);
    //     elCarousel.find('.btn-scroll-down').on('click', function() {
    //       var scrollTo = elCarousel.position().top + elCarousel.height();
    //       if ($(window).width() > 991) {
    //         scrollTo -= 133; //133 is the height of sticky navigation;
    //       }
    //       $('html, body').animate({
    //         scrollTop: scrollTo
    //       }, 1000);
    //     });
    //   }
    // }
  };

  Drupal.behaviors.accordion = {
    attach: function (context, settings) {
      $('.panel-accordion .panel-heading').once('accordion').on('click', function(e){
        e.preventDefault();
        $(this).toggleClass('open');

        if ($(this).hasClass('open')) {
          $(this).next('.panel-body').slideDown();
          $(this).attr('aria-expanded', 'true');
        }
        else {
          $(this).next('.panel-body').slideUp();
          $(this).attr('aria-expanded', 'false');
        }

        setTimeout(function(){
          // calling scroll twice to fix sidebar sticky issue.
          $(window).scroll();
          $(window).scroll();
        }, 500);
      });
    }
  };

  Drupal.behaviors.imageCarousel = {
    attach: function(context, settings) {
       carouselImgOpt = {
        speed: 800,
        autoplaySpeed: 4000,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        autoplay: false,
        dots: false,
        adaptiveHeight: true
      };
      
      $('.image-carousel').once('slick-initialized-img').each(function() {
        var elWrapper = $(this);

        if (elWrapper.find('.item').length == 1) {
          elWrapper.addClass('no-carousel');
        }

        if (!elWrapper.hasClass('slick-initialized')) {
          elWrapper.slick(carouselImgOpt);
          elWrapper.on('afterChange', function(event, slick, currentSlide, nextSlide){
            $(window).scroll();
          });
        }
      });

      Drupal.behaviors.imageCarousel.setArrowPositionToBeImageCentered();
    },

    setArrowPositionToBeImageCentered: function () {
      $('.image-carousel').each(function() {
        var elWrapper = $(this);

        if (elWrapper.hasClass('slick-initialized')) {
          topPosition = $(this).find('.slick-list').width() * 340 / 610 / 2; // 340 x 610 is the image dimension
          $(this).find('.slick-arrow').css('top', topPosition);
        }
      });
    }
  };

  Drupal.behaviors.mediaGallery = {
    attach: function (context, settings) {
      if ($('.media-list-wrapper').length && $('.page-main-wrapper').length) {
        $('.page-main-wrapper').addClass('media-gallery');
      }

      $('.media-list-item').imagesLoaded(function () {
        $('.media-item').matchHeight();
      });

      $('.open-detail-gallery').once('open-detail').on('click', function (e) {
        e.preventDefault();
        Drupal.behaviors.mediaGallery.openDetailGallery($(this).parents('.media-item'));
      });
    },

    openDetailGallery: function (item) {
      $('.opened.detail-gallery-container').slideUp('400', function() {
        $(this).parent().remove();
      });

      if (item.hasClass('opened')) {
        item.removeClass('opened');
        return false
      }
      else {
        $('.media-item').removeClass('opened');
        $('.media-item').find('.ajax-progress').remove();
        item.find('.media-item-thumbnail').append('<div class="ajax-progress ajax-progress-throbber"><div class="throbber">&nbsp;</div></div>');
        item.find('.ajax-progress').fadeIn();
      }

      var itemsPerRow = 3,
          listMedia = $('.media-list-item'),
          mediaItem = $('.media-item');

      item.addClass('opened');

      $('.opened.detail-gallery-container').slideUp('400', function() {
        $(this).parent().remove();
        $('.media-item').removeClass('opened');
      });

      if ($(window).width() <= 600) {
        itemsPerRow = 1;
      }
      else if ($(window).width() <= 991) {
        itemsPerRow = 2;
      }

      var itemIndex = item.index(),
          totalChildOfUl = $('.media-item').length;

      if (item.prevAll('.detail-gallery').length) {
        itemIndex -= 1;
      }

      var positionForDetail = ((Math.floor(itemIndex / itemsPerRow) * itemsPerRow) + itemsPerRow) - 1;

      if (totalChildOfUl < itemsPerRow || positionForDetail >= totalChildOfUl) {
        positionForDetail = totalChildOfUl - 1;
      }

      //Create content and put to correct position :
      if (item.find('.media-item-thumbnail').hasClass('video')) {
        var contentDetail1 =
        '<div class="detail-gallery-img">'+
          '<img src="'+item.find('.media-item-thumbnail img').data('poster')+'" class="img-responsive">'+
          '<a href="#" class="video-btn play-detail-video">'+
            '<span class="sr-only">Play Video</span>'+
            '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-play.png" alt="Play Video" aria-hidden="true" focusable="false">'+
          '</a>'+
          '<video width="640" height="360" controls preload="none" class="detail-gallery-video">'+
            '<source src="'+item.find('.media-item-thumbnail img').data('url')+'" type="video/mp4">'+
            'Your browser does not support the video tag.'+
          '</video>'+
        '</div>';
        
        // Detail video with accessibility
        var contentDetail2 =
        '<div class="detail-gallery-img">'+
          '<img src="'+item.find('.media-item-thumbnail img').data('poster')+'" class="img-responsive">'+
          '<a href="#" class="video-btn play-detail-video">'+
            '<span class="sr-only">Play Video</span>'+
            '<img src="' + Drupal.settings.basePath + Drupal.settings.pathToTheme + '/img/icon-play.png" alt="Play Video" aria-hidden="true" focusable="false">'+
          '</a>'+
          '<div class="px-video-container" id="myvid">' +
            '<div class="px-video-img-captions-container">' +
            '<div class="px-video-captions hide"></div>' +
            '<video width="640" height="360" poster="'+item.find('.media-item-thumbnail img').data('poster')+'" class="detail-gallery-video" controls>' +
              '<source src="'+item.find('.media-item-thumbnail img').data('url')+'" type="video/mp4" />' +
              '<div>' +
                '<a href="'+item.find('.media-item-thumbnail img').data('url')+'">' +
                  '<img src="'+item.find('.media-item-thumbnail img').data('poster')+'" width="600" height="360" alt="download video" />' +
                '</a>' +
              '</div>' +
              '</video>' +
            '</div>' +
            '<div class="px-video-controls"></div>' +
          '</div>' +
        '</div>';

        var contentDetail = contentDetail2;
        
        var linkTranscript = (item.find('.media-item-thumbnail img').data('transcript')) ? '<a href="'+item.find(".media-item-thumbnail img").data("transcript")+'" target="_blank">Download transcript</a>' : '';
        var contentDescription =
        '<div class="detail-gallery-description">'+
          '<p>'+item.find('.media-item-thumbnail img').data('description')+'</p>'+
          linkTranscript+
        '</div>';
      }
      else {
        var contentDetail =
        '<div class="detail-gallery-img">'+
          '<img src="'+item.find('.media-item-thumbnail img').data('url')+'" class="img-responsive">'+
        '</div>';
        var contentDescription =
        '<div class="detail-gallery-description">'+
          '<p>'+item.find('.media-item-thumbnail img').data('description')+'</p>'+
        '</div>';
      }

      contentToClone =
      '<div class="detail-gallery">'+
        '<div class="detail-gallery-container">'+
          '<div class="close-btn">'+
            '<a href="#" class="close-detail-gallery">Close<span class="iconClose"></span></a>'+
          '</div>'+
          '<div class="detail-gallery-wrapper">'+
            contentDetail+
            '<div class="detail-gallery-content">'+
              contentDescription+
              '<div class="detail-gallery-share">'+
              '</div>'+
            '</div>'+
          '</div>'+
        '</div>'+
      '</div>';

      listMedia.find('.media-item:eq(' + positionForDetail + ')').after(contentToClone);

      if (item.find('.media-item-thumbnail').hasClass('video')) {
        setTimeout(function(){
          new InitPxVideo({
            videoId: 'myvid',
            captionsOnDefault: true,
            seekInterval: 20,
            videoTitle: item.find('.media-item-thumbnail img').data('description'),
            debug: true,
            jQuery: $
          });
        }, 500);

        setTimeout(function(){
          var controlElm = $('.px-video-controls');
          if (!controlElm.length)
            return;
    
          var controlLeftElm = controlElm.find('.pull-left').first();
          
          $('<div class="pull-right px-video-control-right"></div>').insertAfter(controlLeftElm);

          var controlRightElm = controlLeftElm.siblings(),
              controlVideoRightElm = controlElm.find('.px-video-control-right');

          controlRightElm.appendTo(controlVideoRightElm);
        }, 1000);
      }

      if ($('.detail-gallery-img .video-btn').length) {
        // Set focus for play button
        setTimeout(function() {
          $('.detail-gallery-img .video-btn').focus();
        }, 500);

        $('.detail-gallery-img .video-btn').on('click', function (e) {
          e.preventDefault();
          //$(this).next('.detail-gallery-video').get(0).play();
          $(this).next('.px-video-container').find('.detail-gallery-video').get(0).play();
          $('.px-video-controls .px-video-play')
            .addClass('hide')
            .removeClass('px-video-show-inline');
          $('.px-video-controls .px-video-pause')
            .removeClass('hide')
            .addClass('px-video-show-inline');
          $(this).parent().addClass('video-play');
          setTimeout(function() {
            $(this).parent().addClass('video-playing');
          }, 300);
        });
      }

      var shareButton = item.find('.share-wrapper').clone(true, true).removeClass('hide');
      $('.detail-gallery-container:not(.opened) .detail-gallery-share').append(shareButton);

      $('a.close-detail-gallery').once('event-close-detail-gallery').on('click', function (e) {
        e.preventDefault();
        $('.detail-gallery-container.opened').slideUp('400', function() {
          // Set focus on play button when close detail
          if ($('.media-item.opened .video-btn').length) {
            $('.media-item.opened .video-btn').focus();
          }

          $(this).parent().remove();
          $('.media-item').removeClass('opened');
        });
      });

      // Issue : Image sharing button is truncated
      var isVideo = $('.detail-gallery-img a.play-detail-video').length;

      if (isVideo > 0) {
        $('.detail-gallery-wrapper').css('margin-bottom', '0px');
      } else {
        $('.detail-gallery-wrapper').css('margin-bottom', '50px');
      }

      $('.detail-gallery').imagesLoaded(function () {
        $('.media-item').find('.ajax-progress').remove();
        $('.detail-gallery-container').slideDown('400', function() {
          $(this).addClass('opened');
        });

        // --- scroll to the opened item ----
        var menuHeight = 133,
            topSpace = 100,
            prevDetailHeight = 0;

        if ($('body').hasClass('logged-in')) {
          menuHeight = menuHeight + $('#navbar-bar').height();
        }
        if ($('body').hasClass('navbar-tray-open')) {
          menuHeight += 40;
        }

        if (item.prevAll('.detail-gallery').length) {
          prevDetailHeight = item.prevAll('.detail-gallery').find('.detail-gallery-container').outerHeight() + 50; // 50 is the margin bottom of detail gallery wrapper.
        }

        if ($(window).width() <= 991) {
          menuHeight = 0;
        }

        $("html, body").animate({scrollTop: $('.detail-gallery-container:not(.opened)').offset().top - menuHeight - prevDetailHeight - topSpace}, 400);
      });
    },
  };

  Drupal.behaviors.carouselThumbnail = {
    attach: function(context, settings) {
       var url = window.location.href;
       if (url.indexOf('/message-australia') > -1) {
          carouselThumbnailOpt = {
            speed: 600,
            autoplaySpeed: 4000,
            lazyLoad: 'ondemand',
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            autoplay: false,
            dots: false,
            arrows: false,
          };
       }else{
          carouselThumbnailOpt = {
            speed: 600,
            autoplaySpeed: 4000,
            lazyLoad: 'ondemand',
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            autoplay: false,
            dots: false,
            arrows: true,
          };
       }

      carouselThumbnailNavOpt = {
        slidesToShow: 3,
        slidesToScroll: 1,
        arrow: false,
        speed: 600,
        asNavFor: '.slider-for',
        dots: false,
        centerMode: false,
        centerPadding: '0px',
        focusOnSelect: true,
        lazyLoad: 'ondemand',
        responsive: [
          {
            breakpoint: 500,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            }
          }
        ]
      };

      $('.bean-books').once('give-ID').each(function(index,el) {
        var book   = $(this);
        var bookId = 'book-'+(index+1);


        book.find('.slider-for .item-book-thumbnail img').each(function (imgIndex,el) {
          $(this).attr('data-id', bookId+'-page-'+(imgIndex+1));
        })
      });

      
      /* message to australia */
      $('.carousel-component-wrapper').once('give-ID').each(function(index, el) {
        var goto_wrap = $('.gotoslide-container');
        var b_url = goto_wrap.data('baseurl');
        var rg_min = goto_wrap.data('rangemin');
        var rg_max = goto_wrap.data('rangemax');
        var rg_num = goto_wrap.data('itemperpage');
        var rg_tot = parseInt(goto_wrap.find('.totalrecord').text());
        var slide_c = parseInt(goto_wrap.find('[name=slidenum]').val());
        
        var mainCarousel = $(this).find('.slider-for');
        var navCarousel = $(this).find('.slider-nav');

        mainCarousel.attr('id', 'carouselThumbMain'+index);
        navCarousel.attr('id', 'carouselThumbNav'+index);

        carouselThumbnailOpt['asNavFor'] = '#carouselThumbNav'+index;
        carouselThumbnailNavOpt['asNavFor'] = '#carouselThumbMain'+index;

        if (mainCarousel.find('.item').length == 1) {
          $(this).addClass('no-carousel');
        }
        
        mainCarousel.on('init', function(){
          $('.paragraphs-items-field-books .slider').css('visibility', 'visible');
          $('.paragraphs-items-field-books .carousel-component-wrapper').addClass('noloading');
        });

        if (!mainCarousel.hasClass('slick-initialized')) {
          mainCarousel.slick(carouselThumbnailOpt);
          mainCarousel.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            Drupal.behaviors.carouselThumbnail.resetReadMoreLess();

            var url = window.location.href;
            if (url.indexOf('/message-australia') > -1) {
              // nextSlide
              // rg_max

//              console.log('currentSlide:' + currentSlide);
//              console.log('nextSlide:' + nextSlide);

              var pnum = Math.floor((rg_max-1)/rg_num);
              var curr = currentSlide + (pnum*rg_num) + 1;
              var real_next = nextSlide + (pnum*rg_num) + 1;

              if (currentSlide == 0 && ( nextSlide>rg_num || real_next>rg_max )) {
                var w_sc = $(window).scrollTop();
                createCookie('win_sc',w_sc,1);

                var next = curr - 1;

                var goto = next;
                if(goto<=0){
                  goto = rg_tot;
                }

                var pnum = Math.floor(goto/rg_num);
                var rest = goto - (pnum*rg_num);
                if(rest>0){
                  pnum+=1;
                }

                var redirect_url = b_url+'?p='+pnum+'&num='+goto;
                //console.log(rest, redirect_url);

                window.location = redirect_url;
              }
              else {
                var next = nextSlide + (pnum*rg_num) + 1;
  //              console.log('--- MTA ---');
  //              console.log(next);
  //              console.log(rg_max);
  //              console.log('nextSlide: ' + nextSlide);
                if(next>rg_max){
                  var w_sc = $(window).scrollTop();
                  createCookie('win_sc',w_sc,1);

                  var goto = next;
                  if(goto>rg_tot){
                    goto = 1;
                  }

                  var pnum = Math.floor(goto/rg_num);
                  var rest = goto - (pnum*rg_num);
                  if(rest>0){
                    pnum+=1;
                  }

                  var redirect_url = b_url+'?p='+pnum+'&num='+goto;
                  //console.log(rest, redirect_url);

                  window.location = redirect_url;
                }
              }



            }
          });
          mainCarousel.on('afterChange', function(event, slick, currentSlide, nextSlide){
            var slidenum = rg_min + currentSlide;
            goto_wrap.find('[name=slidenum]').val(slidenum);
          });
          /*
          mainCarousel.on('beforeChange', function(event, slick, currentSlide, nextSlide){
            console.log(event,currentSlide,nextSlide);
          });
          */
        }

        if (!navCarousel.hasClass('slick-initialized')) {
          navCarousel.slick(carouselThumbnailNavOpt);
        }
        
        
        goto_wrap.find('.gotoslidenow').click(function(){
          var goto = parseInt(goto_wrap.find('[name=slidenum]').val());
          if(goto>=rg_tot){
            goto = rg_tot;
            goto_wrap.find('[name=slidenum]').val(goto);
          }
          if(goto>=rg_min && goto<=rg_max){
            // withing range
            var slidenum = goto-rg_min;
            mainCarousel.slick('slickGoTo', slidenum);
            navCarousel.slick('slickGoTo', slidenum);
          }else{
            // outside range
            var w_sc = $(window).scrollTop();
            createCookie('win_sc',w_sc,1);
            
            var pnum = Math.floor(goto/rg_num);
            var rest = goto - (pnum*rg_num);
            if(rest>0){
              pnum+=1;
            }
            
            var redirect_url = b_url+'?p='+pnum+'&num='+goto;
            //console.log(rest, redirect_url);
            
            window.location = redirect_url;
          }
        });
        goto_wrap.find('[name=slidenum]').keyup(function(e){
          if(e.keyCode==13){
            goto_wrap.find('.gotoslidenow').trigger('click');
          }else{
            var nnn = parseInt(goto_wrap.find('[name=slidenum]').val());
            if(nnn<1){
              nnn = 1;
              goto_wrap.find('[name=slidenum]').val(nnn);
            }
          }
        });
        
        
        mainCarousel.on('click','.slick-arrow.custom-prev',function(){
          var slide_c = parseInt(goto_wrap.find('[name=slidenum]').val());
          var next = slide_c-1;
          var slidenum = next-rg_min;
          
          //console.log(slidenum, rg_min, next);
          if(next<rg_min){
            var w_sc = $(window).scrollTop();
            createCookie('win_sc',w_sc,1);
            
            var goto = next;
            if(goto<=0){
              goto = rg_tot;
            }
            
            var pnum = Math.floor(goto/rg_num);
            var rest = goto - (pnum*rg_num);
            if(rest>0){
              pnum+=1;
            }
            
            var redirect_url = b_url+'?p='+pnum+'&num='+goto;
            //console.log(rest, redirect_url);
            
            window.location = redirect_url;
          }else{
            mainCarousel.slick('slickGoTo', slidenum);
            navCarousel.slick('slickGoTo', slidenum);
          }
        }).on('click','.slick-arrow.custom-next',function(){
          var slide_c = parseInt(goto_wrap.find('[name=slidenum]').val());
          var next = slide_c+1;
          var slidenum = next-rg_min;
          
          //console.log(slidenum, rg_min, next);
          if(next>rg_max){
            var w_sc = $(window).scrollTop();
            createCookie('win_sc',w_sc,1);
            
            var goto = next;
            if(goto>rg_tot){
              goto = 1;
            }
            
            var pnum = Math.floor(goto/rg_num);
            var rest = goto - (pnum*rg_num);
            if(rest>0){
              pnum+=1;
            }
            
            var redirect_url = b_url+'?p='+pnum+'&num='+goto;
            //console.log(rest, redirect_url);
            
            window.location = redirect_url;
          }else{
            mainCarousel.slick('slickGoTo', slidenum);
            navCarousel.slick('slickGoTo', slidenum);
          }
        });
        
        
        var gotoIntervalID = 0;
        gotoIntervalID = setInterval(function(){
          if (mainCarousel.hasClass('slick-initialized') && navCarousel.hasClass('slick-initialized')) {
            var w_sc = readCookie('win_sc');
            // console.log(w_sc);
            
            if(w_sc==null){
              // console.log('nulll.....');
            }else{
              // console.log('else.....');
              $("html, body").scrollTop(w_sc);
              goto_wrap.find('.gotoslidenow').trigger('click');
            }
            
            // var arr_p = '<button type="button" data-role="none" class="slick-prev slick-arrow custom-prev" aria-label="Previous" role="button" style="display: block;">Previous</button>';
            // var arr_n = '<button type="button" data-role="none" class="slick-next slick-arrow custom-next" aria-label="Next" role="button" style="display: block;">Next</button>';
            
            // mainCarousel.append(arr_n);
            // mainCarousel.prepend(arr_p);
            // Drupal.behaviors.carouselThumbnail.setArrowPositionToBeImageCentered();
            var url = window.location.href;
            if (url.indexOf('/message-australia') > -1) {
              var arr_p = '<button type="button" data-role="none" class="slick-prev slick-arrow custom-prev" aria-label="Previous" role="button" style="display: block;">Previous</button>';
              var arr_n = '<button type="button" data-role="none" class="slick-next slick-arrow custom-next" aria-label="Next" role="button" style="display: block;">Next</button>';
 
              mainCarousel.append(arr_n);
              mainCarousel.prepend(arr_p);
              Drupal.behaviors.carouselThumbnail.setArrowPositionToBeImageCentered();
            }
            
            clearInterval(gotoIntervalID);
            eraseCookie('win_sc');
          }
        },200);
      });

      Drupal.behaviors.carouselThumbnail.readMoreLess();
      Drupal.behaviors.carouselThumbnail.setArrowPositionToBeImageCentered();

      $('.item-book-thumbnail a').once('click-event').on('click', function(e) {
        e.preventDefault();
        originalImgSource = $(this).data('src');

        Drupal.behaviors.carouselThumbnail.openImageLightbox(originalImgSource);
      });
    },

    setArrowPositionToBeImageCentered: function () {
      $('.carousel-component-wrapper .slider-for').each(function() {
        var elWrapper = $(this);

        if (elWrapper.hasClass('slick-initialized')) {
          topPosition = $(this).find('.slick-list').width() * 385 / 685 / 2; // 385 x 685 is the image dimension
          $(this).find('.slick-arrow').css('top', topPosition);
        }
      });
    },

    goToSelectedBook: function () {
      if (window.location.hash) {
        var hashTag = window.location.hash;
        hashTag = hashTag.replace('#', '');

        if (hashTag.indexOf('book-') > -1 && hashTag.indexOf('page-') > -1 && $('img[data-id="'+hashTag+'"]').length) {
          var dataSlickIndex = $('img[data-id="'+hashTag+'"]').parents('.slick-slide').data('slick-index');
          $('img[data-id="'+hashTag+'"]').parents('.carousel-component-wrapper').find('.slider-nav').slick('slickGoTo',dataSlickIndex);
          $('img[data-id="'+hashTag+'"]').parents('.carousel-component-wrapper').find('.slider-for').slick('slickGoTo',dataSlickIndex);
          setTimeout(function() {
            Drupal.behaviors.globalFunction.animateToSelectedElement($('img[data-id="'+hashTag+'"]'));
          }, 200);
        }
      }
    },

    readMoreLess: function () {
      $('.text-more-less').once('more-less').each(function() {
        if ($(this).outerHeight() > 66) {
          $(this).data('height-ori', $(this).outerHeight())
          $(this).css('height', '66px');
          $(this).parent().addClass('has-more');
        }
      });

      $('.has-more .read-more-btn').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parent();

        if (parent.hasClass('open')) {
          parent.removeClass('open');
          parent.find('.text-more-less').css('height', '66px');
        }
        else {
          parent.addClass('open');
          parent.find('.text-more-less').css('height', parent.find('.text-more-less').data('height-ori'));
        }

        // Repositioned sticky sidebar.
        setTimeout(function(){
          $(window).scroll();
        }, 600);
      });
    },

    resetReadMoreLess: function () {
      $('.text-more-less').css('height', '66px');
      $('.has-more').removeClass('open');

      // Repositioned sticky sidebar.
      setTimeout(function(){
        $(window).scroll();
      }, 500);
    },

    openImageLightbox: function(imgSource) {
      if (imgSource != undefined && imgSource != '') {
        Drupal.behaviors.globalFunction.resetModalBootstrap();
        $('#modalRC').modal();
        $('#modalRC').addClass('modal-rc-image');

        htmlImage =
          '<div class="modal-video-content">'+
            '<img src="'+imgSource+'" />'+
          '</div>';

        $('#modalRC .modal-body').html(htmlImage);
      }
    },
  };

  Drupal.behaviors.privateSessions = {
    attach: function (context, settings) {
      $('.view-private-sessions .views-row').once('matchHeight-private-listing').matchHeight();

      // Set dropdown value based on change radio button option
      $('#edit-field-government-value-wrapper input[name="field_government"]').on('change', function() {
        var govRadioValue = $(this).val();
        $('#edit-field-government-value').val(govRadioValue);
        $('#edit-field-government-value').selectpicker('refresh');
      });

      // Set dropdown value based on change radio button option
      $('#edit-field-atsi-value-wrapper input[name="field_atsi"]').on('change', function() {
        var atsiValue = ($(this).is(':checked')) ? 1 : 'all';
        $('#edit-field-atsi-value').val(atsiValue);
        $('#edit-field-atsi-value').selectpicker('refresh');
      });

      // Trigger submit filter on change field
      $('#views-exposed-form-private-sessions-panel-pane-private-session').find('select, input').on('change', function() {
        $('.views-submit-button .form-submit').trigger('click');
      });
    }
  };

  Drupal.behaviors.exhibits = {
    attach: function (context, settings) {
      $('.exhibits-element .content-toggle > a').once('click-event').on('click', function (e) {
        e.preventDefault();

        el = $(this);
        el.parents('.exhibits-items').next('.accordion-content').find('td').slideToggle();
        el.parents('.exhibits-items').toggleClass('open-links');
      });

      $('table.exhibits-container').once('table-responsive').each(function() {
        $(this).wrap('<div class="table-responsive"></div>');
      });
    }
  };

  Drupal.behaviors.fileList = {
    attach: function (context, settings) {
      var itemsToShow = 10;

      $('.file-list-wrapper').each(function() {
        var fileWrapper = $(this);

        fileWrapper.each(function () {
          fileWrapper.find('.row').slice(0, itemsToShow).show();
          if (fileWrapper.find('.row').length <= itemsToShow) {
            fileWrapper.find('.load-more').fadeOut();
          }
        });
      });

      $(".load-more a").on('click', function (e) {
        e.preventDefault();
        $(this).parents('.file-list-wrapper').find(".row:hidden").slice(0, itemsToShow).slideDown();

        Drupal.behaviors.fileList.hideLoadMoreIfNoMoreItems($(this).parents('.file-list-wrapper'));
      });
    },

    hideLoadMoreIfNoMoreItems: function (fileList) {
      if (fileList.find(".row:hidden").length < 1) {
        fileList.find('.load-more').fadeOut();
      }
    }
  }

  Drupal.behaviors.globalFunction = {
    attach: function (context, settings) {

      $('.video-btn').once('video-toggle').on('click', function(e) {
        e.preventDefault();

        Drupal.behaviors.globalFunction.openVideoLightbox($(this));
      });

      $('#modalRC').on('hidden.bs.modal', function (e) {
        Drupal.behaviors.globalFunction.resetModalBootstrap();
      });

      // Reset form
      $('.views-reset-button .form-submit').once('reset-form').on('click', function(e) {
        e.preventDefault();
        var $parentForm = $(this).parents('form:first');
        $parentForm.find('select option').removeAttr('selected');
        $parentForm[0].reset();
        $parentForm.find('input[type=text]').val('');
        $parentForm.find('select').selectpicker("refresh");
        $parentForm.find('.views-submit-button .form-submit').click();
      });

      isIE = Drupal.behaviors.globalFunction.isIEorEDGE();
      if (isIE) {
        $('html').addClass('ie');
      }

      if ($('.media-release-content h3').length > 0) {
        $('.media-release-content h3 a').once('length-chk', function(){
          // RCWBUA-142: There are issue on title with certain length
          var mrTitleLength = $(this).text().length;
          if ( mrTitleLength === 89 || mrTitleLength === 100) {
            $(this).closest('.media-release-content').css('padding-right', '15px');
          }
        });
      }

      Drupal.behaviors.globalFunction.initPrintShare();
      Drupal.behaviors.globalFunction.createShareLink();
      Drupal.behaviors.globalFunction.createButtonScrollDownHomepage();
      Drupal.behaviors.globalFunction.addDoubleArrow();
      Drupal.behaviors.globalFunction.initDotdotdot();
      Drupal.behaviors.globalFunction.mediaReleaseAddParameterUrl();
      Drupal.behaviors.globalFunction.adjustOrderedListStartAt();
      Drupal.behaviors.globalFunction.updateAjaxViewURLParams();
      // Drupal.behaviors.globalFunction.triggerClearSearch();
      Drupal.behaviors.globalFunction.alterAjaxViews();
    },

    adjustOrderedListStartAt: function() {
      $('ol').each(function() {
        if (typeof $(this).attr('start') != 'undefined') {
          var startAt = parseInt($(this).attr('start')) - 1;

          $(this).css('counter-reset', 'item '+startAt);
        }

        $(this).attr('type', $(this).css('list-style-type'));
      });
    },

    mediaReleaseAddParameterUrl: function () {
      $('.view-rc-media-releases .pager a').once('click-event').on('click', function() {
         history.pushState({}, null, $(this).attr('href'));
      });

      $('.view-media-gallery .pager a').once('click-event').on('click', function() {
         history.pushState({}, null, $(this).attr('href'));
      });
    },

    openVideoLightbox: function(el) {
      if (el.data('url') != undefined && el.data('url') != '') {
        Drupal.behaviors.globalFunction.resetModalBootstrap();
        $('#modalRC').modal();
        $('#modalRC').addClass('modal-rc-video');
        if (el.parents('.item').length) {
          var shareButton = el.parents('.item').find('.share-wrapper').clone(true, true).removeClass('hide');
        }
        else {
          var shareButton = el.parent().find('.share-wrapper').clone(true, true).removeClass('hide');
        }

        htmlVideo =
          '<div class="modal-video-content">'+
            '<video width="640" height="360" controls autoplay>'+
              '<source src="'+el.data('url')+'" type="video/mp4">'+
              'Your browser does not support the video tag.'+
            '</video>'+
          '</div>'+
          '<div class="video-info">'+
            '<div class="video-info-wrapper">'+
              '<div class="video-info-title"><h3>'+el.data('title')+'</h3></div>'+
              '<div class="video-info-description">'+el.data('description')+'</div>'+
              '<div class="video-info-transcript"><a href="'+el.data('transcript')+'" target="_blank">Download transcript</a></div>'+
            '</div>'+
            '<div class="video-share-wrapper">'+
            '</div>'+
          '</div>';

        $('#modalRC .modal-body').html(htmlVideo);
        $('#modalRC .modal-body .video-share-wrapper').append(shareButton);
      }
    },

    resetModalBootstrap: function () {
      $('#modalRC').attr('class', '');
      $('#modalRC').addClass('modal fade');
      $('#modalRC .modal-body').html('');
    },

    updateAjaxViewURLParams: function() {
      if ($('#views-exposed-form-private-sessions-panel-pane-private-session').length) {
        var url = document.location.pathname;//narratives
        var category = $('#edit-category').val();
        var gender = $('#edit-field-private-session-gender-value').val();
        var state = $('#edit-field-state-value').val();
        var decade = $('#edit-field-decade-value').val();
        var gov = $('#edit-field-government-value').val();
        var atsi = $('#edit-field-atsi-value').val();

        var querystring = 'category=' + category + '&field_private_session_gender_value=' + gender + '&field_state_value=' + state + '&field_decade_value=' + decade + '&field_government_value=' + gov + '&field_atsi_value=' + atsi + '&next=1';
        var narratives_url = (url.match(/\?/) ? '&' : '?') + querystring;
        history.pushState({}, null, narratives_url);
        console.log(narratives_url);

        $('.private-session-image a, .private-session-link a').each(function() {
            var href = $(this).attr('href');
            if (href) {
              href += (href.match(/\?/) ? '&' : '?') + querystring;
              $(this).attr('href', href);
            }
        });
      }
    },

    alterAjaxViews: function() {
     
      if ($('#views-exposed-form-rc-media-releases-block-2').length || 
          $('#views-exposed-form-rc-media-releases-block-1').length || 
          $('#views-exposed-form-media-gallery-panel-pane-media-gallery').length
          ) {
        // var url = document.location.pathname;

        var views = Drupal.settings.views.ajaxViews;
        var view = view_dom_id = view_name = undefined;
        for (var viewsName in views) {
              if (views.hasOwnProperty(viewsName)) {
                view = views[viewsName];
                if (view['view_display_id'] == "block_2" && view['view_name'] == "rc_media_releases") {
                      //I used view "partners" and "block_1" display. Change it to yours
                  view_name = viewsName;
                  view_dom_id = view["view_dom_id"];
                }

                if (view['view_display_id'] == "block_1" && view['view_name'] == "rc_media_releases") {
                      //I used view "partners" and "block_1" display. Change it to yours
                  view_name = viewsName;
                  view_dom_id = view["view_dom_id"];
                }

                if (view['view_display_id'] == "panel_pane_media_gallery" && view['view_name'] == "media_gallery") {
                  view_name = viewsName;
                  view_dom_id = view["view_dom_id"];
                }
              }
        }
              
        if (!Drupal.views.instances[view_name].exposedFormAjax) return;
        Drupal.views.instances[view_name].exposedFormAjax.options.beforeSubmit = function (form_values, element_settings, options) {
          options.url = "/views/ajax";
        }
        
      }
      
    },

    initDotdotdot: function() {
      // Initialize jquery dotdotdot.
      $(".content-ellipsis,"+
        ".about-comm-desc,"+
        ".hero-content-title h2,"+
        ".small .hero-content-title,"+
        ".media-item-description,"+
        ".timeline-item-desc,"+
        ".carousel-thumb-content p,"+
        ".case-study-item .cstudy-summary,"+
        ".media-release-content .wysiwyg,"+
        ".person-name,"+
        ".about-comm-title h3")
      .dotdotdot({
        watch: true
      });
      $(".media-release-content h3").dotdotdot({
        watch: true,
        truncate: 'word',
        ellipsis: "\u2026 \u00bb"
      });

    },

    animateText: function(element, animateDuration, eleValue) {
      // how many decimal places allows
      var decimal_places = 1;

      if (typeof eleValue == 'undefined') {
        eleValue = element.text();
      }

      if (typeof animateDuration == 'undefined') {
        animateDuration = 4000;
      }

      if (isNaN(eleValue) == false) { // If eleValue IS a number.
        if (eleValue.indexOf('.') > -1) {
          decimal_places = eleValue.length - eleValue.indexOf('.') - 1;
        } else {
          decimal_places = 0;
        }

        //number step function for float number, but it can also use for normal number
        var numberStepFunction = function(now, tween) {
          var floored_number = Math.floor(now) / decimal_factor,
              target = $(tween.elem);

          if (decimal_places > 0) {
            floored_number = floored_number.toFixed(decimal_places);
          }

          target.text(floored_number);
        }

        //if value is more than 10 thousand than we put comma (,)
        if (eleValue > 1000) {
          numberStepFunction = $.animateNumber.numberStepFactories.separator(',');
        }

        var decimal_factor = decimal_places === 0 ? 1 : Math.pow(10, decimal_places);

        element
          .animateNumber(
            {
              number: eleValue * decimal_factor,
              numberStep: numberStepFunction
            },
            animateDuration
          );
      }
    },

    isElementInViewport: function(elem, scrolledAfter) {
      var $elem = $(elem);
      
      if (!$elem.length) {
          return false;
      }
      var $window = $(window);

      if (typeof scrolledAfter == 'undefined' || $window.width() < 991) {
        scrolledAfter = 0;
      }

      var docViewTop = $window.scrollTop();
      var docViewBottom = docViewTop + $window.height();

      var elemTop = $elem.offset().top + scrolledAfter;
      var elemBottom = elemTop + $elem.height();

      return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    },

    initPrintShare: function() {
      $('.share-print-share ul>li>a').click(function (e) {
        var cls = $(this).attr('class');
        if (cls.indexOf('share-em') <= -1) {
          e.preventDefault();
          var url = $(this).attr('href');
          var title = $(document).find("title").text();
          var w = 600;
          var h = 400;

          var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
          var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

          var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
          var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

          var left = ((width / 2) - (w / 2)) + dualScreenLeft;
          var top = ((height / 2) - (h / 2)) + dualScreenTop;
          var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

          // Puts focus on the newWindow
          if (window.focus) {
              newWindow.focus();
          }
        }
      });

      $('.print-btn').unbind();
      $('.print-btn').on('click', function(e){
        e.preventDefault();
        window.print();
        return false;
      });

      $('.share-btn').unbind();
      $('.share-btn').keypress(function(e) {
        if (e.keyCode === 32) {
          e.preventDefault();
          if ($('ul.share-list').hasClass('open'))
            $('ul.share-list').removeClass('open');
          else 
            $('ul.share-list').addClass('open');
        }
      });

      $('.share-btn').click(function(e) {
        e.preventDefault();
        if ($('ul.share-list').hasClass('open'))
          $('ul.share-list').removeClass('open');
        else 
          $('ul.share-list').addClass('open');
      });

      $('.share-print-print .print-btn').focus(function(){
        $('ul.share-list').removeClass('open');
      });
    },

    createShareLink: function() {
      $('.share-print-share li a').each(function() {
        var el = $(this);
        var url = window.location.href;
        var rawPageTitle = $(document).find("title").text();
        var pageTitle = encodeURI(rawPageTitle);

        // Remove query string on private session detail page as it contain
        // unnecessary query string.
        if ($('body.node-type-private-session').length > 0) {
          url = url.split("?")[0];
        }

        if (el.parents('.bean-books').length) {
          var imageBookId = el.parents('.item').find('.item-book-thumbnail img').data('id');
          url = url.split("#")[0];
          url += '%23'+imageBookId;
        }

        if (el.attr('class').indexOf('share-fb') > -1) {
          url = encodeURIComponent(url);
          url += '%0D%0A%0D%0AContent warning: material may be confronting and disturbing. If you need support visit support services. (https://www.childabuseroyalcommission.gov.au/contact).';
          pageTitle = encodeURIComponent(rawPageTitle);
          el.attr('href', 'http://www.facebook.com/share.php?u='+url+'&amp;title='+pageTitle)
        }
        else if (el.attr('class').indexOf('share-tw') > -1) {
          url = encodeURIComponent(url);
          var extraText = 'Material may be confronting and disturbing (http://bit.ly/2ixwD85).';
          pageTitle = encodeURIComponent(extraText);
          el.attr('href', '//twitter.com/intent/tweet?url=' + url + '&amp;text='+pageTitle);
        }
        else if (el.attr('class').indexOf('share-li') > -1) {
          url = encodeURIComponent(url);
          url += '%0D%0A%0D%0AContent warning: material may be confronting and disturbing. If you need support visit support services. (https://www.childabuseroyalcommission.gov.au/contact).';
          pageTitle = encodeURIComponent(rawPageTitle);
          el.attr('href', 'http://www.linkedin.com/shareArticle?mini=true&amp;url='+url+'&amp;title='+pageTitle+'&amp;source='+url)
        }
        else {
          url = encodeURIComponent(url);
          url += '%0D%0A%0D%0AContent warning: material may be confronting and disturbing. If you need support visit support services. (https://www.childabuseroyalcommission.gov.au/contact).';
          pageTitle = encodeURIComponent(rawPageTitle);
          el.attr('href', 'mailto:?subject=' + pageTitle + '&body=' + url);
        }
      });
    },

    addDoubleArrow: function() {
      $('.media-release-content h3 > a, .cstudy-title > a, .search-result-title a, .private-session-link a').each(function() {

        if ($(this).parent().hasClass('is-truncated') || $(this).closest('.content-warning').length > 0) {
          return;
        }
        if ($(this).find('.text-arrow').length < 1 ) {
          var textInLink = $(this).html();

          if (textInLink !== "") {
            var wordsInLink = textInLink.split(" ");
            if (wordsInLink[wordsInLink.length - 1].indexOf('text-arrow') == -1) {
              wordsInLink[wordsInLink.length - 1] = ''+wordsInLink[wordsInLink.length - 1]+'&nbsp;<span class="text-arrow">&raquo;</span>';
            }
            $(this).html(wordsInLink.join(' '));
          }
        }
      });
    },

    isIEorEDGE: function() {
      var rv = false; // Return value assumes failure.

      if (navigator.appName == 'Microsoft Internet Explorer'){
         var ua = navigator.userAgent,
             re  = new RegExp("MSIE ([0-9]{1,}[\\.0-9]{0,})");

         if (re.exec(ua) !== null){
           rv = true;
         }
      }
      else if(navigator.appName == "Netscape"){
         /// in IE 11 the navigator.appVersion says 'trident'
         /// in Edge the navigator.appVersion does not say trident
         if(navigator.appVersion.indexOf('Trident') === -1) true;
         else rv = true;
      }

      return rv;
    },

    anchoringAnimation: function() {
      $('a[href^="#"]').once('anchoring-animation').click(function(e) {
        var elmDest = $(this).attr('href');
        var hash = $(this)[0].hash;

        //check if it anchor
        if (hash.length && !(/^http:\/\//.test(elmDest)) && !(/^https:\/\//.test(elmDest))) {
          e.preventDefault();

          Drupal.behaviors.globalFunction.animateToSelectedElement($(hash));
        }
      });
    },

    animateToSelectedElement: function (el) {
      if (!$('#top').length)
        return;

      var menuHeight = $('#top').height();

      if ($('body').hasClass('logged-in')) {
        menuHeight = menuHeight + $('#navbar-bar').height();
      }

      if ($('body').hasClass('navbar-tray-open')) {
        menuHeight += 40;
      }

      //give more space
      menuHeight += 20;

      if ($(window).width() > 991) {
        if (!el.offset())
          return;

        $("html, body").animate({ scrollTop: el.offset().top - menuHeight}, 500);
      } else {
        if (!el.offset())
          return;

        $("html, body").animate({ scrollTop: el.offset().top - 15}, 500);
      }
    },

    numberWithCommas: function(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },

    createButtonScrollDownHomepage: function () {
      if ($('body').hasClass('front') && $('.button-scroll-down').length < 1) {
        scrollDownBtn = $('<button type="button" data-role="none" class="button-scroll-down" aria-label="Scroll Down" role="button">Scroll Down</button>');
        $('body').append(scrollDownBtn);

        $('.button-scroll-down').once('click-event').on('click', function() {
          var scrollTo = '';
          var elementToScroll = '';

          $(".front section[class*=homepage-]").each(function(index) {
            var documentScrollTop = $(document).scrollTop();

            if ($(window).width() > 991) {
              documentScrollTop += 190; //190 is the height of sticky navigation plus more space;
            }

            if (documentScrollTop >= ($(this).position().top + $(this).outerHeight())) {
              if ($(this).next()[0] != undefined) {
                elementToScroll = $(this).next();
              }
            }
          });

          if (elementToScroll == '' && !Drupal.behaviors.globalFunction.isElementInViewport('.footer ul.menu')) {
              elementToScroll = $('#homepage-hero');
          }

          if (elementToScroll !== '' && !Drupal.behaviors.globalFunction.isElementInViewport('.footer  ul.menu')) {
            var scrollTo = elementToScroll.position().top + elementToScroll.outerHeight();
            if ($(window).width() > 991) {
              scrollTo -= 133; //133 is the height of sticky navigation;
            }
            $('html, body').animate({
              scrollTop: scrollTo
            }, 1000);
          }
        });
      }
    },

    triggerClearSearch: function() {
        $('.date-year ul.dropdown-menu li').click(function() {
          if (window.location.href.indexOf("?field_rc_date_value") > -1 || window.location.href.indexOf("?page") > -1) {
             var coba = $(this).attr('data-original-index');
              $('.views-reset-button  .reset-form-processed').click();
             // console.log(coba);
             // $('.date-year ul.dropdown-menu li[ data-original-index="' + coba + '"]').addClass('selected');
          }
        });
    },

    hideShowBtnScroll: function() {
      if (Drupal.behaviors.globalFunction.isElementInViewport('.footer  ul.menu') && $('.button-scroll-down').length) {
        $('.button-scroll-down').fadeOut();
      }
      else {
        $('.button-scroll-down').fadeIn();
      }
    }
  };

  Drupal.behaviors.contentUpdateAjax = {
    attach: function (context, settings) {
      $('.views-submit-button .btn-info, .views-reset-button .btn-info').on('click', function(){
        $('.content-live-region .content-complete').addClass('hide');
        $('.content-live-region .content-update').removeClass('hide');

        // Remove parameter for filter
        if ($(this).closest('.views-reset-button').length) {
          var urlName = document.location.pathname;
          history.pushState({}, null, urlName);
        }
      });
      $(document).ajaxComplete(function() {
        $('.content-live-region .content-update').addClass('hide');
        $('.content-live-region .content-complete').removeClass('hide');
      });
    }
  };

  Drupal.behaviors.altEmptyTextImage = {
    attach: function (context, settings) {
      var imgElm = $('img:not([alt])');
      if (!imgElm.length)
        return;

      imgElm.attr('alt', '');
    }
  };

  Drupal.behaviors.pagerIdentification = {
    attach: function (context, settings) {
      var pagerElm = $('.item-list .pager');
      if (!pagerElm.length)
        return;

      var firstPage = pagerElm.find('.pager-current.first'),
          lastPage = pagerElm.find('.pager-current.last'),
          firstList = pagerElm.find('.pager-previous + .pager-ellipsis'),
          lastList = pagerElm.find('.pager-ellipsis + .pager-next');
  
      if (firstPage.length) {
        pagerElm.addClass('pager-first-page');
        var ellipsisElm = pagerElm.find('.pager-ellipsis'),
            pagerItems = pagerElm.find('.pager-item');

        pagerItems.each(function(index, elm){
          if (index < 2) {
            $(this).addClass('hide-sm');
          } else if (index < 4) {
            $(this).addClass('hide-md');
          } else {
            $(this).addClass('hide');
          }
        });
      } else if (lastPage.length) {
        pagerElm.addClass('pager-last-page');

        var ellipsisElm = pagerElm.find('.pager-ellipsis'),
            pagerItems = pagerElm.find('.pager-item');

        pagerItems.each(function(index, elm){
          if (index < 4) {
            $(this).addClass('hide');
          } else if (index < 6) {
            $(this).addClass('hide-md');
          } else {
            $(this).addClass('hide-sm');
          }
        });
      } else if (!firstList.length) {
        pagerElm.addClass('pager-first-list-page');
        var ellipsisElm = pagerElm.find('.pager-ellipsis'),
            ellipsisClone = ellipsisElm.clone(),
            pagerItems = pagerElm.find('.pager-item'),
            currentPage = parseInt(pagerElm.find('.pager-current').text());

        pagerItems.each(function(index, elm){
          var thisElm = $(this),
              valueElm = parseInt(thisElm.find('a').text());

          if (valueElm <= (currentPage - 2)) {
              thisElm.addClass('hide-md');
          } else if (valueElm <= (currentPage - 1)) {
            thisElm.addClass('hide-sm');
          } 

          if (valueElm >= (currentPage + 2)) {
            thisElm.addClass('hide-md');
          }
          else if (valueElm >= (currentPage + 1)) {
            thisElm.addClass('hide-sm');
          }

          if (valueElm < (currentPage - 2)) {
            thisElm.addClass('hide');
          }

          if (valueElm > (currentPage + 2)) {
            thisElm.addClass('hide');
          }
        });

        var notHideElm = pagerElm.find('.pager-item:not(.hide)');
        
        if (notHideElm.length < 4) {
          notHideElm.last().next().removeClass('hide');
        }

        if (pagerElm.find('.pager-previous').next().hasClass('hide')) {
          ellipsisClone.css({ 'margin-left': '5px' });
          ellipsisClone.insertAfter(pagerElm.find('.pager-previous'));
        }

        if (!pagerElm.find('.pager-previous').next().hasClass('hide')) {
          if (pagerElm.find('.pager-previous').next().hasClass('hide-md')) {
            ellipsisClone.css({ 'margin-left': '5px' });
            ellipsisClone.addClass('show-md');
            ellipsisClone.insertAfter(pagerElm.find('.pager-previous'));
          } if (pagerElm.find('.pager-previous').next().hasClass('hide-sm')) {
            ellipsisClone.css({ 'margin-left': '5px' });
            ellipsisClone.addClass('show-sm');
            ellipsisClone.insertAfter(pagerElm.find('.pager-previous'));
          }
        }
      } else if (!lastList.length) {
        pagerElm.addClass('pager-last-list-page');
        var ellipsisElm = pagerElm.find('.pager-ellipsis'),
        ellipsisClone = ellipsisElm.clone(),
        pagerItems = pagerElm.find('.pager-item'),
        currentPage = parseInt(pagerElm.find('.pager-current').text());

        pagerItems.each(function(index, elm){
          var thisElm = $(this),
            valueElm = parseInt(thisElm.find('a').text());

          if (valueElm <= (currentPage - 2)) {
            thisElm.addClass('hide-md');
          } else if (valueElm <= (currentPage - 1)) {
            thisElm.addClass('hide-sm');
          }

          if (valueElm >= (currentPage + 2)) {
            thisElm.addClass('hide-md');
          }
          else if (valueElm >= (currentPage + 1)) {
            thisElm.addClass('hide-sm');
          }

          if (valueElm < (currentPage - 2)) {
            thisElm.addClass('hide');
          }

          if (valueElm > (currentPage + 2)) {
            thisElm.addClass('hide');
          }
        });

        var notHideElm = pagerElm.find('.pager-item:not(.hide)');

        if (notHideElm.length < 4) {
          notHideElm.first().prev().removeClass('hide');
        }
        
        if (pagerElm.find('.pager-next').prev().hasClass('hide')) {
          ellipsisClone.css({ 'margin-right': '5px' });
          ellipsisClone.insertBefore(pagerElm.find('.pager-next'));
        }

        if (!pagerElm.find('.pager-next').prev().hasClass('hide')) {
          if (pagerElm.find('.pager-next').prev().hasClass('hide-md')) {
            ellipsisClone.css({ 'margin-right': '5px' });
            ellipsisClone.addClass('show-md');
            ellipsisClone.insertBefore(pagerElm.find('.pager-next'));
          } if (pagerElm.find('.pager-next').prev().hasClass('hide-sm')) {
            ellipsisClone.css({ 'margin-right': '5px' });
            ellipsisClone.addClass('show-sm');
            ellipsisClone.insertBefore(pagerElm.find('.pager-next'));
          }
        }
      } else {
        pagerElm.addClass('pager-center-list-page')
      }
    }
  };

  Drupal.behaviors.safariFocusMainNavigation = {
    attach: function (context, settings) {
      var isDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
      if (isDevice) {
        return;
      }

      var mainNavElm = $('.safari .main-navigation');

      mainNavElm.find('.main-nav li a').attr('tabindex', '0');
      mainNavElm.find('.main-nav li a').focus(function(e){
        var thisElm = $(this),
            parentElm = thisElm.parent();

        parentElm.addClass('focus');
      });

      mainNavElm.find('.main-nav li a').blur(function(e){
        var thisElm = $(this),
            parentElm = thisElm.parent();

        parentElm.removeClass('focus');
      });
    }
  };

  $(window).on('resize', function() {
    Drupal.behaviors.footer.removeAllInlineStyleOnResize();
    Drupal.behaviors.header.matchHeightChildMenuPerRow();
    Drupal.behaviors.aboutCommissioner.carouselOnResize();
    Drupal.behaviors.sidebar.onResize();
    Drupal.behaviors.imageCarousel.setArrowPositionToBeImageCentered();
    Drupal.behaviors.carouselThumbnail.setArrowPositionToBeImageCentered();
  });

  $(window).on('load', function() {
    Drupal.behaviors.header.matchHeightChildMenuPerRow();
    Drupal.behaviors.fastFact.animateTextOnViewport();
    Drupal.behaviors.sidebar.initStickySidebar();
    Drupal.behaviors.globalFunction.anchoringAnimation();
    Drupal.behaviors.carouselThumbnail.goToSelectedBook();
  });

  $(window).on('scroll', function() {
    Drupal.behaviors.header.stickyNavigation();
    Drupal.behaviors.fastFact.animateTextOnViewport();
    Drupal.behaviors.sidebar.stickySidebar();
    Drupal.behaviors.globalFunction.hideShowBtnScroll();
  });

  // Add reload on back on following pages.
  window.onpopstate = function(event) {
    if ($('.view-media-gallery').size() > 0 || $('.view-rc-media-releases').size() > 0){
      location.reload();
    }
  };

  // Workaround for .curCSS error.
  $.curCSS = function (element, attrib, val) {
    $(element).css(attrib, val);
  };

  
  
  $(document).ready(function(){
    $('form').each(function(){
      var action = $(this).attr('action');
      if(action=='/narratives'){
        $(this).addClass('narratives-filter-form');
        mod_narratives_form_elems();
      }
    });
    
    readjust_homepage_timeline_elems();
    add_placeholder_text_on_mediarelease_field();
  });
  
  function mod_narratives_form_elems(){
    var form = $('.narratives-filter-form');
    
    form.find('.form-type-radio label').prepend('<a href="/narratives" class="dummy radio"></a>');
    form.find('.form-type-checkbox label').prepend('<a href="/narratives" type="button" class="dummy checkbox"></a>');
    
    // form.submit(function(){
    //   return false;
    // });
    
    //form.find('#clear-search').trigger('click');

    var p_gov  = getUrlParameter('field_government_value');
    var p_atsi = getUrlParameter('field_atsi_value');
   
    if(p_gov!=='All'){
      form.find('[name=field_government]').each(function(){
        var val = $(this).val();
        if(p_gov==val){
          $(this).prop('checked',true);
        }
      });
    }
   
    if(p_atsi==1){
      form.find('[name=field_atsi]').prop('checked',true);
    }
   
   
    form.find('.dummy.radio').each(function(){
      var elem = $(this);
      var par = elem.closest('.form-type-radio');
      var val = par.find('[type=radio]').prop('checked');
     
      // par.css('background','red');
      if(val==true){
        elem.addClass('checked');
      }
    });
   
    form.find('.dummy.checkbox').each(function(){
      var elem = $(this);
      var par = elem.closest('.form-type-checkbox');
      var val = par.find('[type=checkbox]').prop('checked');
     
      // par.css('background','red');
      if(val==true){
        elem.addClass('checked');
      }
    });
    
    form.on('click', '.dummy.radio', function(e){
      e.preventDefault();
      // console.log('radio');
      var elem = $(this);
      var par = elem.closest('.form-type-radio');
      par.find('[type=radio]').click();
      
      var nm = par.find('[type=radio]').attr('name');
      $('[name='+nm+']').each(function(){
        console.log('clear');
        var par2 = $(this).closest('.form-type-radio');
        var tg = par2.find('.dummy.radio');
            tg.removeClass('checked');
      });
      
      if(par.find('[type=radio]').is(':checked')){
        elem.addClass('checked');
      }else{
        elem.removeClass('checked');
      }
      
      // return false;
    });
    form.on('click', '.dummy.checkbox', function(e){
      e.preventDefault();
      // console.log('checkbox');
      var elem = $(this);
      var par = elem.closest('.form-type-checkbox');
      par.find('[type=checkbox]').click();
      
      if(par.find('[type=checkbox]').is(':checked')){
        elem.addClass('checked');
      }else{
        elem.removeClass('checked');
      }
      
      // return false;
    });
    
    form.on('click','label',function(){
      var elem = $(this);
      var par  = elem.closest('.form-item');
      var dumm = elem.find('.dummy');
      
      setTimeout(function(){
        if(par.find('[type=radio]').length>0){
          var nm = par.find('[type=radio]').attr('name');
          $('[name='+nm+']').each(function(){
            var par2 = $(this).closest('.form-type-radio');
            var tg = par2.find('.dummy.radio');
                tg.removeClass('checked');
          });
          
          if(par.find('[type=radio]').is(':checked')){
            dumm.addClass('checked');
          }else{
            dumm.removeClass('checked');
          }
        }

        if(par.find('[type=checkbox]').length>0){
          if(par.find('[type=checkbox]').is(':checked')){
            dumm.addClass('checked');
          }else{
            dumm.removeClass('checked');
          }
        }
      },100);
    });
  }
  
  function readjust_homepage_timeline_elems(){
    var timeoutID = 0;
    res_readjust_homepage_timeline_elems();
    $(window).resize(function(){
      clearTimeout(timeoutID);
      timeoutID = setTimeout(function(){
        res_readjust_homepage_timeline_elems();
      },150);
    });
  }
  
  function res_readjust_homepage_timeline_elems(){
    var cont = $('#homepage-timeline');
    var items = cont.find('.timeline-item');
    var yh = 0;
    var ih = 0;
    
    items.find('.timeline-item-yearlabel').removeAttr('style');
    items.find('.timeline-item-image').removeAttr('style');
    items.each(function(){
      var t_yh = $(this).find('.timeline-item-yearlabel').height();
      var t_ih = $(this).find('.timeline-item-image').height();
      if(t_yh>yh){ yh = t_yh; }
      if(t_ih>ih){ ih = t_ih; }
    });
    items.find('.timeline-item-yearlabel').css('height', yh+'px');
    items.find('.timeline-item-image').css('height', ih+'px');
  }
  
  
  function createCookie(name,value,days) {
      var expires = "";
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days*24*60*60*1000));
          expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + value + expires + "; path=/";
  }

  function readCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }

  function eraseCookie(name) {
      createCookie(name,"",-1);
  }

  var getUrlParameter = function getUrlParameter(sParam) {
      var sPageURL = decodeURIComponent(window.location.search.substring(1)),
          sURLVariables = sPageURL.split('&'),
          sParameterName,
          i;
 
      for (i = 0; i < sURLVariables.length; i++) {
          sParameterName = sURLVariables[i].split('=');
 
          if (sParameterName[0] === sParam) {
              return sParameterName[1] === undefined ? true : sParameterName[1];
          }
      }
  };
  
  function add_placeholder_text_on_mediarelease_field(){
    $('.view-rc-media-releases #edit-keys-wrapper [name=keys]').attr('placeholder','Search by keyword');
  }
}(jQuery, Drupal));
