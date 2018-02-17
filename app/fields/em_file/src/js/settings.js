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
			el.message.success('ok');
		});
		return false;
	},

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