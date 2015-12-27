// init.js
var el = 
{
	/* configVars */
	config : 
	{
		baseUri : false
	},
	/* все что нуждается в инициализации */
	init : function()
	{
		this.popup.init();
	},
	tabs : 
	{
		show : function(instance)
		{
			$(instance).siblings('li').removeClass('act');
			$(instance).addClass('act');
			var curTabIndex = $(instance).index();
			$('.tabCont').removeClass('act').eq(curTabIndex).addClass('act');
		}
	},

	/* скрипты страницы настроек */
	settings : 
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

		/*открывает настройки данного поля
		  отправляет запрос на сервер, для формы редактирования
		  запрос отправляется сюда settings/getFieldForm*/
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

		/*сохранение настроек конкретного своства
		  форма берется тут settings/getFieldForm
		  на сохранение отправляется settings/saveFieldForm*/
		fieldSettingsSubmit : function(instance)
		{
			var formData = $(instance).parents('form').serialize();
			$.ajax({
				url: el.config.baseUri+"settings/saveFieldForm",
				type:'POST',
				dataType:'json',
				data: formData,
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

		/*изменение таблицы в форме настроек типа поля - связка
		  при смене таблицы подменяются поля в поле настройки поля связки*/
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
		},

		/*Отправляет запрос на проверку обновлений*/
		checkUpdates : function()
		{
			$.ajax({
				url: el.config.baseUri+"settings/checkUpdates",
				type:'POST',
				dataType:'json'
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
	},

	/* скрипты страницы добавления элемента и редактирования */
	edit : 
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

		/*открывает форму добавления файла
		  отправляется запрос на страницу table/getFileUploadForm
		  c именем поля и таблицы
		  рещультат открывается в попапе*/
		getFileUploadForm : function(instance,fieldName)
		{
			// проверка на множественность поля
			var multiple = $(instance).parents('.filedEdit').data('multiple');
			if(multiple != 1 && $(instance).parents('.filedEdit').find('.attach').size() > 0 )
			{
				el.message.error('это поле не множественное');
				return false;
			}

			var tableName = $(instance).parents('form').find('input[name="tableName"]').val();
			$.ajax({
				url: el.config.baseUri+"table/getFileUploadForm",
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
		
		/*событие по срабатывющее при изменении поля типа файл
		  и сразу происодит отправка файла на сервер*/
		uplaodFile : function(event, fieldName, tableName,uploadType)
		{
			uploadType = (typeof uploadType == 'undefined')?'fileInput':uploadType;
			var processData = true;
			var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
			switch(uploadType)
			{
				case 'byUrl':
					var data = {type:'byUrl'};
					data.url = $('.popupContLine #fileUploadURL').val();
				break;
				case 'fileInput':
					var data = new FormData();
					$.each(event.target.files, function(key, value)
					{
						data.append(key, value);
					});
					processData = false;
					contentType = false;
				break;
			}
			$.ajax({
				url: el.config.baseUri+"table/uploadFiles/"+tableName+"/"+fieldName,
				type: 'POST',
				data: data,
				cache: false,
				dataType: 'json',
				processData: processData,
				contentType: contentType,
				success: function(data, textStatus, jqXHR)
				{
					if(typeof data.result != "undefined" && data.result == "success")
					{
						/*находим в форме поле куда добавляли файл
						  в зависимости от типа загруженного файла выводм нужную пиктограмму и нужные поля ввода*/
						if(typeof data.files != "undefined")
						{
							var mustInsertHtml = '';
							var curIndex = $('.filedEdit[data-fieldName="'+fieldName+'"] .attach').size();
							$.each(data.files, function(key, fileObj)
							{
								/*подготовка пиктограммы*/
								var icon = '';
								if(fileObj.type == 'image')
									icon = fileObj.sizes.small;
								else
									icon = el.config.baseUri+'img/fileIcon.png';

								/*блок прикрепления*/
								var fileTPL = $('#TPLS .fileTPL').html();
								fileTPL = fileTPL.replace(/#icon#/g,icon)
									.replace(/#name#/g,fileObj.upName)
									.replace(/#index#/g,curIndex)
									.replace(/#value#/g,el.hlp.jsonStringifyWidthEscaping(fileObj))
									.replace(/#fieldName#/g,fieldName);
								mustInsertHtml = mustInsertHtml+fileTPL;

								curIndex++;
							});
							$('.filedEdit[data-fieldName="'+fieldName+'"] .attaches .attachAdd').before(mustInsertHtml);
							el.popup.hide();
						}
						else
							el.message.error('что-то пошло не так 1');
					}
					else
						el.message.error(data.msg);
				},
				errorThrown: function(jqXHR, textStatus, errorThrown)
				{
					el.message.error('что-то пошло не так 3');
				}
			});
		},

		/*Удаляет прикрепленный файл,
		  и переиндексирует индексы файлов, для целостности*/
		removeFileAttach : function(instance)
		{
			$attachesWrap = $(instance).parents('.attaches');
			$(instance).parents('.attach').fadeOut(200,function()
			{
				$(this).remove();
			});
			// переиндексация
			$attachesWrap.find('.attach').each(function()
			{
				var index = $(this).index();
				$(this).find('input').each(function()
				{
					$(this).attr('name',$(this).attr('name').replace(/\d/, index-1 ));
				});
			});
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
			var nodeVal = parseInt($('#nodeAddForm input[name="node"]').val());
			if(nodeVal > 0)
			{
				var nodeTPL = $('#TPLS .nodeTPL').html();
					nodeTPL = nodeTPL.replace(/#value#/g,nodeVal)
						.replace(/#fieldName#/g,fieldName);
				$('.filedEdit[data-fieldName="'+fieldName+'"] .nodes .attachAdd').before(nodeTPL);
				el.popup.hide();
			}
			else
				el.message.error('Введите число');
		},

		/*Удаляет связь с другим элементом*/
		removeNode : function(instance)
		{
			$(instance).parents('.node').fadeOut(200,function()
			{
				$(this).remove();
			});
		}
	},

	/* работа с сообщениями */
	message : 
	{
		error : function(msg)
		{
			$('.alert .msg').html(msg);
			$('.alert').removeClass('show success').addClass('show error');
			this.closeTimeOut();
		},

		success : function(msg)
		{
			$('.alert .msg').html(msg);
			$('.alert').removeClass('show error').addClass('show success');
			this.closeTimeOut();
		},

		close : function()
		{
			$('.alert').removeClass('show');
		},

		closeTimeOut : function()
		{
			setTimeout(function(){
				el.message.close();
			},5000);
		}
	},

	/*работа с модальным окном*/
	popup : 
	{
		init : function()
		{
			// закрытия по esc
			$(document).keyup(function(e)
			{
				if (e.keyCode == 27)
				{
					el.popup.hide();
				}
			});
		},

		show : function(cont)
		{
			$('#popupWrap .popupCont').html(cont);
			$('#popupWrap .popupCont').addClass('show');
			$('body').css('overflow','hidden');
		},

		hide : function()
		{
			$('#popupWrap .popupCont').removeClass('show');
			$('body').css('overflow','auto');
		},

		showTab:function(instance)
		{
			var needTabNum = $(instance).data('tab');
			$('#popupWrap .popupCont .tabsLine .tab').removeClass('active');
			$(instance).addClass('active');
			$('#popupWrap .popupCont .tabsCont .tabCont').removeClass('active');
			$('#popupWrap .popupCont .tabsCont .tabCont[data-tab="'+needTabNum+'"]').addClass('active');
		}
	},

	/*HELPERS*/
	hlp : 
	{
		/*преобразует JSON в строку*/
		jsonStringifyWidthEscaping : function(inJson)
		{
			inJson = JSON.stringify(inJson);
			inJson = inJson.replace(/\\n/g, "\\n")
				.replace(/\\'/g, "\\'")
				.replace(/\\"/g, '\\"')
				.replace(/\\&/g, "\\&")
				.replace(/\\r/g, "\\r")
				.replace(/\\t/g, "\\t")
				.replace(/\\b/g, "\\b")
				.replace(/"/g, '&quot;')
				.replace(/\\f/g, "\\f");
			return inJson;
		}
	}
}
// end init.js