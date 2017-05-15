
function generatePassword(limit) {
	limit = limit || 6;
	var password = '';
	var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"$&=^*#_-@+,.';
	var list = chars.split('');
	var len = list.length,
	i = 0;
	do {
		i++;
		var index = Math.floor(Math.random() * len);
		password += list[index];
	}
	while (i < limit);
	return password;
}

jQuery(document).ready(function($) {

	var dayStart = $('#dayStart').val();
	var weekStart;

		 if (dayStart === '0') { weekStart = 0; }	// 0 = Sunday
	else if (dayStart === '1') { weekStart = 1; }	// 1 = Monday
	else if (dayStart === '2') { weekStart = 2; }	// 2 = Tuesday
	else if (dayStart === '3') { weekStart = 3; }	// 3 = Wednesday
	else if (dayStart === '4') { weekStart = 4; }	// 4 = Thursday
	else if (dayStart === '5') { weekStart = 5; }	// 5 = Friday
	else if (dayStart === '6') { weekStart = 6; }	// 6 = Saturday

	$('.cal-widget').simpleCalendar({
		transition: 'carousel-horizontal',
		weekStart: weekStart
	});


    $("[data-toggle='tooltip']").tooltip();

	$("[data-toggle='popover']").popover();

	$('.msgClose').click(function(e){
		e.preventDefault();
		$(this).closest('.alertMsg').fadeOut("slow", function() {
			$(this).addClass('hidden');
		});
	});

	var placehold = {
		init: function(){
			$('input[type="text"], input[type="email"], input[type="password"], textarea').each(placehold.replace);
		},
		replace: function(){
			var txt = $(this).data('placeholder');
			if (txt) {
				if ($(this).val()=='') {
					$(this).val(txt);
				}
				$(this).focus(function(){
					if ($(this).val() == txt){
						$(this).val('');
					}
				}).blur(function(){
					if ($(this).val() == ''){
						$(this).val(txt);
					}
				});
			}
		}
	}
	placehold.init();

	$("form :input[required='required']").blur(function() {
		if (!$(this).val()) {
			$(this).addClass('hasError');
		} else {
			if ($(this).hasClass('hasError')) {
				$(this).removeClass('hasError');
			}
		}
	});
	$("form :input[required='required']").change(function() {
		if ($(this).hasClass('hasError')) {
			$(this).removeClass('hasError');
		}
	});
});