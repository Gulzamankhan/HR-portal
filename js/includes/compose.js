$(document).ready( function () {
	$(".selectall").change(function(e) {
		var ct = e.currentTarget;

		var o = ct.options[0];
		var t = ct.options[0].text;
		var s = ct.options[0].selected;

		if(s && (t == "Everyone")) {
			for(var i = 1; i < ct.options.length; i++) {
				ct.options[i].selected = false;
			}
		}
	});
});