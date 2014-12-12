<?php
/**
* Cover photo cropping view
*
* @uses vars['entity']  
* //TM: changed avatar to timlineavatar
*/

?>
<div id="timlineavatar-croppingtool" class="mtl ptm">
<label><?php echo elgg_echo('timlineavatar:crop:title'); ?></label>
<br />
<p>
<?php echo elgg_echo("timlineavatar:create:instructions"); ?>
</p>
<?php echo elgg_view_form('timlineavatar/crop', array(), $vars); ?>
</div>