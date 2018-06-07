<?php
// File link url.
$file_url = '#';
if (isset($fields['field_document_file_1'])) {
  $file_url = $fields['field_document_file_1']->content;
}

$doc_category_html = '<div class="item-list"><ul>';
$doc_category = isset($fields['field_document_type']) ? $fields['field_document_type']->content : '';
if ($doc_category != '') {
  $doc_cat_list = explode(",", $doc_category);
  foreach($doc_cat_list as $doc_cat) {
    $term = taxonomy_get_term_by_name($doc_cat, 'document_type');    
    if($term) {
      $cat_color = ' ';
      $term_data = array_values($term);
      $color = isset($term_data[0]->field_category_color) ? $term_data[0]->field_category_color[LANGUAGE_NONE][0]['value'] : '';
      if($color != '') {
        $cat_color = ' style="background-color:'.$color.'"';
      }
      
      $doc_category_html .= '<li'.$cat_color.'>'.$term_data[0]->name.'</li>';
    }
  }
}

$doc_category_html .= '</ul></div>';

?>
<div class="document-item-inner">
    <div class="document-item-content-wrapper">
        <div class="document-item-content">
            <div class="document-item-content-title"><h2><a href="<?php print $file_url; ?>" target="_blank"><?php print $fields['title']->raw; ?></a></h2></div>
            <div class="document-item-content-heading">
                <div class="document-item-content-cat"><?php print $doc_category_html; ?></div>
                <div class="document-item-content-date"><?php print $fields['field_document_date']->content; ?></div>
            </div>
            <div class="document-item-content-desc"><?php print $fields['body']->content; ?></div>
        </div>
        <div class="document-item-link"><?php print $fields['field_document_file']->content; ?></div>
    </div>
</div>
