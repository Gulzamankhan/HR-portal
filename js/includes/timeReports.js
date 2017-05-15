$(document).ready( function () {
	$(".selectall").change(function(e) {
		var ct = e.currentTarget;

		var o = ct.options[0];
		var t = ct.options[0].text;
		var s = ct.options[0].selected;

		if(s && (t == "All Users")) {
			for(var i = 1; i < ct.options.length; i++) {
				ct.options[i].selected = false;
			}
		}
	});

	var dayStart = $('#dayStart').val();
	$('#recFromDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('#recToDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});

	$('#logFromDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('#logToDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
});