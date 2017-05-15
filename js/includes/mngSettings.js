$(document).ready( function () {
	var calLocal = $("#calLocal").val();
	$("select#calLocalization option").each(function() { this.selected = (this.text == calLocal); });
});