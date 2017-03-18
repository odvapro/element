/* скрипты страницы настроек */
el.settings =  
{
	submit : function()
	{
		var formData = $('#settingsForm').serialize();
		$.ajax({
			url: el.config.baseUri+"settings/save",
			type:'POST',
			dataType:'json',
			data: formData,
		}).done(function(e)
		{
			if(typeof e.result != 'undefined' && e.result == 'success')
			{
				el.message.success('Настрокйки сохранены.');
				setTimeout(function(){
					window.location.reload();
				},2000);
			}
			else
				el.message.error('Неизвестная шибка.');
		});
		return false;
	},

	/*показывает поле для изменения названия таблицы*/
	showEditTable : function(instance)
	{
		$(instance).parent('.tableName').find('input').addClass('show').focus();
	},
	
	/*скрывает поле для изменения названия таблицы*/
	hideEditTable:function(instance)
	{
		$(instance).removeClass('show');
	},

	/*синхронизирует значение в поле с текстом*/
	refreshTableName : function(instance)
	{
		var curVal = $(instance).val();
		$(instance).parent('.tableName').find('span.tableNamePlace').html(curVal);
	},

	/**
	 * Открывает настройки данного поля 
	 * отправляет запрос на сервер, для формы редактирования
	 * запрос отправляется сюда settings/getFieldForm
	 * @param  instance -  dom элемент ссылка по коотрой открывается форма
	 * @param  fieldName - название поля для которого открываются настройки
	 * @return void
	 */
	showFieldSetiings : function(instance,fieldName)
	{
		var tableName = $(instance).parents('table').data('tablename');
		$.ajax({
			url: el.config.baseUri+"settings/getFieldForm",
			type:'POST',
			dataType:'json',
			data: { tableName:tableName, fieldName:fieldName },
		}).done(function(e)
		{
			if(typeof e.result != 'undefined' && e.result == "success" && typeof e.form != 'undefined')
				el.popup.show(e.form);
			else
				el.message.error('Неопознанная ошибка');
			// после обновление значений (обязательное поле и множественное) 
		});
	},

	/**
	 * Cохранение настроек конкретного своства 
	 * форма берется тут settings/getFieldForm 
	 * на сохранение отправляется settings/saveFieldForm
	 * @param  instance - dom кнопка в форме
	 * @return false
	 */
	fieldSettingsSubmit : function(instance)
	{
		var formData = $(instance).parents('form').serialize();
		$.ajax({
			url      : el.config.baseUri+"settings/saveFieldForm",
			type     :'POST',
			dataType :'json',
			data     : formData,
		}).done(function(e)
		{
			if(typeof e.result != 'undefined' && e.result == 'success')
			{
				el.message.success('Настрокйки сохранены.');
				el.popup.hide();
				// после обновление значений (обязательное поле и множественное)
			}
			else
				el.message.error('Неизвестная шибка.');
		});
		return false;
	},

	/**
	 * Изменение таблицы в форме настроек типа поля - связка
	 * при смене таблицы подменяются поля в поле настройки поля связки
	 * @param  instance  - dom эелеент  селект который изменяется
	 * @return void
	 */
	fieldSettingsTableChange : function(instance)
	{
		var curVal = $(instance).val();
		var resOptions = '';
		if(curVal != '0')
		{
			$.each(el.config.tables[curVal].fields, function(key, value)
			{
				resOptions += '<option value="'+key+'">'+key+'('+value+')</option>';
			});
		}
		$('select[name="set[nodeField]"]').html(resOptions);
		$('select[name="set[nodeSearch]"]').html(resOptions);
	},

	/**
	 * Отправляет запрос на проверку обновлений
	 * @return void
	 */
	checkUpdates : function()
	{
		$.ajax({
			url      : el.config.baseUri+"settings/checkUpdates",
			type     :'POST',
			dataType :'json'
		}).done(function(e)
		{
			if(typeof e.result != 'undefined')
			{
				if(e.result == 'success')
				{
					$('#refreshUpdates').html(e.msg);
					if(e.hasUpdates === true)
					{
						// имеются обновления 
						$('.updatingbutton').show();
					}
				}
				else
					el.message.error(e.msg);
			}
			else
				el.message.error('Неизвестная шибка.');
		});
	},

	/*Обновление системы до последней версии
	  в первую очередь скрываются кнопки обновления 
	  и показывается строка о том что проводится обновление*/
	updateSystem : function()
	{
		$('.updateBox').hide().after('<p class="centered updatingline">Обновление...</p>');
		$.ajax({
			url: el.config.baseUri+"settings/updateSystem",
			type:'POST',
			dataType:'json'
		}).done(function(e)
		{
			if(typeof e.result != 'undefined' && e.result == 'success')
			{
				$('.updatingline').hide().after('<p class="centered" style="color:green; font-weight:bold;">'+e.msg+'</p>');
			}
			else
				el.message.error('Что-то пошло не так.');
		});
	},

	/*Сохранение пользователя*/
	saveUser : function()
	{
		var userFormData = $('#userForm').serialize();
		var userId = $('input[name="userId"]').val();
		$.ajax({
			url: el.config.baseUri+"settings/saveUser/"+userId,
			type:'POST',
			dataType:'json',
			data:userFormData
		}).done(function(e)
		{
			if(typeof e.result != 'undefined')
			{
				if(e.result == 'success')
				{
					el.message.success(e.msg);
					$('input[type="password"]').val('');
				}
				else
					el.message.error(e.msg);
			}
			else
				el.message.error('Что то пошло не так');
		});
	},

	fieldName:
	{
		/**
		 * Показывает форму редатирования названия на странице таблицы
		 * @param  domnode instance 
		 * @return void
		 */
		showTableEdit:function(instance)
		{
			var editForm = $('.TPLS .fieldNameEditForm').html(),
			tableName    = $(instance).parents('table').data('tablename'),
			fieldName    = $(instance).parents('th').data('code'),
			value		 = $(instance).parents('ul').siblings('._ename').html();

			$.ajax({
				url: el.config.baseUri+"settings/getFieldNameEditForm",
				type:'POST',
				dataType:'json',
				data: {tableName:tableName,fieldName:fieldName,fieldNewName:value},
			}).done(function(e)
			{
				if(typeof e.result != 'undefined' && e.result == 'success')
				{
					el.popup.show(e.form);
				}
				else
					el.message.error('Неизвестная шибка.');
			});
		},
		showEdit:function(instance)
		{
			var editForm = $('.TPLS .fieldNameEditForm').html(),
			tableName    = $(instance).parents('table').data('tablename'),
			fieldName    = $(instance).parents('tr').data('fieldname'),
			value		 = $(instance).siblings('._ename').html();

			$.ajax({
				url: el.config.baseUri+"settings/getFieldNameEditForm",
				type:'POST',
				dataType:'json',
				data: {tableName:tableName,fieldName:fieldName,fieldNewName:value},
			}).done(function(e)
			{
				if(typeof e.result != 'undefined' && e.result == 'success')
				{
					el.popup.show(e.form);
				}
				else
					el.message.error('Неизвестная шибка.');
			});
		},
		save:function(instance)
		{
			$.ajax({
				url: el.config.baseUri+"settings/saveFieldName",
				type:'POST',
				dataType:'json',
				data: $(instance).serialize(),
			}).done(function(e)
			{
				if(typeof e.result != 'undefined' && e.result == 'success')
				{
					el.message.success('Настрокйки сохранены.');
					el.popup.hide();
					$('table[data-tablename="'+e.data.table+'"] tr[data-fieldname="'+e.data.field+'"] ._ename').html(e.data.name);
					$('table[data-tablename="'+e.data.table+'"] th[data-code="'+e.data.field+'"] ._ename').html(e.data.name);
				}
				else
					el.message.error('Неизвестная шибка.');
			});
		}
	},

	/*обработка формы редактирования поля типа файл*/
	file : 
	{
		/*добавляет дополнительное поле для еще одного размера изображения*/
		addImageSizeLine : function(instance)
		{
			var imageSizeLineTPL = $('#TPLS .imageSizeTPL').html();
			// определить индекс нового поля
			var sizeIndex = $('.editLine .inp.imageSize').size()*1-1;
			imageSizeLineTPL = imageSizeLineTPL.replace(/#key#/g,sizeIndex);
			$('.newImageSizesDelimetr').before(imageSizeLineTPL);
			$('.popupContLine').scrollTop($('.popupContLine').height());
		},
		/* удяляет дополнительное поле для размера изображения
			также производится перерасчет ключей инпутов*/
		removeImageSizeLine : function(instance)
		{
			$(instance).parents('.editLine').fadeOut(200,function()
			{
				$(this).remove();
			});
			// перерасчет индексов полей ввода
			$('.popupContLine .editLine .inp.imageSize').each(function()
			{
				var index = $(this).index()*1;
				$(this).find('input').each(function()
				{
					$(this).attr('name',$(this).attr('name').replace(/\d/, index+1 ));
				});
			})
		}
	}
}