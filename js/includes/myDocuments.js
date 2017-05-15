$(document).ready( function () {
   $('#docs').dataTable({
		"sDom": 'T<"clear">lfrtip',
		"oTableTools": {
			"sSwfPath": "js/swf/copy_csv_xls_pdf.swf",
			"aButtons": [
				"copy",
				"csv",
				"pdf",
				"print"
			]
		},
		"order": [ 2, 'asc' ],
		"pageLength": 25
	});

	$('#docs').addClass('pb-10');
});