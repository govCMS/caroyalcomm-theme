<?php
/**
 * @file
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and the
 * child template are dependent on one another, sharing the markup for
 * definition lists.
 *
 * Note that modules and themes may implement their own search type and theme
 * function completely bypassing this template.
 *
 * Available variables:
 * - $index: The search index this search is based on.
 * - $result_count: Number of results.
 * - $spellcheck: Possible spelling suggestions from Search spellcheck module.
 * - $search_results: All results rendered as list items in a single HTML
 *   string.
 * - $items: All results as it is rendered through search-result.tpl.php.
 * - $search_performance: The number of results and how long the query took.
 * - $sec: The number of seconds it took to run the query.
 * - $pager: Row of control buttons for navigating between pages of results.
 * - $keys: The keywords of the executed search.
 * - $classes: String of CSS classes for search results.
 * - $page: The current Search API Page object.
 * - $no_results_help: Help text to display under the header if no results were
 *   found.
 *
 * View mode is set in the Search page settings. If you select
 * "Themed as search results", then the child template will be used for
 * theming the individual result. Any other view mode will bypass the
 * child template.
 *
 * @see template_preprocess_search_api_page_results()
 */
?>
<div class="search-result-wrapper">
<?php if ($result_count): ?>
    <div class="search-info-wrapper">    
      <?php
      if($result_count < $page->options['per_page']) {
        ?>
        Showing <?php print $result_count; ?> results for <strong><?php print $keys; ?></strong>
        <?php
      } else {
//        $showing = intval($page->options['per_page']);
//        $remaining = $result_count - (intval($page->options['per_page']) * $page_current);
//        if($remaining < $showing) {
//          $showing = $remaining;
//        }

        $page_current = isset($_GET['page']) ? $_GET['page'] : 0;
        $page_number = $page_current + 1;
        $page_show_end = $page->options['per_page'] * $page_number;
        $page_show_start = $page_show_end - $page->options['per_page'] + 1;
        if ($page_show_end > $result_count) {
          $page_show_end = $result_count;
        }        
        
        print t('Showing @from-@to out of @result_count results found for <strong>@keys</strong>', array(
          '@from' => $page_show_start,
          '@to' => $page_show_end,
          '@result_count' => $result_count,
          '@keys' => $keys,
        ));
      } 
        ?>      
    </div>    
<?php endif; ?>
<?php print render($spellcheck); ?>
<?php if ($result_count): ?>
  <?php
  $search_term = arg(1);
  $search_term = str_replace('<', '', $search_term);
  $search_term = str_replace('>', '', $search_term);
  ?>  
  <ul class="search-results">
  <?php print render($search_results); ?>
  </ul>    
  <?php print render($pager); ?>
<?php else : ?>
  <h2><?php print t('No results found.'); ?></h2>
  <ul>
    <li>Check if your spelling is correct.</li>
    <li>Remove quotes around phrases to search for each word individually, <em>case study</em> will often show more results than <em>"case study"</em>.</li>
    <li>Use fewer keywords to increase the number of results.</li>
  </ul>
<?php endif; ?>
</div>  
