<?php


/**
*  engine/lib/views.php
*
* View the icon of an entity
*
* Entity views are determined by having a view named after the entity $type/$subtype.
* Entities that do not have a defined icon/$type/$subtype view will fall back to using
* the icon/$type/default view.
*
* @param ElggEntity $entity The entity to display
* @param string $size The size: tiny, small, medium, large
* @param array $vars An array of variables to pass to the view. Some possible
* variables are img_class and link_class. See the
* specific icon view for more parameters.
*
* @return string HTML to display or false
*/



function elgg_view_entity_timelineicon(ElggEntity $entity, $size = 'kaka', $vars = array()) {

// No point continuing if entity is null
if (!$entity || !($entity instanceof ElggEntity)) {
return false;
}

$vars['entity'] = $entity;
$vars['size'] = $size;

$entity_type = $entity->getType();

$subtype = $entity->getSubtype();
if (empty($subtype)) {
$subtype = 'default';
}

$contents = '';
if (elgg_view_exists("timelineicon/$entity_type/$subtype")) {
$contents = elgg_view("timelineicon/$entity_type/$subtype", $vars);
}
if (empty($contents)) {
$contents = elgg_view("timelineicon/$entity_type/default", $vars);
}
if (empty($contents)) {
$contents = elgg_view("timelineicon/default", $vars);
}

return $contents;
}

