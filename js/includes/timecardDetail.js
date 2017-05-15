$(document).ready( function () {
   $('#timelogs').dataTable({
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
		"paging": false,
		"order": [[ 0, 'asc' ], [ 3, 'asc' ]],

		"pageLength": -1
	});

	$('#open').addClass('pb-10');

	var dayStart = $('#dayStart').val();
	var calStart = $('#calStart').val();
	var calEnd = $('#calEnd').val();

	$('#entryDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		initialDate: calStart,
		startDate: calStart,
		endDate: calEnd,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('#entryTimeIn').datetimepicker({
		format: 'hh:ii',
		todayBtn:  0,
		autoclose: 1,
		initialDate: calStart,
		startDate: calStart,
		endDate: calEnd,
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
		initialDate: calStart,
		startDate: calStart,
		endDate: calEnd,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15,
		weekStart: dayStart
	});

	$('input[name="editTimeIn"]').datetimepicker({
		format: 'hh:ii',
		todayBtn:  0,
		autoclose: 1,
		initialDate: calStart,
		startDate: calStart,
		endDate: calEnd,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15,
		weekStart: dayStart
	});
	$('input[name="editTimeOut"]').datetimepicker({
		format: 'hh:ii',
		todayBtn:  0,
		autoclose: 1,
		initialDate: calStart,
		startDate: calStart,
		endDate: calEnd,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15,
		weekStart: dayStart
	});
});