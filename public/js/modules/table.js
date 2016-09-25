el.table = 
{
	setsort:function(instance)
	{
		var fieldName =  $(instance).parents('th').data('code');
		var sortDir = 'desc';
		if($(instance).hasClass('desc'))
			sortDir = 'asc';
		window.location = '?sort='+fieldName+'&sortdir='+sortDir;
	}
}