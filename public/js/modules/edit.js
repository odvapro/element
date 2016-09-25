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
	},

	/*Открывает форму добавления связи*/
	getNodeAddForm : function(instance,fieldName)
	{
		// проверка на множественность поля
		var multiple = $(instance).parents('.filedEdit').data('multiple');
		if(multiple != 1 && $(instance).parents('.filedEdit').find('.node').size() > 0 )
		{
			el.message.error('это поле не множественное');
			return false;
		}

		var tableName = $(instance).parents('form').find('input[name="tableName"]').val();
		$.ajax({
			url: el.config.baseUri+"table/getNodeAddForm",
			type:'POST',
			dataType:'json',
			data: { tableName:tableName, fieldName:fieldName },
		}).done(function(e)
		{
			if(typeof e.result != "undefined" && e.result == "success")
				el.popup.show(e.form);
		});
		return false;
	},

	/*Добавлят свзяь в поле*/
	addNode : function(instance, fieldName, tableName)
	{
		var nodeVal     = parseInt($('#nodeAddForm input[name="node"]').val());
		var nodeTextVal = $('#nodeAddForm input[name="nodetext"]').val();
		if(nodeVal > 0)
		{
			var nodeTPL = $('.NodeFieldTPL.f'+fieldName).html();
				nodeTPL = nodeTPL.replace(/#value#/g,nodeVal)
					.replace(/#searchValue#/g,nodeTextVal)
					.replace(/#fieldName#/g,fieldName);
			$('.filedEdit[data-fieldName="'+fieldName+'"] .nodes .attachAdd').before(nodeTPL);
			el.popup.hide();
		}
		else
			el.message.error('Введите число');
	},

	/**
	 * Удаляет связь с другим элементом
	 * @param  instance - dom элемент крестик
	 * @return void
	 */
	removeNode : function(instance)
	{
		$(instance).parents('.node').fadeOut(200,function()
		{
			$(this).remove();
		});
	},

	/**
	 * Срабатывает на keyup, подгружает нужные данные по ajax выводит под полем
	 * на вверх,вниз,enter происходит выбор подгруженного
	 * @param  {[type]}
	 * @return void
	 */
	autoComlete : function(instance,nodeTable,nodeField,nodeSearch)
	{
		event.preventDefault();
		if(event.keyCode == 37 || event.keyCode == 39) return false;
		switch(event.keyCode)
		{
			// вниз
			case 40:
				var selectedEl    = $(instance).siblings('.autocomleatebox').find('.autocomplLine.selected');
				var selectedIndex = (typeof selectedEl != 'undefined')?selectedEl.index():-1;
				selectedIndex++;
				$(instance).siblings('.autocomleatebox').find('.autocomplLine')
					.removeClass('selected')
					.eq(selectedIndex).addClass('selected');
			break;
			// ввер
			case 38:
				var selectedEl    = $(instance).siblings('.autocomleatebox').find('.autocomplLine.selected');
				var selectedIndex = (typeof selectedEl != 'undefined')?selectedEl.index():-1;
				selectedIndex--;
				$(instance).siblings('.autocomleatebox').find('.autocomplLine')
					.removeClass('selected')
					.eq(selectedIndex).addClass('selected');
			break;
			// ентер
			case 13:
				var selectedEl = $(instance).siblings('.autocomleatebox').find('.autocomplLine.selected');
				if(typeof selectedEl != 'undefined')
				{
					var elId = selectedEl.find('i').html();
					var autocompleteIdInput = $(instance).data('autocompleteid');
					autocompleteIdInput = $(instance).siblings('input[name="'+autocompleteIdInput+'"]');
					$(instance).val(selectedEl.find('.text').html());
					autocompleteIdInput.val(elId);
					$(instance).siblings('.autocomleatebox').remove();
				}
			break;
			// любой другой символ
			// производим поиск, обдновляем список
			default:
				$.ajax({
					url      : el.config.baseUri+"table/autoComplete",
					type     : 'POST',
					dataType : 'json',
					data     : {nodeTable:nodeTable,nodeField:nodeField,nodeSearch:nodeSearch,q:$(instance).val()}
				}).done(function(e)
				{
					if(typeof e.result != 'undefined' && e.result == 'success')
					{
						// обновляем список автокомплита
						var elementsList = '';
						$.each(e.elements, function(index,el)
						{
							elementsList += '<div class="autocomplLine"><span class="elid">[<i>'+el.id+'</i>]</span><span class="text">'+el.name+'</span></div>';
						});
						$(instance).siblings('.autocomleatebox').remove();
						if(elementsList != '')
							$(instance).after('<div class="autocomleatebox">'+elementsList+'</div>');
					}
				});
			break;
		}
	}
}