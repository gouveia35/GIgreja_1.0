// Create time picker
function ew_CreateTimePicker(formid, id, options) {
	if (id.indexOf("$rowindex$") > -1)
		return;
	var $ = jQuery, el = ew_GetElement(id, formid), $el = $(el);
	if ($el.parent().is(".input-group"))
		return;
	var $btn = $('<button type="button"><span class="glyphicon glyphicon-time"></span></button>')
		.addClass("btn btn-default btn-sm").css({ "font-size": $el.css("font-size"), "height": ($.ua.ie && $el.closest(".tab-pane")[0]) ? $el.height() : $el.outerHeight() });
	$el.wrap('<div class="input-group"></div>').after($('<span class="input-group-btn"></span>').append($btn)).timepicker(options)
		.on("showTimepicker", function() {
			$el.data('timepicker-list').width($el.outerWidth() - 2);
		});
	$btn.click(function() {		
		$el.timepicker("show");
	});
}
