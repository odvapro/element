el.table = 
{
	init:function()
	{
		$(document).click(function(event)
		{
		    if (!$(event.target).is(".tablearrow"))
		    	el.table.hideSettings()
		});
	},
	openFieldSettings:function(instance)
	{
		el.table.hideSettings();
		$(instance).parents('th').addClass('open-settings');
	},
	hideSettings:function()
	{
		$('th.open-settings').removeClass('open-settings');
	},

	setsort:function(instance,direction)
	{
		var fieldName =  $(instance).parents('th').data('code');
		window.location = '?sort='+fieldName+'&sortdir='+direction;
	}
}