$(document).ready(function() {
	var weatherLoc = $("#weatherLoc").val();

	$.simpleWeather({
		woeid: weatherLoc,
		unit: 'f',

		success: function(weather) {
			html = '<h2><i class="icon-'+weather.code+'"></i> '+weather.temp+'&deg;'+weather.units.temp+'</h3>';
			html += '<ul><li>'+weather.city+', '+weather.region+'</li>';
			html += '<li class="currently">'+weather.currently+'</li>';
			html += '</ul>';
			$("#weather").html(html);
		},
		error: function(error) {
			$("#weather").html('<p>'+error+'</p>');
		}
	});
});