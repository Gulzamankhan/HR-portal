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
		"order": [[0, 'asc'],[4, 'asc']],
		"pageLength": 100
	});

	$('#rpts').addClass('pb-10');
});