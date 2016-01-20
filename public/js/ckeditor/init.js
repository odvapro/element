$(document).ready(function()
{
	var index = 0;
	$('textarea.visual').each( function(index, element)
	{
		$(element).attr('id','textarea'+index);
		CKEDITOR.replace('textarea'+index);
	});
	$.each(CKEDITOR.instances,function(e,a)
	{
		a.on('change', function()
        {
        	a.updateElement();
        });
	});
});