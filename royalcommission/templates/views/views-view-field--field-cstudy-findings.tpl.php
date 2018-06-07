<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */

if (!empty($row->field_field_cstudy_findings)):
$file = $row->field_field_cstudy_findings[0]['raw'];
$ext = pathinfo($file['uri'], PATHINFO_EXTENSION);
$filesize = format_size($file['filesize']);

?>
<a href="<?php print file_create_url($file['uri']) ?>" class="btn-download file-list-download" target="_blank">
  <span class="file-download-text"><?php print t('Download findings') ?></span>
  <span class="file-meta"><?php print $ext . ' ' . $filesize ?></span>
</a>
<?php endif; ?>