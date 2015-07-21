$(document).ready(function()
{
	$('input.datepicker').datetimepicker({
		timepicker:false,
		format:'Y-m-d'
	});
	$('input.datetimepicker').datetimepicker({
		format:'Y-m-d H:i'
	});
});