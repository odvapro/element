/*работа с модальным окном*/
el.popup = 
{
	init : function()
	{
		// закрытия по esc
		$(document).keyup(function(e)
		{
			if (e.keyCode == 27)
			{
				el.popup.hide();
			}
		});
	},

	show : function(cont)
	{
		$('#popupWrap .popupCont').html(cont);
		$('#popupWrap').addClass('show');
		$('body').css('overflow','hidden');
	},

	hide : function()
	{
		$('#popupWrap').removeClass('show');
		$('body').css('overflow','auto');
	},

	showTab:function(instance)
	{
		var needTabNum = $(instance).data('tab');
		$('#popupWrap .popupCont .tabsLine .tab').removeClass('active');
		$(instance).addClass('active');
		$('#popupWrap .popupCont .tabsCont .tabCont').removeClass('active');
		$('#popupWrap .popupCont .tabsCont .tabCont[data-tab="'+needTabNum+'"]').addClass('active');
	}
}