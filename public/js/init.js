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

	/*работа в сайбаре*/
	sidebar:
	{
		fold:function()
		{
			var sFolded = 0;
			if($('body').hasClass('folded'))
			{
				$('body').removeClass('folded');
			}
			else
			{
				$('body').addClass('folded');
				sFolded = 1;
			}
			el.hlp.cookies.set('sFolded', sFolded, 10);
		}
	},

	/*работа вкладок*/
	tabs : 
	{
		show : function(instance)
		{
			$(instance).siblings('li').removeClass('act');
			$(instance).addClass('act');
			var curTabIndex = $(instance).index();
			$('.tabCont').removeClass('act').eq(curTabIndex).addClass('act');
		},
		// упарвление подвкладками
		sub:
		{
			show:function(instance)
			{
				$(instance).siblings('.tab').removeClass('act');
				$(instance).addClass('act');
				var tabIndex = $(instance).data('tab');
				$(instance).parents('.tabsLine').siblings('.subTabsCont').find('.subTabCont').removeClass('act');
				$(instance).parents('.tabsLine').siblings('.subTabsCont').find('.subTabCont[data-tab="'+tabIndex+'"]').addClass('act');
			}
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
		},
		'cookies' : 
		{
			'_init' : function()
			{
				this._cookies = {};
				var ca = document.cookie.split(';');
				var re = /^[\s]*([^\s]+?)$/i;
				for (var i = 0, l = ca.length; i < l; i++)
				{
					var c = ca[i].split('=');
					if (c.length == 2)
					{
						this._cookies[c[0].match(re)[1]] = unescape(c[1].match(re) ? c[1].match(re)[1] : '');
					}
				}
			},
			'get' : function(name)
			{
				this._init();
				return this._cookies[name];
			},
			'set' :  function(name, value, days, secure)
			{
				var expires = '';
				if(days)
				{
					var date = new Date();
					date.setTime(date.getTime()+(days*24*60*60*1000));
					expires = '; expires='+date.toGMTString();
				}
				// var domain = el.config.baseUri;
				var domain = false;
				document.cookie = name + '='+escape(value) + expires + '; path=/' + (domain ? '; domain=.' + domain : '') + ((secure && H.locProtocol == 'https:') ? '; secure' : '');
			}
		},
		/* подключение скрипта один раз */
		requiredfiles:[],
		requireonce:function(url)
		{
			if(this.requiredfiles.indexOf(url) === -1)
			{
				$.getScript(url);
				this.requiredfiles.push(url);
			}
		}
	}
}
// end init.js