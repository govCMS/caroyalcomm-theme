<?php

define('GOVCMS_MIN_DOWNLOADS', 11000);
define('GOVCMS_MIN_PAGE_VIEWS', 200000);
define('GOVCMS_MIN_RELEASES', 18);
define('GOVCMS_MIN_AVAILABILITY', 98);
define('GOVCMS_MIN_PAGE_VISITS', 200000);
define('GOVCMS_MAX_PAGE_LOAD', 5.0);
define('GOVCMS_THEME', 'govCMS Theme');

/**
 * Page alter.
 */
function royalcommission_page_alter($page) {
  $mobileoptimized = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'MobileOptimized',
      'content' => 'width'
    )
  );
  $handheldfriendly = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'HandheldFriendly',
      'content' => 'true'
    )
  );
  $viewport = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1, maximum-scale=2, user-scalable=0'
    )
  );
  drupal_add_html_head($mobileoptimized, 'MobileOptimized');
  drupal_add_html_head($handheldfriendly, 'HandheldFriendly');
  drupal_add_html_head($viewport, 'viewport');
  drupal_add_library('system', 'ui.draggable');
  drupal_add_library('system', 'ui.droppable');
  drupal_add_library('system', 'ui.sortable');
}

/**
 * Override or insert variables into the html template.
 */
function royalcommission_process_html(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}

function royalcommission_preprocess_html(&$vars) {
  $path = drupal_get_path_alias();
  $aliases = explode('/', $path);
  drupal_add_js('jQuery.extend(Drupal.settings, { "pathToTheme": "' . path_to_theme() . '" });', 'inline');

  foreach ($aliases as $alias) {
    if ($alias == 'search') {
      $vars['classes_array'][] = 'search-results';
    }
  }

  $node = menu_get_object();
  if ($node) {
    if ($node->type == 'rc_media_releases') {
      // TODO Making this configurable?
      $type_speeches = 3;

      $field_rc_type = field_get_items('node', $node, 'field_rc_type');
      if (isset($field_rc_type[0]['tid'])) {
        $rc_type = $field_rc_type[0]['tid'];
        if ($rc_type == $type_speeches) {
          $vars['classes_array'][] = 'media-releases-speeches';
        }
      }
    }
  }
}

/**
 * For stripe.com style sub menu
 */
function royalcommission_preprocess_page(&$variables) {
  $node = menu_get_object();
  if ($node) {
    $variables['theme_hook_suggestions'][] = 'page__' . $node->type;

    // Hero image.
    $field_node_hero_image = field_get_items('node', $node, 'field_node_hero_image');
    if ($field_node_hero_image) {
      $image_uri = $field_node_hero_image[0]['uri'];
      $style = 'hero_image_small';
      $derivative_uri = image_style_path($style, $image_uri);
      if (file_exists($derivative_uri) == FALSE) {
        image_style_create_derivative(image_style_load($style), $image_uri, $derivative_uri);
      }
      $variables['hero_image_url'] = file_create_url($derivative_uri);
    }
    else {
      // Provide default hero image
      $path = drupal_get_path('theme', 'royalcommission');

      if ($node->type === 'private_session') {
        $image_path = '/' . $path . '/img/hero_image/header_private_sessions.jpg';
        $variables['hero_image_url'] = $image_path;
      }
      else if ($node->type === 'rc_media_releases') {
        $image_path = '/' . $path . '/img/hero_image/header_research_and_resources.jpg';
        $variables['hero_image_url'] = $image_path;
      }
      else if ($node->type === 'og_case_study') {
        $image_path = '/' . $path . '/img/hero_image/header_final_report2.jpg';
        $variables['hero_image_url'] = $image_path;
      }
    }

    // private session title
    if ($node->type == 'private_session') {
      $variables['hero_title'] = $node->title;
      if (!empty($node->field_private_session_name)) {
        $person_name = $node->field_private_session_name[LANGUAGE_NONE][0]['value'];
        $variables['hero_title'] = $person_name . '\'s story';
      }
    }
  }

  if (strpos(current_path(), 'search/') !== FALSE) {
    $variables['page']['content']['system_main']['form']['#access'] = FAlSE;
    drupal_add_js(drupal_get_path('theme', 'royalcommission') . '/js/jquery.highlight.js');
  }
}

/**
 * Preprocess variables for block.tpl.php
 */
function royalcommission_preprocess_block(&$variables) {
  $variables['classes_array'][] = 'clearfix';
}

/**
 * Implements template_preprocess_field().
 */
function royalcommission_preprocess_field(&$variables) {
  $node = menu_get_object();
  if ($node && isset($node->type) && $node->type === 'private_session') {
    $all_taxonomy = array();
    $link_attributes = array(
      'attributes' => array(
        'class' => array('ticked-tags-item'),
      )
    );

    // Private session category
    $field_private_session_category = field_get_items('node', $node, 'field_private_session_category');
    if (isset($field_private_session_category)) {
      foreach($field_private_session_category as $cat_tid) {
        $term_category = taxonomy_term_load($cat_tid['tid']);
        $elem = l($term_category->name, '/narratives',
                $link_attributes + array(
                  'query' => array(
                    'category' => $term_category->tid
                  )
                ));
        $all_taxonomy[] = $elem;
      }
    }

    // Gender
    $field_private_session_gender = field_get_items('node', $node, 'field_private_session_gender');
    if (count($field_private_session_gender) > 0) {
      foreach ($field_private_session_gender as $key => $ps_gender) {
        if (isset($field_private_session_gender[$key]['value'])) {
          $gender_title = $ps_gender['value'];
          if ($gender_title == 'Other') {
            $gender_title = 'Other gender';
          }
          if ($gender_title == 'Both') {
            $gender_title = 'Male and female';
          }
          $elem = l($gender_title, '/narratives',
                  $link_attributes + array(
                    'query' => array(
                      'field_private_session_gender_value' => $ps_gender['value']
                    )
                  ));
          $all_taxonomy[] = $elem;
        }    
      }
    }

    // State
    $field_state = field_get_items('node', $node, 'field_state');
    if (isset($field_state[0]['value'])) {
      $state_title = $field_state[0]['value'];
      if ($state_title != 'Unknown') {
        $elem = l($state_title, '/narratives',
                $link_attributes + array(
                  'query' => array(
                    'field_state_value' => $field_state[0]['value']
                  )
                ));
        $all_taxonomy[] = $elem;
      }
    }

    // Year
    $field_decade = field_get_items('node', $node, 'field_decade');
    if ($field_decade[0]['value']) {
      $decade_title = $field_decade[0]['value'];
      if($decade_title != 'Unknown') {
        $decade_title .= 's';
        $elem = l($decade_title, '/narratives',
                $link_attributes + array(
                  'query' => array(
                    'field_decade_value' => $field_decade[0]['value']
                  )
                ));
        $all_taxonomy[] = $elem;
      }
    }

    // Pre / Post.
    $field_prepost = field_get_items('node', $node, 'field_private_session_pre_post');
    if (isset($field_prepost[0]['value'])) {
      $prepost_title = $field_prepost[0]['value'];
      if(isset($field_decade[0]['value'])) {
        $field_decade_text = $field_decade[0]['value'];
        if ($field_decade_text != 'Unknown') {
          $prepost_title .= '-1990';
          $all_taxonomy[] = '<span class="ticked-tags-item tags-bold">' . $prepost_title . '</span>';
        }
      }

    }

    $tags_html = '<h4>' . t('Tags') . '</h4>';
    $tags_html .= '<div class="ticked-tags">';
    foreach ($all_taxonomy as $ticked) {
      $tags_html .= $ticked;
    }
    $tags_html .= '</div>';

    $variables['private_sessions_ticked_taxonomy'] = $tags_html;
    $variables['items'][]['#markup'] = $tags_html;
  }
}

/**
 * Preprocess Views exposed form
 */
function royalcommission_preprocess_views_exposed_form(&$vars, $hook) {

  // Load jQuery highlight plugin on views filter.
  drupal_add_js(drupal_get_path('theme', 'royalcommission') . '/js/jquery.highlight.js');

  if (strrpos($vars['form']['#id'], 'views-exposed-form', -strlen($vars['form']['#id'])) !== FALSE) {
    $vars['form']['submit']['#attributes']['class'] = array('btn btn-info');
    $vars['form']['submit']['#value'] = "Filter";
    $vars['form']['reset']['#attributes']['class'] = array('btn btn-info');

    $form_ids = array(
      'views-exposed-form-rc-media-releases-page',
      'views-exposed-form-rc-media-releases-block-1',
      'views-exposed-form-rc-media-releases-block-2',
      'views-exposed-form-document-listing-panel-document-listing',
      'views-exposed-form-og-case-study-page',
      'views-exposed-form-og-case-study-panel-case-study',
      'views-exposed-form-private-sessions-panel-pane-private-session',
      'views-exposed-form-media-gallery-panel-pane-media-gallery',
    );

    if (in_array($vars['form']['#id'], $form_ids)) {
      $vars['form']['submit']['#value'] = t("Go");
      $vars['form']['reset'] = array(
        '#type' => 'markup',
        '#markup' => '<div class="views-exposed-widget views-reset-button"><input class="btn btn-info form-submit" type="button" id="clear-search" name="op" value="Clear search"></div>',
      );
      $vars['form']['#attributes']['class'][] = 'views-filter-style';
    }

    // Change Filter button.
    if ($vars['form']['#id'] == 'views-exposed-form-media-gallery-panel-pane-media-gallery') {
      $vars['form']['submit']['#value'] = t("Go");
    }
    else if ($vars['form']['#id'] == 'views-exposed-form-private-sessions-panel-pane-private-session') {
      // RCWBUA-150: Somehow, checkboxex and radio button is flipped, this code is to adjust.
      $widgets = $vars['widgets'];
      $elem_keys = array_keys($widgets);
      $elem_key1 = array_pop($elem_keys);
      $elem_key2 = array_pop($elem_keys);
      array_push($elem_keys, $elem_key1, $elem_key2);

      $new_widgets = array();
      foreach ($elem_keys as $key) {
        $new_widgets[$key] = $widgets[$key];
      }
      $vars['widgets'] = $new_widgets;

      // RCWBUA-208 #3: If there are still State or Decade with value `Unknown`,
      //  then set it to All.
      $field_with_unknown = array('field_state_value', 'field_decade_value');
      foreach ($field_with_unknown as $f_with_unknown) {
        if ($vars['form'][$f_with_unknown]['#value'] === 'Unknown') {
          $state_1st_opt = current(array_keys($vars['form'][$f_with_unknown]['#options']));
          $vars['form'][$f_with_unknown]['#value'] = $state_1st_opt;
        }

        // This is an attempt to remove the unknown value. 
        // Not fully working, must be removed from backend.
//        foreach ($vars['form'][$f_with_unknown]['#options'] as $opt_k => &$opt_v) {
//          if (strtolower($opt_k) === 'unknown') {
//            unset($vars['form'][$f_with_unknown]['#options'][$opt_k]);
//            continue;
//          }
//        }
      }
    }

    unset($vars['form']['submit']['#printed']);
    unset($vars['form']['reset']['#printed']);
    $vars['button'] = drupal_render($vars['form']['submit']);
    $vars['reset_button'] = drupal_render($vars['form']['reset']);
  }
}

/**
 * Alter default jquery.
 */
function royalcommission_js_alter(&$javascript) {
  $node_admin_paths = array(
    'node/*/edit',
    'node/add',
    'node/add/*',
  );
  $replace_jquery = TRUE;
  if (path_is_admin(current_path())) {
    $replace_jquery = FALSE;
  }
  else {
    foreach ($node_admin_paths as $node_admin_path) {
      if (drupal_match_path(current_path(), $node_admin_path)) {
        $replace_jquery = FALSE;
      }
    }
  }

  global $base_url;

  // Swap out jQuery to use an updated version of the library.
  if ($replace_jquery) {
    //$javascript['misc/jquery.js']['data'] = '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js';
    $javascript['misc/jquery.js']['data'] = drupal_get_path('theme', 'royalcommission') . '/js/jquery.min.js';
    $javascript['misc/jquery.js']['version'] = '1.9.1';
    $javascript['misc/jquery.js']['preprocess'] = FALSE;
    //$javascript['misc/ui/jquery.ui.core.min.js']['data'] = '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js';
    $javascript['misc/ui/jquery.ui.core.min.js']['data'] = drupal_get_path('theme', 'royalcommission') . '/js/jquery-ui.min.js';
    $javascript['misc/ui/jquery.ui.core.min.js']['version'] = '1.10.2';
    $javascript['misc/ui/jquery.ui.core.min.js']['preprocess'] = FALSE;
    unset($javascript['misc/ui/jquery.ui.widget.min.js']);
    unset($javascript['misc/ui/jquery.ui.mouse.min.js']);
    unset($javascript['misc/ui/jquery.ui.draggable.min.js']);
    unset($javascript['misc/ui/jquery.ui.droppable.min.js']);
    unset($javascript['misc/ui/jquery.ui.sortable.min.js']);
  }

  // Set async on twitter.
  foreach ($javascript as $name => &$values) {
    // Skip if not external.
    if ($values['type'] != 'external') {
      continue;
    }
    // Set async to true if file is connect.facebook.net/en_US/all.js
    if (strpos($name, 'platform.twitter.com/widgets.js') !== FALSE) {
      $values['async'] = TRUE;
      // Stop processing once we've modified twitter widget.js.
      break;
    }
  }
}

/**
 * Alter html tag.
 */
function royalcommission_html_tag($vars) {
  if ($vars['element']['#tag'] == 'script') {
    unset($vars['element']['#value_prefix']);
    unset($vars['element']['#value_suffix']);
  }

  return theme_html_tag($vars);
}

/**
 * Alter default main manu structure.
 */
function royalcommission_menu_tree__main_menu($variables) {
  return '<ul class="nav main-nav">' . $variables['tree'] . '</ul>';
}

/**
 * Alter main menu item structure.
 */
function royalcommission_menu_link__main_menu($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $below = $element['#below'];
    $menu_items = array();
    foreach ($below as $mkey => $menu_item) {
      if (is_numeric($mkey)) {
        $menu_items[$mkey] = $menu_item;
      }
    }
    $menu_cols = array();
    $menu_item_count = count($menu_items);
    if ($menu_item_count < 3) {
      $divider = $menu_item_count % 3;
      $menu_cols = array_chunk($menu_items, $divider, true);
    }
    else {
      $col = array();
      $col[1] = (int) floor($menu_item_count / 3) + (($menu_item_count % 3) >= 1 ? 1 : 0);
      $col[2] = (int) floor($menu_item_count / 3) + (($menu_item_count % 3) >= 2 ? 1 : 0);
      $col[3] = (int) floor($menu_item_count / 3) + (($menu_item_count % 3) >= 3 ? 1 : 0);
      // Create menu item.
      $menu_cols[0] = array_splice($menu_items, 0, $col[1]);
      $menu_cols[1] = array_splice($menu_items, 0, $col[2]);
      $menu_cols[2] = array_splice($menu_items, 0, $col[3]);
    }

    if ($menu_cols) {
      $sub_menu = '<div class="submenu-wrapper"><div class="container"><div class="row"><div class="submenu-wrapper-container"><ul class="clearfix">';
      foreach ($menu_cols as $menu_col => $menu_col_item) {
        if (!empty($menu_cols[$menu_col])) {
          $sub_menu .= drupal_render($menu_cols[$menu_col]);
        }
      }
      $sub_menu .= '</ul></div></div></div><a href="#" class="close-submenu">Close <span class="x-icon"></span></a></div>';
    }
    $output = '<a href="' . url($element['#href']) . '" aria-expanded="false" role="button">' . $element['#title'] . '</a>';
  }
  else {
    $sub_menu = drupal_render($element['#below']);
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  }

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function royalcommission_menu_link__menu_footer_menu($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
    $output = '<a href="#" class="dropdown-footer">' . $element['#title'] . '</a>';
  }
  else {
    $sub_menu = drupal_render($element['#below']);
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  }

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Form alter
 * @param array $form
 * @param type $form_state
 * @param type $form_id
 */
function royalcommission_form_alter(&$form, &$form_state, $form_id) {
  if (!empty($form['actions']) && $form['actions']['submit']) {
    $form['actions']['submit']['#attributes'] = array(
      'class' => array(
        'btn',
        'btn-primary'
      )
    );
  }

  // Search block alter.
  if ($form_id == 'search_block_form') {
    if (isset($form['search_block_form'])) {
      $form['search_block_form']['#attributes']['placeholder'] = t('Search');
      $form['#submit'][] = 'royalcommission_search_block_form_submit';
    }
  }

  if ($form_id == 'search_api_page_search_form_default_search') {
    $form['keys_1']['#attributes']['placeholder'] = t('Search');
  }

  // Hide search form in search results listing.
  if ($form_id == 'search_form') {
    unset($form['basic']);
    unset($form['advanced']);
  }
  // Replace -Any- text with the filter label.
  if ($form['#form_id'] === 'views_exposed_form') {
    foreach ($form['#info'] as $filter_info) {
      $filter = $filter_info['value'];
      if (isset($form[$filter]['#type']) && $form[$filter]['#type'] == 'select') {
        $form[$filter]['#options']['All'] = $filter_info['label'];
      }
      else if ($filter_info['value'] === 'title') {
        $form['title']['#attributes']['placeholder'] = t('Search by keyword');
      }
    }
  }

  // Alter document listing filter.
  if ($form['#id'] == 'views-exposed-form-document-listing-panel-document-listing') {
    $form['type']['#options']['All'] = t('Document type');
    $form['category']['#options']['All'] = t('Category');
    $form['topic']['#options']['All'] = t('Topic');
    $form['keys']['#attributes']['placeholder'] = t('Search by keyword');
  }

  // Alter media gallery form.
  if ($form['#id'] == 'views-exposed-form-media-gallery-panel-pane-media-gallery') {
    $form['type']['#options']['All'] = t('All');
    $form['type']['#type'] = 'radios';
    $form['category']['#options']['All'] = t('Category');
    $form['topic']['#options']['All'] = t('Topic');
    $form['keyword']['#attributes']['placeholder'] = t('Search by keyword');
  }

  // Private sessions.
  if ($form['#id'] == 'views-exposed-form-private-sessions-panel-pane-private-session') {
    $form['category']['#options']['All'] = t('Institution');
    $form['topic']['#options']['All'] = t('Topic');
    $form['keyword']['#attributes']['placeholder'] = t('Enter keyword');

    $form['field_atsi_value']['#value'] = isset($form_state['input']['field_atsi_value']) ? $form_state['input']['field_atsi_value'] : 'All';
    $field_atsi_html = '<div class="form-item form-type-checkbox">
      <input type="checkbox" id="field-atsi" name="field_atsi" value="1" class="form-checkbox">
      <label class="option" for="field-atsi"">Aboriginal and Torres Strait Islander </label>
    </div>';
    $form['field_atsi_value']['#field_suffix'] = $field_atsi_html;

    $form['field_government_value']['#value'] = isset($form_state['input']['field_government_value']) ? $form_state['input']['field_government_value'] : 'All';
    $field_gov_html = '<div class="form-radios">
        <div class="form-item form-type-radio">
          <input type="radio" id="field-government-1" name="field_government" value="1" class="form-radio">
          <label class="option" for="field-government-1">Government </label>
        </div>
        <div class="form-item form-type-radio">
          <input type="radio" id="field-government-0" name="field_government" value="0" class="form-radio">
          <label class="option" for="field-government-0">Non-Government </label>
        </div>
    </div>';
    $form['field_government_value']['#field_suffix'] = $field_gov_html;

    $opt_year = array('' => 'Year');
    $start_year = 1900;
    $current_year = 2020;
    for ($year = $current_year; $year > $start_year; $year = $year - 10) {
      $opt_year[$year] = $year;
    }

    $form['field_private_session_year_value']['#type'] = 'select';
    $form['field_private_session_year_value']['#options'] = $opt_year;
    $form['field_private_session_year_value']['#value'] = '';

    // To prevent illegal choice of element.
    $form['field_private_session_year_value']['#value'] = isset($form_state['input']['field_private_session_year_value']) ? $form_state['input']['field_private_session_year_value'] : '';
  }

  if ($form['#id'] == 'views-exposed-form-rc-media-releases-block-2' || $form['#id'] == 'views-exposed-form-rc-media-releases-block-1') {
    $old_year = 2013;
    $current_year = date('Y');
    $diff_year = (int) $current_year - $old_year;
    $form['field_rc_date_value']['value']['#date_year_range'] = '-0:-' . $diff_year;
  }

}

function royalcommission_search_block_form_submit($form, &$form_state) {
  $form_state['redirect'] = array('search/' . $form_state['values']['search_block_form']);
}

/**
 * Implements theme_breadcrumb().
 */
function royalcommission_breadcrumb($variables) {
  $menu_active = menu_get_active_trail();
  $breadcrumb = $variables['breadcrumb'];

  // Modify breadcrumb on search/* page.
  if (strpos(current_path(), 'search') !== FALSE) {
    unset($breadcrumb);
    $breadcrumb = array(
      '<a href="/">Home</a>',
      t('Search')
    );

    // Set breadcrumb if search key entered.
    $key = '';
    if (isset($_GET['q'])) {
      $keys = explode('/', $_GET['q']);
      $key =  strip_tags(filter_xss($keys[1]));
    }
    if ($key != '') {
      $breadcrumb = array(
        '<a href="/">Home</a>',
        t('Search'),
        $key,
      );
    }
  }

  // Based on main menu trail.
  if ($menu_active && strpos(current_path(), 'search') === FALSE) {
    $breadcrumb = array();
    foreach ($menu_active as $menu_index => $menu_active_trail) {
      if ($menu_index > 0) {
        if (isset($menu_active_trail['link_path']) && $menu_active_trail['link_path'] == '<front>') {
          $breadcrumb[] = $menu_active_trail['title'];
        }
        else {
          if ($menu_active_trail == end($menu_active)) {
            $breadcrumb[] = $menu_active_trail['title'];
          }
          else {
            $breadcrumb[] = l($menu_active_trail['title'], $menu_active_trail['href']);
          }
        }
      }
      else {
        $breadcrumb[] = l($menu_active_trail['title'], $menu_active_trail['href']);
      }
    }
  }

  // Based on node type.
  $node = menu_get_object();
  if ($node) {
    $node_title = $node->title;
    // Alter private session title
    if ($node->type == 'private_session') {
      if (!empty($node->field_private_session_name)) {
        $person_name = $node->field_private_session_name[LANGUAGE_NONE][0]['value'];
        $node_title = $person_name . '\'s story';
      }
    }
    $breadcrumb_nodetype = array(
      'og_case_study' => array(
        $breadcrumb[0],
        l('Final Report', 'final-report'),
        l('Case Studies', 'case-studies'),
        $node_title
      ),
      'rc_media_releases' => array(
        $breadcrumb[0],
        l('Research & Resources', 'research-and-resources'),
        l('Media Releases', 'media-releases'),
        $node_title
      ),
      'private_session' => array(
        $breadcrumb[0],
        l('Private Sessions', 'private-sessions'),
        l('Narratives', 'narratives'),
        $node_title
      ),
    );

    if(isset($node->field_rc_type) && $node->type == 'rc_media_releases') {
      $tid = $node->field_rc_type[LANGUAGE_NONE][0]['tid'];
      $term = taxonomy_term_load($tid);
      if($term && strtolower($term->name) == 'speeches') {
        $breadcrumb_nodetype[$node->type][2] =  l('Speeches', 'speeches');
      }
    }

    if (isset($breadcrumb_nodetype[$node->type])) {
      unset($breadcrumb);
      $breadcrumb = $breadcrumb_nodetype[$node->type];
    }
  }

  if (!empty($breadcrumb)) {
    $output = '';

    if (isset($breadcrumb[0])) {
      $breadcrumb[0] = '<span class="element-invisible">' . t('You are here') . '</span>' . $breadcrumb[0];
    }    
    
    $output .= theme('item_list', array(
      'attributes' => array(
        'class' => array('breadcrumb'),
      ),
      'items' => $breadcrumb,
      'type' => 'ul',
    ));
    return $output;
  }
}

/**
 * Render share and print block.
 */
function _royalcommission_render_share() {
  $block_shareprint = module_invoke('block', 'block_view', 1);
  if ($block_shareprint) {
    return $block_shareprint['content'];
  }
  return '';
}

/**
 * Implements theme_file_link().
 */
function royalcommission_file_link($variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];

  $url = file_create_url($file->uri);

  // Human-readable names, for use as text-alternatives to icons.
  $mime_name = array(
    'application/msword' => t('Microsoft Office document icon'),
    'application/vnd.ms-excel' => t('Office spreadsheet icon'),
    'application/vnd.ms-powerpoint' => t('Office presentation icon'),
    'application/pdf' => t('PDF icon'),
    'video/quicktime' => t('Movie icon'),
    'audio/mpeg' => t('Audio icon'),
    'audio/wav' => t('Audio icon'),
    'image/jpeg' => t('Image icon'),
    'image/png' => t('Image icon'),
    'image/gif' => t('Image icon'),
    'application/zip' => t('Package icon'),
    'text/html' => t('HTML icon'),
    'text/plain' => t('Plain text icon'),
    'application/octet-stream' => t('Binary Data'),
  );

  $mimetype = file_get_mimetype($file->uri);

  $mime_to_ext = array(
    'application/pdf' => 'PDF',
    'text/plain' => 'TXT',
    'application/msword' => 'DOC',
    'application/vnd.ms-excel' => 'XLS',
    'application/vnd.ms-powerpoint' => 'PPT',
  );

  // Link options.
  $options = array(
    'attributes' => array(
      'type' => $file->filemime . '; length=' . $file->filesize,
      'target' => '_blank',
      'class' => array(
        'btn-download'
      )
    ),
    'html' => true,
  );

  // Get filesize.
  $file_size = _royalcommission_filesize($file->filesize);

  // Use custom link text.
  if (isset($mime_to_ext[$mimetype])) {
    $link_text = t('<span class="file-download-text">Download</span><span class="class="file-meta">@ext @filesize</span>', array('@ext' => $mime_to_ext[$mimetype], '@filesize' => $file_size));
    $options['attributes']['aria-label'] = t('Download @ext @filesize', array('@ext' => $mime_to_ext[$mimetype], '@filesize' => $file_size));
  }
  else {
    $link_text = t('<span class="file-download-text">Download</span><span class="class="file-meta">@ext @filesize</span>', array('@filesize' => $file_size));
    $options['attributes']['aria-label'] = t('Download File @filesize', array('@filesize' => $file_size));
  }

  return l($link_text, $url, $options);
}

/**
 * Helper for filesize
 */
function _royalcommission_filesize($bytes) {
  if ($bytes >= 1073741824) {
    $bytes = number_format($bytes / 1073741824, 0) . ' GB';
  }
  elseif ($bytes >= 1048576) {
    $bytes = number_format($bytes / 1048576, 0) . ' MB';
  }
  elseif ($bytes >= 1024) {
    $bytes = number_format($bytes / 1024, 0) . ' KB';
  }
  elseif ($bytes > 1) {
    $bytes = $bytes . ' bytes';
  }
  elseif ($bytes == 1) {
    $bytes = $bytes . ' byte';
  }
  else {
    $bytes = '0 bytes';
  }

  return $bytes;
}

/**
 * Implements hook_preprocess_entity().
 */
function royalcommission_preprocess_entity(&$variables) {
  if ($variables['entity_type'] == 'paragraphs_item') {
    $bundle = $variables['elements']['#bundle'];
    if ($bundle === 'og_file_list') {
      $variables['classes_array'][] = 'panel panel-accordion';

      // Background color.
      $field_fl_color = field_get_items('paragraphs_item', $variables['paragraphs_item'], 'field_fl_color');
      if ($field_fl_color && $field_fl_color[0]['value']) {
        $variables['fl_bg_color'] = $field_fl_color[0]['value'];
      }

      // Title.
      $field_fl_title = field_get_items('paragraphs_item', $variables['paragraphs_item'], 'field_fl_title');
      if ($field_fl_title && $field_fl_title[0]['value']) {
        $variables['fl_title'] = $field_fl_title[0]['value'];
      }
      else {
        $variables['fl_title'] = '';
      }

      // Description.
      $field_fl_description = field_get_items('paragraphs_item', $variables['paragraphs_item'], 'field_fl_description');
      if ($field_fl_description && $field_fl_description[0]['value']) {
        $variables['fl_description'] = $field_fl_description[0]['value'];
      }
      else {
        $variables['fl_description'] = '';
      }
    }
  }

  if ($variables['entity_type'] == 'bean') {
    $bundle = $variables['elements']['#bundle'];

    if ($bundle == 'fast_facts') {
      $variables['content']['no_animation'] = FALSE;
      if (isset($variables['content']['field_fast_facts_disable_animate'])) {
        $disable_animate = $variables['content']['field_fast_facts_disable_animate']['#items'][0]['value'];
        if ($disable_animate == 1) {
          $variables['content']['no_animation'] = TRUE;
        }
        unset($variables['content']['field_fast_facts_disable_animate']);
      }
    }
  }
}

/**
 * Implement template_preprocess_search_api_page_result().
 */
function royalcommission_preprocess_search_api_page_result(&$variables) {
  // Alter search date
  $node = $variables['item'];
  $variables['url']['path'] = url($variables['url']['path']);
  if (isset($node)) {
    $preview_text = '';
    if (!empty($variables['snippet'])) {
      if(strlen($variables['snippet']) > 255) {
        $preview_text = preg_replace('/\s+?(\S+)?$/', '', substr($variables['snippet'], 0, 255)) . ' ...';
      } else {
        $preview_text = $variables['snippet'];
      }
    }

    $search_item_date = format_date($node->created, 'custom', 'd F Y', NULL ,$langcode = NULL);
    $variables['info_split']['date'] = $search_item_date;
    $variables['dateexist'] = true;
    // Alter document result.
    if ($node->type == 'document') {
      // Document category
      if (isset($node->field_document_type)) {
        $categories = array();
        foreach ($node->field_document_type[LANGUAGE_NONE] as $doc_cat) {
          $doc_cat_term = taxonomy_term_load($doc_cat['tid']);
          $categories[] = array(
            'name' => $doc_cat_term->name,
            'color' => isset($doc_cat_term->field_category_color) ? $doc_cat_term->field_category_color[LANGUAGE_NONE][0]['value'] : 'white',
          );
        }


        if ($categories) {
          $category_html = '';
          foreach ($categories as $category) {
            $category_html .= '<span style="background-color:' . $category['color'] . '">' . $category['name'] . '</span>';
          }
          $variables['info_split']['category'] = $category_html;
        }
      }
      // Document file.
      if (isset($node->field_document_file)) {
        $file_object = (object) $node->field_document_file[LANGUAGE_NONE][0];
        if(isset($file_object->field_file_date[LANGUAGE_NONE][0]['value']) && !empty($file_object->field_file_date[LANGUAGE_NONE][0]['value'])) {
          $search_item_date = format_date(strtotime($file_object->field_file_date['und'][0]['value']), 'custom', 'd F Y', NULL ,$langcode = NULL);
          $variables['info_split']['date'] = $search_item_date;
          $variables['dateexist'] = true;
        } else {
          $variables['dateexist'] = false;
        }
        $variables['url']['path'] = file_create_url($file_object->uri);
        $variables['info_split']['file'] = theme('file_link', array('file' => $file_object));
      }
    }

    if ($node->type == 'rc_media_releases') {
      if (!empty($node->field_rc_date)) {
        $media_release_date = $node->field_rc_date[LANGUAGE_NONE][0]['value'];
        $media_item_date = format_date(strtotime($media_release_date), 'custom', 'd F Y', NULL ,$langcode = NULL);
        $variables['info_split']['date'] = $media_item_date;
      }
    }

    if ($node->type == 'private_session') {
      $variables['info_split']['date'] = '';
      $variables['content_warning'] = '<div class="content-warning">' 
          . '<strong>Content warning: This story is about child sexual abuse. It may contain graphic descriptions and strong language, and may be confronting and disturbing. If you need help, please see <a href="/contact-us">support services</a>.</strong>' 
          . '</div>';
      $variables['snippet'] = $preview_text;
    }

  }
}

/**
 * Implements template_preprocess_search_api_page_results().
 */
function royalcommission_preprocess_search_api_page_results(&$variables) {
  $minimun_word_size = variable_get('minimum_word_size', 3);
  if (strlen($variables['keys']) < $minimun_word_size) {
    $variables['result_count'] = 0;
  }
}

/**
 * Royal commission prev next.
 */
function _royalcommission_render_prevnext() {
  $params = drupal_get_query_parameters();
  $view = views_get_view('private_sessions');
  $view->set_display('private_prev_next');

  $filters = $view->display_handler->get_option('filters');

  $querystring = '?';

  if (isset($params['category'])) {
    $filters['category']['value'] = $params['category'];
    $querystring .= 'category=' . $params['category'] . '&';
  }
  if (isset($params['field_private_session_gender_value'])) {
    $filters['field_private_session_gender_value']['value'] = $params['field_private_session_gender_value'];
    $querystring .= 'field_private_session_gender_value=' . $params['field_private_session_gender_value'] . '&';
  }
  if (isset($params['field_state_value'])) {
    $filters['field_state_value']['value'] = $params['field_state_value'];
    $querystring .= 'field_state_value=' . $params['field_state_value'] . '&';
  }
  if (isset($params['field_decade_value'])) {
    $filters['field_decade_value']['value'] = $params['field_decade_value'];
    $querystring .= 'field_decade_value=' . $params['field_decade_value'] . '&';
  }
  if (isset($params['field_government_value'])) {
    $filters['field_government_value']['value'] = $params['field_government_value'];
    $querystring .= 'field_government_value=' . $params['field_government_value'] . '&';
  }
  if (isset($params['field_atsi_value'])) {
    $filters['field_atsi_value']['value'] = $params['field_atsi_value'];
    $querystring .= 'field_atsi_value=' . $params['field_atsi_value'] . '&';
  }
  $start_from_zero = TRUE;
  if (isset($params['next'])) {
    $querystring .= 'next=' . $params['next'];
    $start_from_zero = FALSE;
  }
  //dpr($querystring);

  $view->display_handler->set_option('filters', $filters);
  //dpr($filters );

  $view->pre_execute();
  $output = $view->display_handler->preview();
  $view->post_execute();

  $result = $view->result;

  $html_prevnext = '<div class="prev-next">';

  if ($result) {
    $nids = array();
    foreach ($result as $nid) {
      $nids[] = intval($nid->nid);
    }
    //dpr($nids);

    if ($start_from_zero) {
      $querystring .= 'next=1';
      $url_next = url('node/' . $nids[0]) . $querystring;
      $has_next = TRUE;
    }
    else {
      $node = menu_get_object();
      $has_prev = FALSE;

      if ($node & $nids) {
        $key = array_search($node->nid, $nids);
        if ($querystring == '?') {
          $querystring = '';
        }
        if ($key == 0) {
          // Show next only.
          $nid_next = $nids[$key + 1];
          $url_next = url('node/' . $nid_next) . $querystring;
          $has_next = TRUE;
        }
        else if ($key == count($nids) - 1) {
          // Show prev only.
          $nid_prev = $nids[$key - 1];
          $url_prev = url('node/' . $nid_prev) . $querystring;
          $has_prev = TRUE;
        }
        else {
          $nid_prev = $nids[$key - 1];
          $nid_next = $nids[$key + 1];
          $url_next = url('node/' . $nid_next) . $querystring;
          $url_prev = url('node/' . $nid_prev) . $querystring;
          $has_prev = TRUE;
          $has_next = TRUE;
        }
      }
    }
  }
  if ($has_prev) {
    $html_prevnext .= '<div class="prev-next-wrapper prev"><a href="' . $url_prev . '">Previous</div>';
  }
  if ($has_next) {
    $html_prevnext .= '<div class="prev-next-wrapper next"><a href="' . $url_next . '">Next</div>';
  }

  $html_prevnext .= '</div>';

  print $html_prevnext;
}

/**
 * Implements hook_preprocess_status_messages().
 */
function royalcommission_preprocess_status_messages(&$variables) {
  // Fixix RCWBUA-275.
  $arg0 = arg(0);
  if (!empty($arg0) && $arg0 == 'search') {
    if (count($_SESSION['messages']['warning']) > 2) {
      $keys = array_keys($_SESSION['messages']['warning'], 
        t('No valid search keys were present in the query.'));

      if (count($keys) > 1) {
        // Only use last, remove everything.
        array_pop($keys);
        foreach ($keys as $key) {
          if (isset($_SESSION['messages']['warning'][$key])) {
            unset($_SESSION['messages']['warning'][$key]);
            if (isset($_SESSION['messages']['warning'][$key-1])) {
              unset($_SESSION['messages']['warning'][$key-1]);
            }
          }
        }
      }
    }
  }
}
