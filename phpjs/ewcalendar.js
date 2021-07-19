// Create calendar
function ew_CreateCalendar(formid, id, format) {
	if (id.indexOf("$rowindex$") > -1)
		return;
	var $ = jQuery, el = ew_GetElement(id, formid), $el = $(el);
	if ($el.parent().is(".input-group"))
		return;
	var $btn = $('<button type="button"><span class="glyphicon glyphicon-calendar"></span></button>')
		.addClass("btn btn-default btn-sm").css({ "font-size": $el.css("font-size"), "height": ($.ua.ie && $el.closest(".tab-pane")[0]) ? $el.height() : $el.outerHeight() });
	$el.data("calendar", Calendar.setup({
		inputField: el, // input field
		showsTime: / %H:%M(:%S)?$/.test(format), // shows time
		ifFormat: format, // date format
		button: $btn[0], // button
		cache: true // reuse the same calendar object, where possible
	})).wrap('<div class="input-group"></div>').after($('<span class="input-group-btn"></span>').append($btn));
}
