<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>


<div class="col-md-12">
  <?php print $fields['title']->wrapper_prefix; ?>
  <?php print $fields['title']->content; ?>
  <?php print $fields['title']->wrapper_suffix; ?>
  <?php print $fields['created']->wrapper_prefix; ?>
  <?php print $fields['created']->content; ?>
  <?php print $fields['created']->wrapper_suffix; ?>
</div>
<div class="col-md-8 col-xs-12">
  <?php print $fields['body']->wrapper_prefix; ?>
  <?php print $fields['body']->content; ?>
  <?php print $fields['body']->wrapper_suffix; ?>
  <?php print l(t('View full case study'), 'node/' . $row->nid, array('attributes' => array('class' => array('view-full-cs')))); ?>
</div>
<div class="col-md-4 col-xs-12">
  <?php print $fields['field_cstudy_findings']->wrapper_prefix; ?>
  <?php print $fields['field_cstudy_findings']->content; ?>
  <?php print $fields['field_cstudy_findings']->wrapper_suffix; ?>
</div>