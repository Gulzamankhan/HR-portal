$(document).ready( function () {
	var origMgrId = $("#origMgrId").val();
	$("select#managerId option").each(function() { this.selected = (this.text == origMgrId); });

	$('#hideIt').hide();

	$('#showIt').click(function(e) {
		e.preventDefault();
		$('#password1').prop('type','text');
		$('#password2').prop('type','text');
		$('#showIt').hide();
		$('#hideIt').show();
	});
	$('#hideIt').click(function(e) {
		e.preventDefault();
		$('#password1').prop('type','password');
		$('#password2').prop('type','password');
		$('#hideIt').hide();
		$('#showIt').show();
	});

	$('#generate').click(function (e) {
		e.preventDefault();

		var pwd = generatePassword(8);

        $('#password1').val(pwd);
		$('#password2').val(pwd);
    });

	$('#terminate').change(function() {
		var terminate = $('#terminate').val();
		if (terminate === '0') {
			$('#terminationDate').val('');
			$('#terminationReason').val('');
		}
    });

	var dayStart = $('#dayStart').val();
	$('#terminationDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});
});