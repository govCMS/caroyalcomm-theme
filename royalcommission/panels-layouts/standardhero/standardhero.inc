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
  'title' => t('Standard Page with Hero'),
  'category' => t('Royal Commission'),
  'icon' => 'standardhero.png',
  'theme' => 'rc-layouts-standardhero',
  'admin css' => 'standardhero.admin.css',
  'regions' => array(
    'top' => t('Top'),
    'top_left' => t('Top left'),
    'top_right' => t('Top right'),    
    'content_top' => t('Content top'),
    'content' => t('Content'),    
  ),
);
