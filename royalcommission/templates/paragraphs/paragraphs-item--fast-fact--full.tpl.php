<?php
$fast_fact_number = isset($content['field_fast_fact_number']) ? render($content['field_fast_fact_number'][0]) : '';
$fast_fact_title = isset($content['field_fast_fact_title']) ? render($content['field_fast_fact_title'][0]) : '';
?>
<div class="fast-fact-item">
    <div class="fast-fact-number"><?php print $fast_fact_number; ?></div>
    <div class="fast-fact-title"><?php print $fast_fact_title; ?></div>
</div>
