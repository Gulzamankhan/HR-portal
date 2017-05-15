jQuery(document).ready(function($) {

	var timeId		= $("#timeId").val();
	var weekNo		= $("#weekNo").val();
	var clockYear	= $("#clockYear").val();
	var running		= $("#running").val();

	$("#toggleTime").click(function(e) {
		e.preventDefault();

		if (weekNo !== '') {
			post_data = {
				'timeId':timeId,
				'weekNo':weekNo,
				'clockYear':clockYear,
				'running':running
			};
			$.post('includes/timeclock.php', post_data, function(datares) {
				if (datares) {
					var reloadPage = jQuery(location).attr('href');
					window.location = reloadPage;
				}
			});
		}
	});

});