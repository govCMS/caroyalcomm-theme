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
  'title' => t('Standard Page'),
  'category' => t('Royal Commission'),
  'icon' => 'standard.png',
  'theme' => 'rc-layouts-standard',
  'admin css' => 'standard.admin.css',
  'regions' => array(
    'top_left' => t('Top left'),
    'top_right' => t('Top right'),
    'content_top' => t('Content top'),    
    'content' => t('Content'),    
  ),
);
