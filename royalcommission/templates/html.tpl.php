<?php
/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   tag.
 * - $head_title_array: (array) An associative array containing the string parts
 *   that were used to generate the $head_title variable, already prepared to be
 *   output as TITLE tag. The key/value pairs may contain one or more of the
 *   following, depending on conditions:
 *   - title: The title of the current page, if any.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site, if any, and if there is no title.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 *
 * @ingroup themeable
 */
global $base_url;
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title><?php print $head_title; ?></title>
  <meta http-equiv="cleartype" content="on">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta property="og:title" content="<?php print $head_title; ?>"/>
  <?php print $head; ?>
  <link rel="stylesheet" href="/<?php echo drupal_get_path('theme', 'royalcommission'); ?>/css/bootstrap.min.css">
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
<div id="main-body">
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
  <div id="is_tablet"></div>
  <div id="is_mobile"></div>
</div>


<!-- Modal/Lightbox Royal Commission -->
<div class="modal fade" id="modalRC" tabindex="-1" role="dialog">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center" role="document">
      <div class="modal-content">
        <div class="close-btn-wrapper">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">Close<span aria-hidden="true"></span></button>
        </div>
        <div class="modal-body"></div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
</div><!-- /.modal -->

<!-- Extra element for accessibility -->
<p class="sr-only">
  <span class="sr-only content-live-region" aria-live="assertive">
    <span class="content-update hide">Content updating</span>
    <span class="content-complete">Updating complete</span>
  </span>
</p>

</body>
</html>
