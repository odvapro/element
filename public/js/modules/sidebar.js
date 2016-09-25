/*работа в сайбаре*/
el.sidebar = 
{
	fold:function()
	{
		var sFolded = 0;
		if($('body').hasClass('folded'))
		{
			$('body').removeClass('folded');
		}
		else
		{
			$('body').addClass('folded');
			sFolded = 1;
		}
		el.hlp.cookies.set('sFolded', sFolded, 10);
	}
}