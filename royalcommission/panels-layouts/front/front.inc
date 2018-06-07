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
  'title' => t('Frontpage'),
  'category' => t('Royal Commission'),
  'icon' => 'front.png',
  'theme' => 'rc-layouts-front',
  'admin css' => 'front.admin.css',
  'regions' => array(
    'homepage_hero' => t('Homepage hero carousel'),
    'homepage_fastfact' => t('Homepage fast fact'),
    'homepage_commissioner' => t('Homepage about commissioner'),
    'homepage_timeline' => t('Homepage timeline'),
    'homepage_media' => t('Homepage media'),
  ),
);
