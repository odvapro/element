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
		showRanamePopup:function()
		{
			var popupHtml = $('#filyetTPLS ._renameTviewPopup').html();
			el.popup.show(popupHtml);
		},
		delete:function(instance)
		{
			$.ajax({
				url      : el.config.baseUri+"tview/delete",
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
				url      : el.config.baseUri+"tview/add",
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
		rename:function(instance)
		{
			$.ajax({
				url      : el.config.baseUri+"tview/rename",
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
		saveTviewSettings:function(instance)
		{
			$.ajax({
				url      : el.config.baseUri+"tview/save",
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
		},
		setViewAsDefault:function(viewId)
		{
			if(typeof viewId == 'undefined')
				return el.message.error('отображение не может быть по умолчанию');
			$.ajax({
				url      : el.config.baseUri+"tview/setAsDefault",
				type     : 'POST',
				dataType : 'json',
				data     : {viewId:viewId}
			}).done(function(e)
			{
				if(typeof e.success != 'undefined' && e.success == true)
					el.message.success('Настрокйик сохранены');
				else
					el.message.error('что-то пошло не так');
			});
		}
	}
}