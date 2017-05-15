$(document).ready( function () {
	var langCode			= $('#langCode').val();
	var dayStart			= $('#dayStart').val();
	var schedForText		= $('#schedForText').val();
	var addedByText			= $('#addedByText').val();
	var dnldTemplateText	= $('#dnldTemplateText').val();
	var uplSchedText		= $('#uplSchedText').val();
	var editSchedText		= $('#editSchedText').val();

	$('#calendar').fullCalendar({
        header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek'
		},
		lang: langCode,
		firstDay: dayStart,
		eventLimit: true,
		eventSources: [
			'includes/mngSchedules.php',
		],
		
		selectable: true,
		select: function(start) {
			var eventData;
			eventData = {
				start: start
			};
			$('#addTimes').modal();
			$('#schedDay').val(start.format());
			$('#calendar').fullCalendar('unselect');
		},
		loading: function(bool) {
			$('#loading').toggle(bool);
		}
	});

	$('input[name="startTime"]').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('input[name="endTime"]').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	
	   $('#docs').dataTable({
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
		"order": [ 2, 'asc' ],
		"pageLength": 25
	});

	$('#docs').addClass('pb-10');
});

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