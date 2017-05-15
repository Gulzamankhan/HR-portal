$(document).ready( function () {
	var langCode	 = $('#langCode').val();
	var dayStart	 = $('#dayStart').val();
	var schedForText = $('#schedForText').val();
	var addedByText	 = $('#addedByText').val();

	$('#calendar').fullCalendar({
        header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek'
		},
		lang: langCode,
		firstDay: dayStart,
		//weekNumbers: true,
		eventSources: [
			'includes/mySchedule.php',
		],
		eventClick:  function(event) {
			var eventDates,
				eventTime,
				eventDesc;

			if (event.description !== null) {
				eventDesc = event.description;
			} else {
				eventDesc = '';
			}

			if (event.startTime !== null) {
				eventTime = event.startTime+' &mdash; '+event.endTime;
			} else {
				eventTime = '';
			}

			$('#modalTitle').html(schedForText+' '+event.usersName);
            $('#schedHoursription').html(eventDesc);
            $('#schedTimes').html(eventTime);
            $('#createdBy').html(addedByText+' '+event.createdBy);
			$('#fullCalModal').modal();
		},
		loading: function(bool) {
			$('#loading').toggle(bool);
		}
	});
});