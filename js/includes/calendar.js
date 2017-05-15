
$(document).ready( function () {
	var langCode = $('#langCode').val();
	var dayStart = $('#dayStart').val();
	var theUser  = $('#theUser').val();
	var noDesc   = $('#noDesc').val();

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
			'includes/events.php',
			'includes/publicEvents.php'
		],
		eventClick:  function(event) {
			var eventDates,
				eventTime,
				eventDesc;

			if (event.description !== null) {
				eventDesc = event.description;
			} else {
				eventDesc = noDesc;
			}

			if (event.startTime !== null) {
				eventTime = event.startTime+' &mdash; '+event.endTime;
			} else {
				eventTime = '';
			}

			if (theUser === event.userId) {
				$('#delBtn').show();
				$('#editBtn').show();
			} else {
				$('#delBtn').hide();
				$('#editBtn').hide();
			}

            $('#modalTitle').html(event.title);
            $('#eventDescription').html(eventDesc);
            $('#eventTimes').html(eventTime);
            $('#createdBy').html('Saved by: '+event.createdBy);
			$('#fullCalModal').modal();

			$('#deleteTitle').html(event.title);
			$('#delTitle').val(event.title);
			$('#deleteId').val(event.eventId);

			$('#editTitle').html('Edit Event: '+event.title);
			$('#editId').val(event.eventId);
			$('#eTitle').val(event.title);
			$('#eDesc').val(event.description);
        },
		loading: function(bool) {
			$('#loading').toggle(bool);
		}
    });

	$('.fc-month-button').before('<a href="#newDate" data-toggle="modal" class="fc-button fc-state-default fc-state-new">New Date</a>');

	var dayStart = $('#dayStart').val();
	$('#newstartDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('#neweventTime').datetimepicker({
		format: 'hh:ii',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15,
		weekStart: dayStart
	});

	$('#newendDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
	$('#newendTime').datetimepicker({
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