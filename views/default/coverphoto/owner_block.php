<?php
/**
* coverphoto owner block
*/

$user = elgg_get_page_owner_entity();

if (!$user) {
// no user so we quit view
echo elgg_echo('viewfailure', array(__FILE__));
return TRUE;
}


echo  elgg_view_entity_icon ();






/*

$icon = elgg_view_entity_timelineicon($user, 'shangazi', array(
'use_hover' => false,
'use_link' => false,
));






// grab the actions and admin menu items from user hover
$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu();
$actions = elgg_extract('action', $menu, array());
$admin = elgg_extract('admin', $menu, array());

$coverphoto_actions = '';
if (elgg_is_logged_in() && $actions) {
$coverphoto_actions = '<ul class="elgg-menu coverphoto-action-menu mvm">';
foreach ($actions as $action) {
$coverphoto_actions .= '<li>' . $action->getContent(array('class' => 'elgg-button elgg-button-action')) . '</li>';
}
$coverphoto_actions .= '</ul>';
}





// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
$text = elgg_echo('admin:options');



$admin_links = '<ul class="coverphoto-admin-menu-wrapper">';
$admin_links .= "<li><a rel=\"toggle\" href=\"#coverphoto-menu-admin\">$text&hellip;</a>";
$admin_links .= '<ul class="coverphoto-admin-menu" id="coverphoto-menu-admin">';
foreach ($admin as $menu_item) {
$admin_links .= elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}
$admin_links .= '</ul>';
$admin_links .= '</li>';
$admin_links .= '</ul>';	
}






// content links
$content_menu = elgg_view_menu('owner_block', array(
'entity' => elgg_get_page_owner_entity(),
'class' => 'coverphoto-content-menu',
));




echo <<<HTML

<div id="coverphoto-owner-block">
$icon
$coverphoto_actions
$content_menu
$admin_links
</div>

HTML;


*/