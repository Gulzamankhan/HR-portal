$(document).ready( function () {
   $('#archMsg').dataTable({
		"order": [ 4, 'desc' ],
		"pageLength": 25,
		// Hide the Full Date Column (order by)
		"columnDefs": [
            {
                "targets": [4],
                "visible": false,
                "searchable": false
            }
		]
	});

	$('#archMsg').addClass('pt-10 pb-10');
});