$(document).ready( function () {
	$('#sentMsg').dataTable({
		"order": [ 5, 'desc' ],
		"pageLength": 25,
		"columnDefs": [
            {
                "targets": [5],
                "visible": false,
                "searchable": false
            },
			{"width": "2%", "targets": 0}
		]
	});

	$('#sentMsg').addClass('pt-10 pb-10');
});