// create editor
function ew_CreateEditor(formid, name, cols, rows, readonly) {
	if (typeof CKEDITOR == "undefined" || name.indexOf("$rowindex$") > -1)
		return;
	var $ = jQuery, form = $("#" + formid)[0], el = ew_GetElement(name, form);
	if (!el)
		return;
	var args = {"id": name, "form": form, "enabled": true};
	$(el).trigger("create", [args]);
	if (!args.enabled)
		return;
	var w = (cols ? Math.abs(cols) : 35) * 2 + "em"; // width
	var h = ((rows ? Math.abs(rows) : 4) + 4) * 1.5 + "em"; // height
	if (readonly) {
		new ew_ReadOnlyTextArea(el, w, h);
	} else {
		var longname = formid + "$" + name + "$";
		var path = window.location.href.substring(0, window.location.href.lastIndexOf("/") + 1);
		var editor = {
			name: name,
			active: false,
			instance: null,
			create: function() {
				this.instance = CKEDITOR.replace(el, {

					//width: w, // DO NOT specify width when creating editor
					height: h,
					autoUpdateElement: false,
					filebrowserBrowseUrl: 'ckeditor/filemanager/browser/default/browser.html?Connector=' + path + 'ckeditor/filemanager/connectors/php/connector.php',
					filebrowserImageBrowseUrl: 'ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=' + path + 'ckeditor/filemanager/connectors/php/connector.php',
					filebrowserFlashBrowseUrl: 'ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=' + path + 'ckeditor/filemanager/connectors/php/connector.php',
					filebrowserUploadUrl: path + 'ckeditor/filemanager/connectors/php/upload.php?Type=File',
					filebrowserImageUploadUrl: path + 'ckeditor/filemanager/connectors/php/upload.php?Type=Image',
					filebrowserFlashUploadUrl: path + 'ckeditor/filemanager/connectors/php/upload.php?Type=Flash', 
					baseHref: 'ckeditor/'
				});				
				CKEDITOR.instances[longname] = this.instance;
				delete CKEDITOR.instances[name];
				this.active = true;
			},			
			set: function() { // update value from textarea to editor
				if (this.instance) this.instance.setData(this.instance.element.value);
			},
			save: function() { // update value from editor to textarea
				if (this.instance) this.instance.updateElement();
				var args = {"id": name, "form": form, "value": ew_RemoveSpaces(el.value)};
				$(el).trigger("save", [args]).val(args.value);
			},
			focus: function() { // focus editor
				if (this.instance) this.instance.focus();
			},
			destroy: function() { // destroy
				if (this.instance) this.instance.destroy();
			}			
		};
		$(el).data("editor", editor).addClass("editor");
	}
}
