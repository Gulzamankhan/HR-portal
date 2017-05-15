$(document).ready( function () {
   $('#activity').dataTable({
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
		"order": [ 0, 'desc' ],
		"pageLength": 100,
		"columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
			{"width": "50%", "targets": 3}
		]
	});

	$('#activity').addClass('pb-10');
});