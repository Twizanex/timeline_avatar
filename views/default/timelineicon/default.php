<?php
/**
* Generic Cover photo view.
*
* @package Elgg
* @subpackage Core
*
* @uses $vars['entity'] The entity the icon represents - uses getIconURL() method
* @uses $vars['size'] topbar, tiny, small, medium (default), large, master
* @uses $vars['href'] Optional override for link
* @uses $vars['img_class'] Optional CSS class added to img
* @uses $vars['link_class'] Optional CSS class for the link
* // (!in_array($size, array('babu', 'nyanya', 'dada', 'kaka', 'shangazi', 'mjomba')))
*/

$entity = $vars['entity'];

$sizes = array('dada', 'kaka', 'shangazi', 'nyanya', 'mjomba', 'babu');
// Get size
if (!in_array($vars['size'], $sizes)) {
$vars['size'] = "kaka";
}

$class = elgg_extract('img_class', $vars, '');

if (isset($entity->name)) {
$title = $entity->name;
} else {
$title = $entity->title;
}
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
$url = $vars['href'];
}

$icon_sizes = elgg_get_config('coverphoto_sizes');  //TM:  coverphoto_sizes
$size = $vars['size'];

if (!isset($vars['width'])) {
$vars['width'] = $size != 'mjomba' ? $icon_sizes[$size]['w'] : null;
}
if (!isset($vars['height'])) {
$vars['height'] = $size != 'mjomba' ? $icon_sizes[$size]['h'] : null;
}

$img_params = array(
'src' => $entity->getTimelineiconURL($vars['size']),
'alt' => $title,	
);

if (!empty($class)) {
$img_params['class'] = $class;
}

if (!empty($vars['width'])) {
$img_params['width'] = $vars['width'];
}

if (!empty($vars['height'])) {
$img_params['height'] = $vars['height'];
}

$img = elgg_view('output/img', $img_params);

if ($url) {
$params = array(
'href' => $url,
'text' => $img,
'is_trusted' => true,
);
$class = elgg_extract('link_class', $vars, '');
if ($class) {
$params['class'] = $class;
}

echo elgg_view('output/url', $params);
} else {
echo $img;
}