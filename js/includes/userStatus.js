$(document).ready( function () {
   $('#clockedIn').dataTable({
		"order": [2, 'asc'],
		"pageLength": 25
	});

	$('#clockedIn').addClass('pt-10 pb-10');

	$('#clockedOut').dataTable({
		"order": [0, 'asc'],
		"pageLength": 25
	});

	$('#clockedOut').addClass('pt-10 pb-10');
});