<?php

$file_uri = '#';
if (isset($row->_field_data['fid']['entity']->uri)) {
  $file_uri = $row->_field_data['fid']['entity']->uri;
}

?>
<div class="col-md-8 col-xs-12">
    <h4><a href="<?php print file_create_url($file_uri); ?>" target="_blank"><?php print $fields['filename']->content; ?></a></h4>
  <p><?php print $fields['field_file_description']->content; ?></p>
</div>
<div class="col-md-4 col-xs-12"><?php print $fields['rendered']->content; ?></div>