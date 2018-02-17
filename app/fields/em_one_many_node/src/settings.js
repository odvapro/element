el.settings.oneManyNode =
{
	changeTable:function(instance)
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
		$('select[name="set[nodeName]"]').html(resOptions);
	}
}