$(document).ready( function () {
   $('#actUsers').dataTable({
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
		"order": [ 5, 'asc' ],
		"pageLength": 25
	});

	$('#actUsers').addClass('pb-10');
});