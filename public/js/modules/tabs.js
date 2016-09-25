/*работа вкладок*/
el.tabs = 
{
	show : function(instance)
	{
		$(instance).siblings('li').removeClass('act');
		$(instance).addClass('act');
		var curTabIndex = $(instance).index();
		$('.tabCont').removeClass('act').eq(curTabIndex).addClass('act');
	},
	// упарвление подвкладками
	sub:
	{
		show:function(instance)
		{
			$(instance).siblings('.tab').removeClass('act');
			$(instance).addClass('act');
			var tabIndex = $(instance).data('tab');
			$(instance).parents('.tabsLine').siblings('.subTabsCont').find('.subTabCont').removeClass('act');
			$(instance).parents('.tabsLine').siblings('.subTabsCont').find('.subTabCont[data-tab="'+tabIndex+'"]').addClass('act');
		}
	}
}