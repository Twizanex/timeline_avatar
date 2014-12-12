<?php
/**
* Upload and crop an Cover photo page
*/

// Only logged in users
gatekeeper();

elgg_set_context('coverphoto_edit'); // TM:    coverphoto_edit    

$title = elgg_echo('timlineavatar:edit'); //TM:  timlineavatar

$entity = elgg_get_page_owner_entity();  // TM: THIS IS NOT WORKING BECOUSE IT RETURNS ZERO OR NULL

if (elgg_is_logged_in()) {
$entity = elgg_get_logged_in_user_entity();

}


if (!$entity) {
	register_error(elgg_echo("timeline_avatar pages coveravatar edit.php can not get entity"));
	forward();
}


if (!elgg_instanceof($entity, 'user') || !$entity->canEdit()) {
register_error(elgg_echo('timlineavatar:noaccess')); //TM:  timlineavatar
forward(REFERER);
}


$content = elgg_view('maincore/timlineavatar/upload', array('entity' => $entity));  //TM: timlineavatar


/*
// only offer the crop view if an avatar has been uploaded
if (isset($entity->coverphototime)) {  //TM:  coverphototime
$content .= elgg_view('maincore/timlineavatar/crop', array('entity' => $entity)); //TM:  timlineavatar
}
*/
$params = array(
'content' => $content,
'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);