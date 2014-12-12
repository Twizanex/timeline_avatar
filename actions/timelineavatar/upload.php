<?php
/**
* Cover photo upload upload action
*/

$guid = get_input('guid');
$owner = get_entity($guid);

//$guid_onwer_page = elgg_get_page_owner_entity(); // Used to identify the file owner

if (!$owner || !($owner instanceof ElggUser) || !$owner->canEdit()) {
register_error(elgg_echo('timlineavatar:upload:fail')); //TM:  timlineavatar
forward(REFERER);
}

if ($_FILES['timlineavatar']['error'] != 0) {   //TM:  timlineavatar
register_error(elgg_echo('timlineavatar:upload:fail'));  //TM:  timlineavatar
forward(REFERER);
}

if (isset($_FILES['timlineavatar']) && $_FILES['timlineavatar']['error'] == 0) {


$icon_sizes = elgg_get_config('coverphoto_sizes');  //TM:  coverphoto_sizes

// get the images and save their file handlers into an array
// so we can do clean up if one fails.
// $topbar = array();
//  $name = 'kaka';  
//TM: changed avatar to timlineavatar

// get_a_jpg_image_from_existing_timeline_file($_FILES['timlineavatar']);


//var_dump ($keronche);

save_uploaded_timeline_file('timlineavatar'); // save the default file or original file that the user uploaded


/*
  if (!require_once(dirname(dirname(__FILE__))."/timeline_avatar/lib/php_image_magician.php"))
{
// this is for debugging for testing... it helps to know if the file is right  checkL
 echo "Could not load fil /models/php_image_magician.php  . Please check your Elgg /models/php_image_magician.php file for all required files.";
}

 if (!require_once(dirname(dirname(__FILE__))."/models/CoverPhotoFilePath.php"))
{
// this is for debugging for testing... it helps to know if the file is right  checkL
 echo "Could not load fil /models/CoverPhotoFilePath.php  . Please check your Elgg /models/CoverPhotoFilePath.php file for all required files.";
}
*/

//elgg_load_library('elgg:libtimlineimagelib');


/*

if (!require_once(dirname(dirname(__FILE__))."/timeline_avatar/lib/classPhpPsdReader.php"))
{
// this is for debugging for testing... it helps to know if the file is right  checkL
 echo "Could not load fil /models/classPhpPsdReader.php  . Please check your Elgg /models/classPhpPsdReader.php file for all required files.";
}

*/
/*

	$tomfiletry  = '/home/twigal6/elgg_data/2013/03/27/35/coverphoto/35coverphoto.jpg' ;
	
	
        $magicianObj = new imageLib($tomfiletry); //topbar

//	 $magicianObj = new imageLib($topbarfileload); //topbar
	
	 $magicianObj -> resizeImage(200, 200, 'exact', true);       // topbar
	
	 $magicianObj -> saveImage('/home/twigal6/elgg_data/2013/03/27/35/coverphoto/topbar.jpg' , 100);

*/



//get_resized_image_from_uploaded_timeline_file();

 get_resized_image_from_uploaded_timeline_file('timlineavatar'); // saves a file called topbarfile
 
 get_resized_image_from_uploaded_timeline_tiny('timlineavatar'); // saves a file called tinyfile
 
 get_resized_image_from_uploaded_timeline_small('timlineavatar'); // Saves a file called smallfile
 
 get_resized_image_from_uploaded_timeline_medium ('timlineavatar'); // Saves a file called medium
 
 get_resized_image_from_uploaded_timeline_large('timlineavatar'); // Saves a file called large
 
 get_resized_image_from_uploaded_timeline_master('timlineavatar'); // Saves a file called master


//$resized = get_resized_image_from_uploaded_timeline_file('timlineavatar');




$original_uploded_image = get_uploaded_coverphoto_timeline_file();

if(file_exists($original_uploded_image)) {
					
	unlink($original_uploded_image);
				
				
				
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
			}




}

if ($resized) {
//@todo Make these actual entities. See exts #348.
$file = new ElggFile();
$file->owner_guid = $guid;
$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM:  coverphoto
$file->open('write');
$file->write($resized);
$file->close();
$topbar[] = $file;
} else {
// cleanup on fail
foreach ($topbar as $file) {
$file->delete();
}

register_error(elgg_echo('timlineavatar:resize:fail'));  //TM:  timlineavatar
forward(REFERER);
}



// reset crop coordinates

/*
$owner->x1 = 0;
$owner->x2 = 0;
$owner->y1 = 0;
$owner->y2 = 0;
*/

$owner->coverphototime = time(); //TM: coverphototime
if (elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
system_message(elgg_echo("coverphototime:upload:success"));  //TM:  coverphototime

$view = 'river/user/default/profileiconupdate';
elgg_delete_river(array('subject_guid' => $owner->guid, 'view' => $view));
add_to_river($view, 'update', $owner->guid, $owner->guid);
}

forward(REFERER);