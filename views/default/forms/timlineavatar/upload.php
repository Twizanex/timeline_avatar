<?php
/**
* Cover photo upload form
*
* @uses $vars['entity']
* //TM:  timlineavatar
*/

?>
<div>
<label><?php echo elgg_echo("timlineavatar:upload"); ?></label><br />
<?php echo elgg_view("input/file",array('name' => 'timlineavatar')); ?>
</div>
<div class="elgg-foot">
<?php echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid)); ?>
<?php echo elgg_view('input/submit', array('value' => elgg_echo('upload'))); ?>
</div>