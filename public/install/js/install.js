el = 
{
	install:function(instance)
	{
		$.ajax({
			url      : '',
			type     :'POST',
			dataType :'json',
			data     : {ajax:'install',formData:$(instance).serializeArray()}
		}).done(function(e)
		{
			if(typeof e.success != 'undefined')
			{
				window.location.reload();
			}
		});
		return false;
	}
}