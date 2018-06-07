<?php

/**
 * @file
 * One column stacked panel layout.
 */

/**
 * Implements hook_panels_layouts().
 */
// Plugin definition.
$plugin = array(
  'title' => t('Page Sidebar'),
  'category' => t('Royal Commission'),
  'icon' => 'sidebarnav.png',
  'theme' => 'rc-layouts-sidebarnav',
  'admin css' => 'sidebarnav.admin.css',
  'regions' => array(
    'top' => t('Top'),
    'top_left' => t('Top left'),
    'top_right' => t('Top right'),
    'content_top' => t('Content top'),
    'sidebar' => t('Sidebar'),
    'content' => t('Content'),
  ),
);
