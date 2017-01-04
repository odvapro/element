// подключается в списке элементов
el.emMultyNode = 
{
	// досатеат значение матрицы выводит в попап
	getMatrix:function(instance,fieldCode)
	{
		var $table = $(instance).parents('._em_table'),
			tableName = $table.data('tablename'),
			primaryKey = $table.data('primarykey'),
			id = $(instance).parents('tr').data('id');
		$.ajax({
			url      :el.config.baseUri+'fld/em_multy_node/index/getMatrix/',
			type     :'POST',
			dataType :'json',
			data     :{
				tableName  :tableName,
				primaryKey :primaryKey,
				fieldCode  :fieldCode,
				id         :id
			}
		}).done(function(e)
		{
			if(typeof e.success == 'undefined') return false;
			el.popup.show(e.table);
		});
	}
}