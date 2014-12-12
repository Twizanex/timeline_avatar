<?php

gatekeeper();
$user = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($user->guid);
$title = elgg_echo('newsfeed');
$composer = elgg_view('page/elements/composer', array('entity' => $user));
$db_prefix = elgg_get_config('dbprefix');
$activity = elgg_list_river(array(
'joins' => array("JOIN {$db_prefix}entities object ON object.guid = rv.object_guid"),
'wheres' => array("
rv.subject_guid = $user->guid
OR rv.subject_guid IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='follower')
OR rv.subject_guid IN (SELECT guid_one FROM {$db_prefix}entity_relationships WHERE guid_two=$user->guid AND relationship='friend')
"),
));
elgg_set_page_owner_guid(1);
$content = elgg_view_layout('', array(
'title' => $title,
'content' => $composer . $activity,
));
//  echo elgg_view_page($title, $content);



?>


<style>












</style>







<script>

(function() {
  $(document).ready(function() {
    var timelineAnimate;
    timelineAnimate = function(elem) {
      return $(".timeline.animated .timeline-row").each(function(i) {
        var bottom_of_object, bottom_of_window;
        bottom_of_object = $(this).position().top + $(this).outerHeight();
        bottom_of_window = $(window).scrollTop() + $(window).height();
        if (bottom_of_window > bottom_of_object) {
          return $(this).addClass("active");
        }
      });
    };
    timelineAnimate();
    return $(window).scroll(function() {
      return timelineAnimate();
    });
  });

}).call(this);




<?php  

/*
               elgg_load_css('timelinestyle_css'); 

              elgg_load_js('timeline_js');
		elgg_load_js('blocksit_js');
		elgg_load_js('jquery_js');

*/




?>


<?php


$body .= <<<HTML






HTML;

echo elgg_view_page($title, $body);


?>    
    
    
    
    