<?php
/**
 * Elgg Cover photo filestore.
 * This file contains classes, interfaces and functions for
 * saving and retrieving data to various file stores.
 *
 * @package Elgg.Core
 * @subpackage DataModel.FileStorage
 */

function getTimelineIconUrl() {

	$size = elgg_strtolower($size);



$owner = elgg_get_page_owner_entity();



//$user_guid = $user->
$icon_time = $owner->coverphototime;  //TM: coverphototime 



// Get the size
$size = strtolower(get_input('size'));
if (!in_array($size, array('mjomba', 'shangazi', 'kaka', 'dada', 'nyanya', 'babu'))) {
$size = 'nyanya';   // Here we select the ouput image size
}

// If user exist, return default icon
if ($icon_time) {

	      $uploaded_url = "coveravatar/view/$owner->username/$size/$icon_time";
			
			
			
	       return elgg_normalize_url($uploaded_url);
		
		
		} else {
		
		   
	         $default_url = "mod/timeline_avatar/graphics/timelineicons/default/$size.png"; 
		   
		return elgg_normalize_url($default_url);
	
		
		}

		

}


/**
 * Get the contents of an uploaded file.
 * (Returns false if there was an issue.)
 *
 * @param string $input_name The name of the file input field on the submission form
 *
 * @return mixed|false The contents of the file, or false on failure.
 */
function get_uploaded_timeline_file($input_name) {
	// If the timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
		return file_get_contents($_FILES[$input_name]['tmp_name']);
	}
	return false;
}


/**
* Gets the the contents of an already uploaded image  get_uploaded_coverphoto_timeline_file
* (Returns false if the file was not an image)
*
* @src string $input_name The file type to be  file on the disk
* @imagecreatefrom  attepts to open the file then save it later to JPG format
* @return false|mixed The contents of the image, or false on failure
*/

function get_a_jpg_image_from_existing_timeline_file($input_name) {
// Get the image information like array :  [3]=> string(24) "width="175" height="175"" ["bits"]=> int(8) ["channels"]=> int(3) ["mime"]=> string(10) "image/jpeg"
$imgsizearray = getimagesize($input_name); //TM: gives :

if ($imgsizearray == FALSE) {
return FALSE;
}

// We only accept JPEG, PNG, and GIF image formats, Other formats not in the array bellow will returne false or the uploaded image will not be saved

$accepted_formats = array(
'image/jpeg' => 'jpeg',
'image/pjpeg' => 'jpeg',
'image/png' => 'png',
'image/x-png' => 'png',
'image/gif' => 'gif'
);




// make sure the function is available for gives string(19) "imagecreatefromjpeg" 
$load_function = "imagecreatefrom" . $accepted_formats[$imgsizearray['mime']];
if (!is_callable($load_function)) {
return FALSE;
}

  // Based on what kind of image $imgsizearray['mime'] -- it is you could select the correct function to open the file:

        switch ($imgsizearray['mime'])
        {
          
           //jpeg file    $im = @imagecreatefromjpeg($imgname); // Attempt to open  
            case 'image/jpeg':
              //  $src = imagecreatefromjpeg($input_name);  
              //  break;
          
             case 'image/pjpeg':
                $src = imagecreatefromjpeg($input_name); //pjpeg file
                break;   
  
            case 'image/gif':
                $src = imagecreatefromgif($input_name);  //gif file
                break;

            case 'image/png':
            //    $src = imagecreatefrompng($input_name);  //png file
            //    break;
    
            case 'image/x-png':
            $src = imagecreatefrompng($input_name);  //x-png file
             break; 
           
            default:
            // throw new InvalidArgumentException('Invalid image type');
                return false;
        }


     // grab a compressed jpeg version of the image
   ob_start();

  // Skip the filename parameter using NULL, then set the quality to 75% 

    // imagejpeg(image, NULL, 90); //  Then you just save our image mimetypes $scr types to JPEG format file using: 
    imagejpeg($src, NULL, 100);  // $src_image - you can change 100 to any number from 0 to 100 maxmum
	$jpeg = ob_get_clean();

	// Free up memory

	imagedestroy($src);
	
	// If no error - return our final JPEG image 
	return $jpeg;
 
      }
  


function save_uploaded_timeline_file($input_name) {

$originalfile = array();

if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

       
        $guid = get_input('guid'); // TM: get the user guid of the file to be saved
       $guid = elgg_get_page_owner_entity();
       
        // name for our file
        $name = "coverphoto";
        
        //make sure file is an image
//        if (($isanonainye == 'image/jpeg' || $isanonainye == 'image/gif' || $isanonainye == 'image/png' || $isanonainye == 'image/pjpeg') ) {
 
 // return get_resized_image_from_existing_timeline_file($_FILES[$input_name]['tmp_name']);
 
 // Let us now process the uploaded ($_FILES[$input_name]['tmp_name'])  image JPEG or GIF or PNG and turn only a JPEG image format
 $jpg_imagestore = get_a_jpg_image_from_existing_timeline_file($_FILES[$input_name]['tmp_name']);
 
 
//  return $jpg_imagestore;

if ($jpg_imagestore) {
//@todo Make these actual entities. See exts #348.
$file = new ElggFile();
$file->owner_guid = $guid;
$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM:  coverphoto
$file->open('write');
$file->write($jpg_imagestore);
$file->close();
 $originalfile[] = $file;


} else {
 
 // cleanup on fail
foreach ($originalfile as $file) {
$file->delete();
}

register_error(elgg_echo('timlineavatar:file:failed:upload'));  //TM:  timlineavatar
forward(REFERER);
}

// }

}

// frees image from memory

 imagedestroy($jpg_imagestore);

}



/** 
         *  Function by TM:
	 *  TM: Get the File Path from elgg dataroot where our  as saved on disk for an ElggFile object
	 *
	 * Returns an empty string if no filename set
	 *
	 * @param ElggFile $file File object
	 *
	 * @return string Lets get the whole image from the file.
	 * @throws InvalidParameterException
	 */

//       get_uploaded_coverphoto_timeline_file
function get_uploaded_coverphoto_timeline_file($guid) {
       
    //    $guid = elgg_get_logged_in_user_guid (); // TM: user guid only allow user to upload but not site adim
       
     //  $guid = get_input('guid'); // TM: get the user guid of the file to be saved
      
      
      
        $guid = elgg_get_page_owner_entity(); // TM: This works better for admin but not user
        
        // name for our file
        $name = "coverphoto";
      
    //  $imagelast_name = "{$guid}{$name}";
      
	$file = new ElggFile();
	
	$file->setFilename("coverphoto/{$guid}{$name}.jpg");

	
	if (!$file) {
			$msg = elgg_echo('InvalidParameterException:MissingFIle $imagelast_name',
				array($file->getFilename(), $file->guid));
			throw new InvalidParameterException($msg);
		}
		
		
		
//	 return $file->grabFile($file); // TM: Let us grab the image and use it or abuse it... :)
	
	return $file->getFilenameOnFilestore($file);
	
       
  
    }



/** 
         *  Function by TM:
	 *  TM: Get the File Path from elgg dataroot where our  as saved on disk for an ElggFile object
	 *
	 * Returns an empty string if no filename set
	 *
	 * @param ElggFile $file File object
	 *
	 * @return string The full path of where the file is going to be stored
	 * @throws InvalidParameterException
	 */
 
 
 
 
function timeline_get_timeline_file_coverphoto_img_dir() { 
         
//        $file = newAvatar();
	$file = new ElggFile();
	$file->setFilename("coverphoto/");
	
	if (!$file) {
			$msg = elgg_echo('InvalidParameterException:MissingFolder',
				array($file->getFilename(), $file->guid));
			throw new InvalidParameterException($msg);
		}
		
		
	return $file->getFilenameOnFilestore($file);
 
     }


/** 
         *  Function by TM:
	 *  TM: Get the File Path from elgg dataroot where our  as saved on disk for an ElggFile object
	 *
	 * Returns an empty string if no filename set
	 *
	 * @param ElggFile $file File object
	 *
	 * @return string Lets get the whole image from the file.
	 * @throws InvalidParameterException
	 */


function get_uploaded_coverphoto_timeline_file_name($imagelast_name) {
       
	$file = new ElggFile();
	$file->setFilename("coverphoto/$imagelast_name");
	
	if (!$file) {
			$msg = elgg_echo('InvalidParameterException:MissingFIle $imagelast_name',
				array($file->getFilename(), $file->guid));
			throw new InvalidParameterException($msg);
		}
		
		
	return $file->grabFile($file); // TM: Let us grab the image and use it or abuse it... :)
       
  
    }


/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */
 

 
function get_resized_image_from_uploaded_timeline_file($input_name) {

	//	$topbar = array();
	
	// If our timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

	elgg_load_library('elgg:libtimlineimagelib');
	
	
	
	
	$topbar = array();
        $name = 'babu';  
        
        $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	
	$owner = get_entity($guid);
	
	
	$topbarfileload = get_uploaded_coverphoto_timeline_file();
	
	$kenyajane =   timeline_get_timeline_file_coverphoto_img_dir();
	
	
	$topbarfile = $kenyajane . "topbar" . ".jpg";
	

	 $magicianObj = new imageLib($topbarfileload); //topbar
	
	// $magicianObj -> resizeImage(200, 200, 'exact', true);       // topbar 
        
        // create a 900 x 200 image
        
         $magicianObj -> resizeImage(900, 200, 'exact', true);       // topbar
         
	 $magicianObj -> saveImage($topbarfile , 100);

	//	return $topbar;
	
	
	    // Let us load the uploaded image and save it elgg way
                        
        $ikominaisanoisato =  get_uploaded_coverphoto_timeline_file_name ('topbar.jpg');
	
	if ($ikominaisanoisato){
	
	
	 $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	//@todo Make these actual entities. See exts #348.
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM: changed profile to coverphoto
	$file->open('write');
	$file->write($ikominaisanoisato);
	$file->close();
	$topbar[] = $file;
	
	$owner->coverphototime = time();  //TM: changed icontime to coverphototime
	
	
	
	} else {
	// cleanup on fail
	foreach ($topbar as $file) {
	$file->delete();
	}


	register_error(elgg_echo('timlineavatar:resize:fail'));  //TM: changed avatar to timlineavatar
	forward(REFERER);
	}
	
	

	
	// Now let us delete the images we nolonger need to free the disk space.
        
                         if(file_exists($topbarfile)) {
					unlink($topbarfile);
				  //      $topbarfile->delete();
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
			}
	
/****			
 *  Now we are going to destroy the source image and the newly resized image in memory after we are finished writing the file out to disk. 
 *  Why?, I we do not destroy or clear the image from memory, we will quickly reached the memory limit that our hosting provider has placed in their php.ini 
 *  file which is 64M by defult. Otherwise, we will have to set our .htaccess file memory limit to 128M just to process only 6 images!
 *
 ****/		

	// frees image from memory
	
	imagedestroy($topbarfileload);
	
	imagedestroy($ikominaisanoisato);
	
	imagedestroy($magicianObj);
	
	}
	


	return false;

}






/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */
 
 
function get_resized_image_from_uploaded_timeline_tiny($input_name) {

	//	$topbar = array();
	
	// If our timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

	elgg_load_library('elgg:libtimlineimagelib');
	
	
	
	
	$tinyObjbar = array();
        $name = 'nyanya';  
        
        $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	$owner = get_entity($guid);
	
	
	$tinyfileload = get_uploaded_coverphoto_timeline_file();
	
	$kenyajane =   timeline_get_timeline_file_coverphoto_img_dir();
	
	
	$tinyfile = $kenyajane . "tiny" . ".jpg";
	


	 $tinyObj = new imageLib($tinyfileload); //tiny
	
	 $tinyObj -> resizeImage(320, 95, 'exact', true);       // tiny
		 
	 $tinyObj -> saveImage($tinyfile , 100);
	
	
	
	
	//	return $topbar;
	
	
	    // Let us load the uploaded image and save it elgg way
                        
        $tiny_thumbnail =  get_uploaded_coverphoto_timeline_file_name ('tiny.jpg');
	
	if ($tiny_thumbnail){
	


	 $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	//@todo Make these actual entities. See exts #348.
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM: changed profile to coverphoto
	$file->open('write');
	$file->write($tiny_thumbnail);
	$file->close();
	$tinyObjbar[] = $file;
	
	$owner->coverphototime = time();  //TM: changed icontime to coverphototime
	
	
	
	} else {
	// cleanup on fail
	foreach ($tinyObjbar as $file) {
	$file->delete();
	}


	register_error(elgg_echo('timlineavatar:resize:fail'));  //TM: changed avatar to timlineavatar
	forward(REFERER);
	}
	
	

	
	// Now let us delete the images we nolonger need to free the disk space.
        
                         if(file_exists($tinyfile)) {
					unlink($tinyfile);
				  //      $topbarfile->delete();
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
			}
	
/****			
 *  Now we are going to destroy the source image and the newly resized image in memory after we are finished writing the file out to disk. 
 *  Why?, I we do not destroy or clear the image from memory, we will quickly reached the memory limit that our hosting provider has placed in their php.ini 
 *  file which is 64M by defult. Otherwise, we will have to set our .htaccess file memory limit to 128M just to process only 6 images!
 *
 ****/		
	
	
	
	// Let us free the memory
	
	imagedestroy($tinyfileload);
	
	imagedestroy($tiny_thumbnail);
	
	imagedestroy($tinyObj);
	
	
	
	}


	return false;

}






/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */

 
function get_resized_image_from_uploaded_timeline_small($input_name) {

	//	$topbar = array();
	
	// If our timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

	elgg_load_library('elgg:libtimlineimagelib');
	
	
	
	
	$smallObjfile = array();
        $name = 'dada';  
        
        $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	$owner = get_entity($guid);
	
	
	$smallfileload = get_uploaded_coverphoto_timeline_file();
	
	$kenyajane =   timeline_get_timeline_file_coverphoto_img_dir();
	
	
	$smallfile = $kenyajane . "dada" . ".jpg";
	


	 $smallObj = new imageLib($smallfileload); //small
	
	 // create a 320 x 180 image
	 $smallObj -> resizeImage(320, 180, 'exact', true);       // small
		 
	 $smallObj -> saveImage($smallfile , 100);
	
	
	
	
	//	return $topbar;
	
	
	    // Let us load the uploaded image and save it elgg way
                        
        $small_thumbnail =  get_uploaded_coverphoto_timeline_file_name ('dada.jpg');
	
	if ($small_thumbnail){
	


	 $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	//@todo Make these actual entities. See exts #348.
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM: changed profile to coverphoto
	$file->open('write');
	$file->write($small_thumbnail);
	$file->close();
	$smallObjfile[] = $file;
	
	$owner->coverphototime = time();  //TM: changed icontime to coverphototime
	
	
	
	} else {
	// cleanup on fail
	foreach ($smallObjfile as $file) {
	$file->delete();
	}


	register_error(elgg_echo('timlineavatar:resize:fail'));  //TM: timlineavatar
	forward(REFERER);
	}
	
	

	
	// Now let us delete the images we nolonger need to free the disk space.
        
                         if(file_exists($smallfile)) {
					unlink($smallfile);
				  //      $topbarfile->delete();
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
 }
 
 /****			
 *  Now we are going to destroy the source image and the newly resized image in memory after we are finished writing the file out to disk. 
 *  Why?, I we do not destroy or clear the image from memory, we will quickly reached the memory limit that our hosting provider has placed in their php.ini 
 *  file which is 64M by defult. Otherwise, we will have to set our .htaccess file memory limit to 128M just to process only 6 images!
 *
 ****/	
	
	// Let us free the memory
	
	imagedestroy($smallfileload);
	
	imagedestroy($small_thumbnail);
	
	imagedestroy($smallObj);
	
	
	
	}

	return false;

}




/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */

 
function get_resized_image_from_uploaded_timeline_medium($input_name) {

	//	$topbar = array();
	
	// If our timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

	elgg_load_library('elgg:libtimlineimagelib');
	
	
	
	
	$mediumObjfile = array();
        $name = 'kaka';  
        
        $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	$owner = get_entity($guid);
	
	
	$mediumfileload = get_uploaded_coverphoto_timeline_file();
	
	$kenyajane =   timeline_get_timeline_file_coverphoto_img_dir();
	
	
	$mediumfile = $kenyajane . "kaka" . ".jpg";
	


	 $mediumObj = new imageLib($mediumfileload); //medium
	
	// create a 600 x 200 image
	 $mediumObj -> resizeImage(600, 200, 'exact', true);       // medium
		 
	 $mediumObj -> saveImage($mediumfile , 100);
	

	    // Let us load the uploaded image and save it elgg way
                        
        $medium_thumbnail =  get_uploaded_coverphoto_timeline_file_name ('kaka.jpg');
	
	if ($medium_thumbnail){
	


	 $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	//@todo Make these actual entities. See exts #348.
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM: changed profile to coverphoto
	$file->open('write');
	$file->write($medium_thumbnail);
	$file->close();
	$mediumObjfile[] = $file;
	
	$owner->coverphototime = time();  //TM:  coverphototime
	
	
	
	} else {
	// cleanup on fail
	foreach ($mediumObjfile as $file) {
	$file->delete();
	}


	register_error(elgg_echo('timlineavatar:resize:fail'));  //TM:  timlineavatar
	forward(REFERER);
	}
	
	

	
	// Now let us delete the images we nolonger need to free the disk space.
        
                         if(file_exists($mediumfile)) {
					unlink($mediumfile);
				  //      $topbarfile->delete();
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
			}
 /****			
 *  Now we are going to destroy the source image and the newly resized image in memory after we are finished writing the file out to disk. 
 *  Why?, I we do not destroy or clear the image from memory, we will quickly reached the memory limit that our hosting provider has placed in their php.ini 
 *  file which is 64M by defult. Otherwise, we will have to set our .htaccess file memory limit to 128M just to process only 6 images!
 *
 ****/	
	
	// Let us free the memory
	
	imagedestroy($mediumfileload);
	
	imagedestroy($medium_thumbnail);
	
	imagedestroy($mediumObj);
	
	
	
	}

	return false;

}








/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */

 
function get_resized_image_from_uploaded_timeline_large($input_name) {

	//	$topbar = array();
	
	// If our timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

	elgg_load_library('elgg:libtimlineimagelib');

	$largeObjfile = array();
        $name = 'shangazi';  
        
        $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	$owner = get_entity($guid);
	
	
	$largefileload = get_uploaded_coverphoto_timeline_file();
	
	$kenyajane =   timeline_get_timeline_file_coverphoto_img_dir();
	
	
	$largefile = $kenyajane . "shangazi" . ".jpg";
	


	 $largeObj = new imageLib($largefileload); //tiny
	
	   // create a 851 x 315 image
	 $largeObj -> resizeImage(851, 315, 'exact', true);       // tiny
		 
	 $largeObj -> saveImage($largefile , 100);

	    // Let us load the uploaded image and save it elgg way
                        
        $large_thumbnail =  get_uploaded_coverphoto_timeline_file_name ('shangazi.jpg');
	
	if ($large_thumbnail){
	


	 $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	//@todo Make these actual entities. See exts #348.
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM:  coverphoto
	$file->open('write');
	$file->write($large_thumbnail);
	$file->close();
	$largeObjfile[] = $file;
	
	$owner->coverphototime = time();  //TM: changed icontime to coverphototime
	
	
	
	} else {
	// cleanup on fail
	foreach ($largeObjfile as $file) {
	$file->delete();
	}


	register_error(elgg_echo('timlineavatar:resize:fail'));  //TM: changed avatar to timlineavatar
	forward(REFERER);
	}

	
	// Now let us delete the images we nolonger need to free the disk space.
        
                         if(file_exists($largefile)) {
					unlink($largefile);
				  //      $topbarfile->delete();
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
			}

/****			
 *  Now we are going to destroy the source image and the newly resized image in memory after we are finished writing the file out to disk. 
 *  Why?, I we do not destroy or clear the image from memory, we will quickly reached the memory limit that our hosting provider has placed in their php.ini 
 *  file which is 64M by defult. Otherwise, we will have to set our .htaccess file memory limit to 128M just to process only 6 images!
 *
 ****/		
	
	// Let us free the memory
	
	imagedestroy($largefileload);
	
	imagedestroy($large_thumbnail);
	
	imagedestroy($largeObj);
	
	
	
	}

	return false;

}






/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */

 
function get_resized_image_from_uploaded_timeline_master($input_name) {

	//	$topbar = array();
	
	// If our timeline_file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {

	elgg_load_library('elgg:libtimlineimagelib');

	$masterObjfile = array();
        $name = 'mjomba';  
        
        $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	$owner = get_entity($guid);
	
	
	$masterfileload = get_uploaded_coverphoto_timeline_file();
	
	$kenyajane =   timeline_get_timeline_file_coverphoto_img_dir();
	
	
	$masterfile = $kenyajane . "mjomba" . ".jpg";
	


	 $masterObj = new imageLib($masterfileload); // Master
	
	 // create a 990 x 288 image
	 $masterObj -> resizeImage(990, 288, 'exact', true);       // Master
		 
	 $masterObj -> saveImage($masterfile , 100);
	

	    // Let us load the uploaded image and save it elgg way
                        
        $master_thumbnail =  get_uploaded_coverphoto_timeline_file_name ('mjomba.jpg');
	
	if ($master_thumbnail){
	


	 $guid = elgg_get_logged_in_user_guid (); // TM: get the user guid or id of the file to be saved
	
	
	//@todo Make these actual entities. See exts #348.
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("coverphoto/{$guid}{$name}.jpg"); //TM:  coverphoto
	$file->open('write');
	$file->write($master_thumbnail);
	$file->close();
	$masterObjfile[] = $file;
	
	$owner->coverphototime = time();  //TM:  coverphototime

	
	} else {
	// cleanup on fail
	foreach ($masterObjfile as $file) {
	$file->delete();
	}


	register_error(elgg_echo('timlineavatar:resize:fail'));  //TM: timlineavatar
	forward(REFERER);
	}

	
	// Now let us delete the images we nolonger need to free the disk space.
        
                         if(file_exists($masterfile)) {
					unlink($masterfile);
				  //      $topbarfile->delete();
                             } else {
				register_error(elgg_echo("Your cover photo was not processed properly:action:upload:error:coverphoto"));
			}
/****			
 *  Now we are going to destroy the source image and the newly resized image in memory after we are finished writing the file out to disk. 
 *  Why?, I we do not destroy or clear the image from memory, we will quickly reached the memory limit that our hosting provider has placed in their php.ini 
 *  file which is 64M by defult. Otherwise, we will have to set our .htaccess file memory limit to 128M just to process only 6 images!
 *
 ****/		
	
	// Let us free the memory
	
	imagedestroy($masterfileload);
	
	imagedestroy($master_thumbnail);
	
	imagedestroy($masterObj);
	
	
	
	}

	return false;

}











/**
 * Gets the jpeg contents of the resized version of an already uploaded image
 * (Returns false if the timeline_file was not an image)
 *
 * @param string $input_name The name of the timeline_file on the disk
 * @param int    $maxwidth   The desired width of the resized image
 * @param int    $maxheight  The desired height of the resized image
 * @param bool   $square     If set to true, takes the smallest of maxwidth and
 * 			                 maxheight and use it to set the dimensions on the new image.
 *                           If no crop parameters are set, the largest square that fits
 *                           in the image centered will be used for the resize. If square,
 *                           the crop must be a square region.
 * @param int    $x1         x coordinate for top, left corner
 * @param int    $y1         y coordinate for top, left corner
 * @param int    $x2         x coordinate for bottom, right corner
 * @param int    $y2         y coordinate for bottom, right corner
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */
function get_resized_image_from_existing_timeline_file($input_name, $maxwidth, $maxheight, $square = FALSE,
$x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $upscale = FALSE) {

	// Get the size information from the image
	$imgsizearray = getimagesize($input_name);
	if ($imgsizearray == FALSE) {
		return FALSE;
	}

	$width = $imgsizearray[0];
	$height = $imgsizearray[1];

	$accepted_formats = array(
		'image/jpeg' => 'jpeg',
		'image/pjpeg' => 'jpeg',
		'image/png' => 'png',
		'image/x-png' => 'png',
		'image/gif' => 'gif'
	);

	// make sure the function is available
	$load_function = "imagecreatefrom" . $accepted_formats[$imgsizearray['mime']];
	if (!is_callable($load_function)) {
		return FALSE;
	}

	// get the parameters for resizing the image
	$options = array(
		'maxwidth' => $maxwidth,
		'maxheight' => $maxheight,
		'square' => $square,
		'upscale' => $upscale,
		'x1' => $x1,
		'y1' => $y1,
		'x2' => $x2,
		'y2' => $y2,
	);
	$params = get_image_resize_parameters($width, $height, $options);
	if ($params == FALSE) {
		return FALSE;
	}

	// load original image
	$original_image = $load_function($input_name);
	if (!$original_image) {
		return FALSE;
	}

	// allocate the new image
	$new_image = imagecreatetruecolor($params['newwidth'], $params['newheight']);
	if (!$new_image) {
		return FALSE;
	}

	// color transparencies white (default is black)
	imagefilledrectangle(
		$new_image, 0, 0, $params['newwidth'], $params['newheight'],
		imagecolorallocate($new_image, 255, 255, 255)
	);

	$rtn_code = imagecopyresampled(	$new_image,
									$original_image,
									0,
									0,
									$params['xoffset'],
									$params['yoffset'],
									$params['newwidth'],
									$params['newheight'],
									$params['selectionwidth'],
									$params['selectionheight']);
	if (!$rtn_code) {
		return FALSE;
	}

	// grab a compressed jpeg version of the image
	ob_start();
	imagejpeg($new_image, NULL, 90);
	$jpeg = ob_get_clean();

	imagedestroy($new_image);
	imagedestroy($original_image);

	return $jpeg;
}
