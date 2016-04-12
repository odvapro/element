if($('.codeeditorcodemirror').size())
{
	$('.codeeditorcodemirror').each(function()
	{
		var textareaEl = $(this)[0];
		var editor = CodeMirror.fromTextArea(textareaEl,{
			lineNumbers: true,
			mode: "text/html",
			styleActiveLine: true,
			theme:'neo',
			matchBrackets: true
		});
		editor.on("change", function()
		{
		  editor.save();
		});
	});
}
