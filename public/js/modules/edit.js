/* скрипты страницы добавления элемента и редактирования */
el.edit =  
{
	/*сохраняет/добавляет элемент в базу*/
	save : function()
	{
		$form = $('#elementForm');
		var formData = $form.serialize();
		$.ajax({
			url: el.config.baseUri+"table/save",
			type:'POST',
			dataType:'json',
			data: formData,
		}).done(function(e)
		{
			if(typeof e.result != 'undefined' && e.result == 'success')
			{
				if(typeof e.type != 'undefined' && e.type == 'update')
				{
					el.message.success('Элемент сохранен.');
					// если есть фйлы загруженные во временные папки
					// нужно обновить страницу после, для обновления значений полей файлов
					// обновления путей и тд 
					if($('input.tmpfield').size() > 1)
					{
						setTimeout(function()
						{
							window.location.reload();
						},300);
					}
				}
				else
				{
					el.message.success('Элемент добавлен.');
					// паренаправление на форму редактирования
					setTimeout(function()
					{
						var tableName = $form.find('input[name="tableName"]').val();
						window.location.href = el.config.baseUri+'table/'+tableName+'/edit/'+e.elId;
					},300);
				}
			}
			else
				el.message.error(e.msg);
		});
		return false;
	},
	
	/*Удаляет элемент из таблицы*/
	delete : function(instance, inner)
	{
		if(typeof inner != 'undefined' && inner == true)
		{
			var tableName = $('input[name="tableName"]').val();
			var primaryKey = $('input[name="primaryKey"]').val();
			var primaryIndexValue = $('input[name="field['+primaryKey+']"]').val();
		}
		else
		{
			var tableName = $(instance).parents('table').data('tablename');
			var primaryKey = $(instance).parents('table').data('primarykey');
			var primaryIndexValue = $(instance).parents('tr').data('id');
		}
		$.ajax({
			url: el.config.baseUri+"table/delete/"+tableName+"/"+primaryKey+"/"+primaryIndexValue,
			type:'POST',
			dataType:'json',
		}).done(function(e)
		{
			if(typeof e.result != 'undefined')
			{
				if(typeof inner != 'undefined' && inner == true)
					window.history.back();
				else
					$(instance).parents('tr').fadeOut();
			}
		});
		return false;
	}
}
