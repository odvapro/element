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
	},
	filter:
	{
		openSubPopup:function(instance,event)
		{
			this.closeSubPopup(event);
			$(instance).parents('._filterWraper').addClass('open');
			$(instance).addClass('open');
		},
		closeSubPopup:function(event)
		{
			event.stopPropagation();
			$('.fBlock,._filterWraper').removeClass('open');
		}
	}
}