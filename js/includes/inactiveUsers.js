$(document).ready( function () {
   $('#inactUsers').dataTable({
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

	$('#inactUsers').addClass('pb-10');
});