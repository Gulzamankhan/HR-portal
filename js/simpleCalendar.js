
(function(factory) {
	if(typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else {
		factory(jQuery);
	}
}(function($) {
	var defaults = {
		todayButton:		false,
		showInput:			false,
		weekStart:			0,
		widget:				true,
		cellRatio:			1,
		format:				'd/m/y',
		footer:				false,
		dayHeader:			true,
		mode:				'widget',
		animDuration:		200,
		transition:			'',
		tableClasses:		'table table-condensed',
		hidden:				true,
		setOnMonthChange:	true,
		condensed:			false
	};

	var now = new Date();

	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var shortMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var shortDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

	Date.prototype.daysInMonth = function(delta) {
		delta = delta === undefined ? 0 : delta;

		return new Date(this.getFullYear(), this.getMonth() + 1 + delta, 0).getDate();
	}

	Date.prototype.isDay = function(day) {
		if(day === undefined) {
			day = new Date();
		}

		return this.getFullYear() == day.getFullYear()
			&& this.getMonth() == day.getMonth()
			&& this.getDate() == day.getDate();
	}

	Date.prototype.isValid = function() {
		return Object.prototype.toString.call(this) === "[object Date]" && !isNaN(this.getTime());
	}

	$.fn.simpleCalendar = function(method) {
		var pMethods = {
			drawCalendar: function(selectedDate, replace) {
				var options = $(this).data('options');

				selectedDate = selectedDate || now;

				if(replace !== undefined && replace == true) {
					this.empty();
				}

				pMethods.drawHeader(selectedDate, options).appendTo(this);

				var month = pMethods
					.drawMonth(selectedDate, options)
					.addClass('current');

				$('<div />')
					.addClass('simpleCalendar-month')
					.html(month)
					.appendTo(this);

				pMethods.drawFooter(selectedDate, options).appendTo(this);

				$(this).data('simpleCalendar', true);
				$(this).data('date', selectedDate);
				$(this).data('element', this);

				return this;
			},
			drawPopupCalendar: function(selectedDate, replace) {
				var options = $(this).data('options');

				selectedDate = selectedDate || now;

				var container, calendar;

				if($(this).parent('.simpleCalendar-popup-wrapper').length) {
					container = $(this).parent();
					calendar = $(this).parent().find('.simpleCalendar-popup');
					calendar.empty();
				} else {
					container = $('<div />').addClass('simpleCalendar-popup-wrapper');
					calendar = $('<div />')
						.addClass('simpleCalendar simpleCalendar-popup')
						.width($(this).outerWidth(true));

					$(this).wrap(container);
				}

				$(this).after(calendar);

				if(options.hidden) {
					calendar.hide();
				}

				pMethods.drawHeader(selectedDate, options).appendTo(calendar);

				var month = pMethods
					.drawMonth(selectedDate, options)
					.addClass('current');

				$('<div />')
					.addClass('simpleCalendar-month')
					.html(month)
					.appendTo(calendar);

				calendar.data('simpleCalendar', true);
				calendar.data('date', selectedDate);
				calendar.data('element', this);
				calendar.data('options', options);

				$(this).parent().wrap($('<div />').addClass('simpleCalendar-affix'));

				return this;
			},
			drawHeader: function(date, options) {
				var header = $('<div />').addClass('simpleCalendar-header');
				var monthNames = options.shortMonths ? shortMonths : months;

				$('<button />')
					.addClass('prev-month change-month btn btn-calendar btn-xs')
					.html('<i class="fa fa-chevron-left"></i>')
					.appendTo(header);

				$('<button />')
					.addClass('next-month change-month btn btn-calendar btn-xs')
					.html('<i class="fa fa-chevron-right"></i>')
					.appendTo(header);

				$('<span />')
					.addClass('month')
					.append('<div>' + monthNames[date.getMonth()] + ' ' + date.getFullYear() + '</div>')
					.appendTo(header);

				return header;
			},
			drawMonth: function(date, options) {
				date = date || now;
				var monthStart = new Date(date.getFullYear(), date.getMonth(), 1, 0, 0, 0);
				var days = [];
				var rows = [];
				var table = $('<table />').addClass(options.tableClasses);

				var numPrevDays = monthStart.getDay() - options.weekStart;
				var numCurrentDays = date.daysInMonth();
				var numNextDays = 42 - numPrevDays - numCurrentDays;

				var daysInLastMonth = date.daysInMonth(-1);

				// Header
				if(options.dayHeader) {
					var tableHeader = $('<tr />');

					for(var i = 0; i <= 6; i++) {
						var day = i + options.weekStart;

						if(day > 6) {
							day = i - 6;
						}

						$('<th />')
							.text(shortDays[day])
							.appendTo(tableHeader);
					}

					table.append(tableHeader);
				}

				for(var i = 1; i <= numPrevDays; i++) {
					var day = (daysInLastMonth - numPrevDays) + i;

					days.push({
						date: new Date(date.getFullYear(), date.getMonth() - 1, day, 0, 0, 0),
						displayNumber: day,
						classes: 'month-prev'
					});
				}

				for(var i = 1; i <= numCurrentDays; i++) {
					var day = {
						date: new Date(date.getFullYear(), date.getMonth(), i, 0, 0, 0),
						displayNumber: i,
						classes: ''
					};

					day.classes = day.date.isDay() ? 'today' : '';
					day.classes += day.date.isDay(date) ? ' selected' : '';
					days.push(day);
				}

				for(var i = 1; i <= numNextDays; i++) {
					days.push({
						date: new Date(date.getFullYear(), date.getMonth() + 1, i, 0, 0, 0),
						displayNumber: i,
						classes: 'month-next'
					});
				}

				rows = [
					days.slice(0, 7),
					days.slice(7, 14),
					days.slice(14, 21),
					days.slice(21, 28),
					days.slice(28, 35),
					days.slice(35)
				];

				for(var row = 0; row < 6; row++) {
					var tr = $('<tr />');

					for(var col = 0; col < 7; col++) {
						var cell = rows[row][col];

						$('<td />')
							.data('date', cell.date)
							.text(cell.displayNumber)
							.addClass(cell.classes)
							.appendTo(tr);
					}

					tr.appendTo(table);
				}

				return table;
			},
			drawFooter: function(date, options) {
				var footer = $('<div />').addClass('simpleCalendar-footer input-prepend');

				if(options.footer == false) {
					footer.hide();
				}

				if(options.todayButton) {
					$('<button />')
						.text('Today')
						.addClass('btn simpleCalendar-today')
						.attr('type', 'button')
						.appendTo(footer);
				}

				if(options.showInput) {
					$('<span />')
						.text(pMethods.formatDate(date, options))
						.addClass('simpleCalendar-input uneditable-input span2')
						.appendTo(footer);
				}

				$('<input />')
					.prop('type', 'hidden')
					.val(parseInt(date.getTime() / 1000), 10)
					.appendTo(footer);

				return footer;
			},
			formatDate: function(date, options) {
				return options.format
					.replace('d', ('0' + date.getDate()).substr(-2))
					.replace('m', ('0' + (date.getMonth() + 1)).substr(-2))
					.replace('y', date.getFullYear().toString().substr(-2))
					.replace('Y', date.getFullYear());
			}
		};

		var methods = {
			init: function(settings) {
				var options = $.extend({}, defaults, settings);

				if(!$(document).data('simpleCalendar-events')) {
					$(document).on('click.simpleCalendar', '.simpleCalendar .change-month', function(e) {
							e.preventDefault();
							e.stopPropagation();

							methods.changeMonth.apply($(this).closest('.simpleCalendar'), [ $(this).hasClass('next-month') ? 1 : -1, $(this).closest('.simpleCalendar').data('options') ]);
						})
						.on('click.simpleCalendar', '.simpleCalendar-today', function() {
							methods.changeMonth.apply($(this).closest('.simpleCalendar'), [ now, $(this).closest('.simpleCalendar').data('options') ]);
						})
						.on('click.simpleCalendar', '.simpleCalendar table.current td', function() {
							var container = $(this).closest('.simpleCalendar');
							var table = $(this).closest('table');

							table.find('.selected').removeClass('selected');

							$(this).addClass('selected');

							container.find('.simpleCalendar-footer').replaceWith(pMethods.drawFooter($(this).data('date'), $(this).closest('.simpleCalendar').data('options')));
							container.data('date', $(this).data('date'));
						})
						.on('click.simpleCalendar', '.simpleCalendar-popup-trigger', function(e) {
							$(this).parent('.simpleCalendar-popup-wrapper').addClass('simpleCalendar-open').find('.simpleCalendar-popup').show();
							$('.simpleCalendar-popup-wrapper.simpleCalendar-open').not($(this).parent()).removeClass('simpleCalendar-open').find('.simpleCalendar-popup').hide();
						})
						.on('click.simpleCalendar', function(e) {
							var target = $(e.target);

							if(!target.closest('.simpleCalendar-popup-wrapper').length) {
								$('.simpleCalendar-popup-wrapper.simpleCalendar-open').removeClass('simpleCalendar-open').find('.simpleCalendar-popup').hide();
							}
						})
						.on('click.simpleCalendar', '.simpleCalendar td', function() {
							var thisDate = $(this).data('date');
							var originalElement = $(this).closest('.simpleCalendar').data('element');
							var formattedDate = pMethods.formatDate($(this).data('date'), $(this).closest('.simpleCalendar').data('options'));

							$(originalElement).trigger('dateselect', [ thisDate ]);
							$(originalElement).children('.simpleCalendar-popup-trigger').val(formattedDate).trigger('change');
						});

					$(document).data('simpleCalendar-events', true);
				}

				return this.each(function() {
					if($(this).is(':input')) {
						var element = $('<div />');

						$(this).addClass('simpleCalendar-target').after(element);
					} else {
						var element = this;
					}

					$(element).addClass('simpleCalendar ' + options.transition);
					$(element).data('options', options);

					if(options.transition) {
						$(element).addClass('transition');
					}

					if(options.condensed) {
						$(element).addClass('condensed');
					}

					switch(options.mode) {
						case 'popup':
							$(element).addClass('simpleCalendar-popup-trigger');

							pMethods.drawPopupCalendar.apply(element, options.date);
						break;
						case 'widget':
						default:
							pMethods.drawCalendar.call(element, options.date);
					}
				});
			},
			changeMonth: function(month, options) {
				var newDay, newDate, direction, newCalendar;

				var container = $(this);
				var calendar = $(this).find('table');

				var currentDate = container.data('date');
				var calWidth = calendar.outerWidth(true);
				var calHeight = calendar.outerHeight(true);

				calendar.parent().height(calHeight);

				if(typeof month === 'number') {
					direction = month > 0 ? 1 : -1;
					newDay = Math.min(currentDate.daysInMonth(month), currentDate.getDate());
					newDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + direction, newDay);
				} else if(month instanceof Date) {
					direction = (month > currentDate) ? 1 : -1;
					newDate = now;
				}

				calendar.stop(true, true);
				container.data('date', newDate);
				newCalendar = pMethods.drawMonth(newDate, options).addClass('current');

				switch(options.transition) {
					case 'fade':
						calendar.fadeOut(options.animDuration, function() {
							$(this).replaceWith(newCalendar.hide().fadeIn(options.animDuration));
						});
					break;
					case 'crossfade':
						calendar.removeClass('current').after(newCalendar);
						calendar.animate({ opacity: 0 }, options.animDuration);

						newCalendar.css({ opacity: 0, position: 'absolute', top: 0 }).animate({ opacity: 1 }, options.animDuration);
					break;
					case 'carousel-horizontal':
						calendar.css({ position: 'absolute' }).animate({ left: -(calWidth * direction) }).after(newCalendar);

						newCalendar.css({ left: direction * calWidth, position: 'absolute' }).animate({ left: 0 });
					break;
					case 'carousel-vertical':
						calendar.css({ position: 'absolute' }).animate({ top: -(calHeight * direction) }).after(newCalendar);

						newCalendar.css({ top: direction * calHeight, position: 'absolute' }).animate({ top: 0 });
					break;
					default:
						calendar.replaceWith(newCalendar);
					break;
				}

				newCalendar.promise().done(function() {
					container.find('table').not(newCalendar).remove();
				});

				container.find('.simpleCalendar-header').replaceWith(pMethods.drawHeader(newDate, options));
				container.find('.simpleCalendar-footer').replaceWith(pMethods.drawFooter(newDate, options));

				if(options.setOnMonthChange) {
					container.prev('.simpleCalendar-target').val($(this).data('date')).trigger('change');
				}
			},
			date: function() {
				if($(this).next('.simpleCalendar').length) {
					return $(this).next('.simpleCalendar').data('date');
				} else if($(this).data('simpleCalendar')) {
					return $(this).data('date');
				}

				return false;
			}
		};

		if(methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if(typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}
	};
}));
