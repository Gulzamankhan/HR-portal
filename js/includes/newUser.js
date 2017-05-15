$(document).ready( function () {
	var dayStart = $('#dayStart').val();
	$('#userHireDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});

		$('#visaexpiry').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0,
		weekStart: dayStart
	});

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
});