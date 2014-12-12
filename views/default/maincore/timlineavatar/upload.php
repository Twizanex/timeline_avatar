<?php
/**
* Cover photo upload view
*
* @uses $vars['entity']
*/




$owner_entity_page = elgg_get_page_owner_entity(); // TM: owner

// let us make shure only page owner can edit the cover photo

if (!$owner_entity_page || !($owner_entity_page instanceof ElggUser) || !$owner_entity_page->canEdit()) {
register_error(elgg_echo('avatar:upload:fail'));
forward(REFERER);
}






$user_avatar = elgg_view('output/img', array( 
'src' => getTimelineIconUrl(), 
'alt' => elgg_echo('timlineavatar'),
));

$current_label = elgg_echo('timlineavatar:current');  //TM:  timlineavatar

$remove_button = '';
if ($vars['entity']->coverphototime) {   //TM:  coverphototime
$remove_button = elgg_view('output/url', array(
'text' => elgg_echo('remove'),
'title' => elgg_echo('timlineavatar:remove'),  //TM:  timlineavatar
// 'href' => 'actions/timelineavatar/remove?guid=' . elgg_get_page_owner_guid(),  
'href' => '/actions/remove?guid=' . elgg_get_page_owner_guid(),   
'is_action' => TRUE,
'class' => 'elgg-button elgg-button-cancel mll',
));
}

$form_params = array('enctype' => 'multipart/form-data');
$upload_form = elgg_view_form('timlineavatar/upload', $form_params, $vars);



//TM: changed avatar to timlineavatar

?>

<p class="mtm">
<?php echo elgg_echo('timlineavatar:upload:instructions'); ?> 
</p>

<?php

$image = <<<HTML
<div id="current-user-timlineavatar" class="mrl prl">
<label>$current_label</label><br />
$user_avatar
</div>
$remove_button
HTML;

$body = <<<HTML
<div id="timlineavatar-upload">
$upload_form
</div>
HTML;

echo elgg_view_image_block($image, $upload_form);