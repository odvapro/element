el.fileField = 
{
	// открывает форму добавления файла
	// отправляется запрос на страницу fld/em_file/index/getFileUploadForm
	// c именем поля и таблицы
	// рещультат открывается в попапе
	getUploadForm : function(instance,fieldName)
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
			url: el.config.baseUri+"fld/em_file/index/getFileUploadForm",
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

	// событие по срабатывющее при изменении поля типа файл
	// и сразу происодит отправка файла на сервер
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
			url: el.config.baseUri+"fld/em_file/index/uploadFiles/"+tableName+"/"+fieldName,
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: processData,
			contentType: contentType,
			success: function(data, textStatus, jqXHR)
			{
				if(typeof data.result == "undefined" || data.result != "success")
					el.message.error(data.msg);
				/*находим в форме поле куда добавляли файл
				  в зависимости от типа загруженного файла выводм нужную пиктограмму и нужные поля ввода*/
				if(typeof data.files == "undefined")
					el.message.error('что-то пошло не так 1');
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
			},
			errorThrown: function(jqXHR, textStatus, errorThrown)
			{
				el.message.error('что-то пошло не так 3');
			}
		});
	},

	// Удаляет прикрепленный файл,
	// и переиндексирует индексы файлов, для целостности
	removeFileAttach : function(instance)
	{
		$attachesWrap = $(instance).parents('.attaches');
		$(instance).parents('.attach').fadeOut(200,function()
		{
			$(this).remove();
		});
		// переиндексация
		// чтобы не возникало пустот типа  0 1 _ 3
		$attachesWrap.find('.attach').each(function()
		{
			var index = $(this).index();
			$(this).find('input').each(function()
			{
				$(this).attr('name',$(this).attr('name').replace(/\d/, index-1 ));
			});
		});
	}
}
