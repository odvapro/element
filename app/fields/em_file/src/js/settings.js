el.settings.fieldFile = 
{
	/**
	 * Отправляет запрос на регенерацтю картинок данного поля
	 * @return void
	 */
	reGenerate:function(instance)
	{

		var tableName = $(instance).parents('form').find('input[name="tableName"]').val();
		var fieldName = $(instance).parents('form').find('input[name="fieldName"]').val();
		$.ajax({
			url: el.config.baseUri+"fld/em_file/index/reGenerate",
			type:'POST',
			dataType:'json',
			data: { tableName:tableName, fieldName:fieldName },
		}).done(function(e)
		{
			console.log(e);
			// el.message.success('ok');
		});
		return false;
	}
}