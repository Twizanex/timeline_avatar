// force the first column to at least be as large as the coverphoto box in cols 2 and 3
// we also want to run before the widget init happens so priority is < 500
elgg.register_hook_handler('init', 'system', function() {
// only do this on the coverphoto page's widget canvas.
if ($('.coverphoto').length) {
$('#elgg-widget-col-1').css('min-height', $('.coverphoto').outerHeight(true) + 1);
}
}, 400);