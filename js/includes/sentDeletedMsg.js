$(document).ready( function () {
   $('#sent').dataTable({
		"order": [ 5, 'asc' ],
		"pageLength": 25,
		"columnDefs": [
            {
                "targets": [5],
                "visible": false,
                "searchable": false
            },
			{ "width": "2%", "targets": 0 }
		]
	});

	$('#sent').addClass('pt-10 pb-10');
});