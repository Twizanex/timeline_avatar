<?php
/**
* Cover photo crop form
*
* @uses $vars['entity']
* //TM: changed avatar to timlineavatar
*/

elgg_load_js('jquery.imgareaselect');
elgg_load_js('elgg.timlineavatar_cropper');
elgg_load_css('jquery.imgareaselect');

$master_img = elgg_view('output/img', array(
'src' => $vars['entity']->getIconUrl('master'),
'alt' => elgg_echo('timlineavatar'),
'class' => 'mrl',
'id' => 'user-timlineavatar-cropper',
));

$preview_img = elgg_view('output/img', array(
'src' => $vars['entity']->getIconUrl('master'),
'alt' => elgg_echo('timlineavatar'),
));

?>
<div class="clearfix">
<?php echo $master_img; ?>
<div id="user-timlineavatar-preview-title"><label><?php echo elgg_echo('timlineavatar:preview'); ?></label></div>
<div id="user-timlineavatar-preview"><?php echo $preview_img; ?></div>
</div>
<div class="elgg-foot">
<?php
$coords = array('x1', 'x2', 'y1', 'y2');
foreach ($coords as $coord) {
echo elgg_view('input/hidden', array('name' => $coord, 'value' => $vars['entity']->$coord));
}

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));

echo elgg_view('input/submit', array('value' => elgg_echo('timlineavatar:create')));

?>
</div>