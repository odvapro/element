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
		this.table.init();
		$.ajaxSetup({
			beforeSend:function()
			{
				el.loader.show();
			},
			complete:function()
			{
				el.loader.hide();
			}
		});
	},

	loader :
	{
		show:function()
		{
			$('#contentBlock').addClass('loading');
		},
		hide:function()
		{
			$('#contentBlock').removeClass('loading');
		}
	}
}
// end init.js