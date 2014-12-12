<?php
/**
* Avatar remove action
*/

$user_guid = get_input('guid');
$user = get_user($user_guid);

if (!$user || !$user->canEdit()) {
register_error(elgg_echo('timlineavatar:remove:fail')); //TM:  timlineavatar
forward(REFERER);
}

// Delete all icons from diskspace
$icon_sizes = elgg_get_config('coverphoto_sizes'); //TM:  coverphoto_sizes 
foreach ($icon_sizes as $name => $size_info) {
$file = new ElggFile();
$file->owner_guid = $user_guid;
$file->setFilename("coverphoto/{$user_guid}{$name}.jpg"); //TM:  coverphoto
$filepath = $file->getFilenameOnFilestore();
if (!$file->delete()) {
elgg_log("timlineavatar file remove failed. Remove $filepath manually, please.", 'WARNING'); //TM:  timlineavatar
}
}

// Remove crop coords
unset($user->x1);
unset($user->x2);
unset($user->y1);
unset($user->y2);

// Remove icon
unset($user->coverphototime);  //TM: coverphototime

system_message(elgg_echo('timlineavatar:remove:success')); //TM: timlineavatar
forward(REFERER);