$(document).ready( function () {
	$('#rpts').dataTable({
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
		"order": [0, 'asc'],
		"pageLength": 50
	});

	$('#rpts').addClass('pb-10');
});