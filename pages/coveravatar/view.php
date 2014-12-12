<?php
/**
* View a cover Photo
*/

// page owner library sets this based on URL
$user = elgg_get_page_owner_entity();

// Get the size
$size = strtolower(get_input('size'));
if (!in_array($size, array('mjomba', 'shangazi', 'kaka', 'dada', 'nyanya', 'babu'))) {
$size = 'kaka';
}

// If user doesn't exist, return default Cover photo
if (!$user) {
$url = "mod/timeline_avatar/graphics/timelineicons/default/{$size}.png";
$url = elgg_normalize_url($url);
forward($url);
}

$user_guid = $user->getGUID();

// Try and get the Coverphoto
$filehandler = new ElggFile();
$filehandler->owner_guid = $user_guid;
$filehandler->setFilename("coverphoto/{$user_guid}{$size}.jpg");  //TM:  coverphoto 

$success = false;

try {
if ($filehandler->open("read")) {
if ($contents = $filehandler->read($filehandler->size())) {
$success = true;
}
}
} catch (InvalidParameterException $e) {
elgg_log("Unable to get avatar for user with GUID $user_guid", 'ERROR');
}


if (!$success) {
$url = "mod/timeline_avatar/graphics/timelineicons/default/{$size}.png";
$url = elgg_normalize_url($url);
forward($url);
}

header("Content-type: image/jpeg", true);
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($contents));

echo $contents;