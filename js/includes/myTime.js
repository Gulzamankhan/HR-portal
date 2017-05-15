$(document).ready( function () {
	var dayStart = $('#dayStart').val();
	$('#entryDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('#entryTimeIn').datetimepicker({
		format: 'hh:ii',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15,
		weekStart: dayStart
	});
	$('#entryTimeOut').datetimepicker({
		format: 'hh:ii',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15,
		weekStart: dayStart
	});
});