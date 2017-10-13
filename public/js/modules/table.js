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
	view:
	{
		showAddPopup:function()
		{
			var popupHtml = $('#filyetTPLS ._addTviewPopup').html();
			el.popup.show(popupHtml);
		},
		showDeletePopup:function(viewId)
		{
			if(typeof viewId == 'undefined')
				return el.message.error('Нельзя удалять основное отображение');
			var popupHtml = $('#filyetTPLS ._deleteTviewPopup').html();
			el.popup.show(popupHtml);
		},
		delete:function(instance)
		{
			$.ajax({
				url      : el.config.baseUri+"table/deleteView",
				type     :'POST',
				dataType :'json',
				data     : $(instance).serialize()
			}).done(function(e)
			{
				if(typeof e.success != 'undefined' && e.success == true)
					window.location = e.url;
				else
					el.message.error('что-то пошло не так');
			});
		},
		add:function(instance)
		{
			$.ajax({
				url      : el.config.baseUri+"table/addView",
				type     :'POST',
				dataType :'json',
				data     : $(instance).serialize()
			}).done(function(e)
			{
				if(typeof e.success != 'undefined' && e.success == true)
					window.location = e.url;
				else
					el.message.error('что-то пошло не так');
			});
		},
		saveTviewSettings:function(instance)
		{
			$.ajax({
				url      : el.config.baseUri+"table/saveView",
				type     :'POST',
				dataType :'json',
				data     : $(instance).serialize()
			}).done(function(e)
			{
				if(typeof e.success != 'undefined' && e.success == true)
					window.location.reload();
				else
					el.message.error('что-то пошло не так');
			});
		},
		showApplyInput:function(instance)
		{
			$(instance).parents('.filterInput').addClass('changed');
		}
	}
}