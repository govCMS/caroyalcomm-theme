<?php
$exhibit_number = isset($content['field_exhibit_number']) ? $content['field_exhibit_number'][0]['#markup'] : '';
$exhibit_hearing_day = isset($content['field_exhibit_hearing_day']) ? $content['field_exhibit_hearing_day'][0]['#markup'] : '';
$exhibit_tendered_by = isset($content['field_exhibit_tendered_by']) ? $content['field_exhibit_tendered_by'][0]['#markup'] : '';
$exhibit_witness_names = isset($content['field_exhibit_witness_names']) ? $content['field_exhibit_witness_names'][0]['#markup'] : '';
$exhibit_date_tendered = isset($content['field_exhibit_date_tendered']) ? strip_tags($content['field_exhibit_date_tendered'][0]['#markup']) : '';
$exhibit_attachments = isset($content['field_exhibit_file_attachments']) ? $content['field_exhibit_file_attachments']['#items'] : '';

$attachments = array();
if (!empty($exhibit_attachments)) {
  foreach ($exhibit_attachments as $attachment) {
    $attachment_id = !empty($attachment['field_file_id']) ? $attachment['field_file_id'][LANGUAGE_NONE][0]['value'] : '';
    $attachment_date = !empty($attachment['field_file_date']) ? format_date(strtotime($attachment['field_file_date'][LANGUAGE_NONE][0]['value']), $type = 'custom', $format = 'd M Y', NULL, $langcode = NULL) : '';
    $attachments[] = array(
      'file_name' => $attachment['filename'],
      'file_id' => $attachment_id,
      'file_date' => $attachment_date,
      'file' => (object) $attachment,
    );
  }
} else {
  $exhibit_attachments = array();
}
?>
<!-- items -->
<tr class="exhibits-items">
    <td class="exhibits-element"><?php print $exhibit_number; ?></td>                                
    <td class="exhibits-element"><?php print $exhibit_hearing_day; ?></td>
    <td class="exhibits-element"><?php print $exhibit_tendered_by; ?></td>
    <td class="exhibits-element"><?php print $exhibit_witness_names; ?></td>
    <td class="exhibits-element"><?php print $exhibit_date_tendered; ?></td>
    <td class="exhibits-element"><?php print count($exhibit_attachments); ?> <span class="content-toggle"><a href="#"><span class="show-text">show</span><span class="hide-text">hide</span></a></span></td>
</tr>
<!-- end items -->
<!-- content items -->
<tr class="accordion-content">
    <td colspan="6">
        <?php
        if ($attachments):
          foreach ($attachments as $file_attachment) {
            ?>
            <div class="document-container clearfix">
                <div class="document-container-left">
                  <div class="document-title-container">
                      <h4 class="document-title"><?php print !empty($file_attachment['file_name']) ? $file_attachment['file_name'] : $file_attachment['file_id']; ?></h4>
                  </div>
                  <div class="document-info-container">
                      <div class="document-id-container">
                          <span class="document-id">ID: <b><?php print $file_attachment['file_id']; ?></b></span>
                      </div>
                      <div class="document-date-container">
                          <span class="document-date">Date: <b><?php print $file_attachment['file_date']; ?></b></span>
                      </div>
                  </div>
                </div>
                <div class="document-container-right">
                  <div class="document-download-container">
                      <div class="download">
                        <div class="document-item-link">                          
                              <?php
                              $options = array(
                                'attributes' => array(
                                  'type' => $file_attachment['file']->filemime . '; length=' . $file_attachment['file']->filesize,
                                  'target' => '_blank',
                                  'class' => array(
                                    'btn-download'
                                  )
                                ),
                                'html' => true,
                              );
                              $mime_to_ext = array(
                                'application/pdf' => 'PDF',
                                'text/plain' => 'TXT',
                                'application/msword' => 'DOC',
                                'application/vnd.ms-excel' => 'XLS',
                                'application/vnd.ms-powerpoint' => 'PPT',
                              );
                              
                              $url = file_create_url($file_attachment['file']->uri);
                              $mimetype = file_get_mimetype($file_attachment['file']->uri);
                              $file_size = _royalcommission_filesize($file_attachment['file']->filesize);
                              
                              if (isset($mime_to_ext[$mimetype])) {
                                $link_text = t('Download @ext @filesize', array('@ext' => $mime_to_ext[$mimetype], '@filesize' => $file_size));
                                $options['attributes']['aria-label'] = t('Download @ext @filesize', array('@ext' => $mime_to_ext[$mimetype], '@filesize' => $file_size));
                              }
                              else {
                                $link_text = t('Download File @filesize', array('@filesize' => $file_size));
                                $options['attributes']['aria-label'] = t('Download File @filesize', array('@filesize' => $file_size));
                              }

                              print '<span class="file">' . l($link_text, $url, $options) . '</span>';
                              
                              ?>                          
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <?php
          }
        endif;
        ?>  
    </td>
</tr>
<!-- end content items -->


