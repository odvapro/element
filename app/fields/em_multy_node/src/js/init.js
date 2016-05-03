el.emMultyNode = 
{
	settings:
	{
		changeType:function(instance)
		{
			var curVal = $(instance).val();
			if(curVal == 'select_from_table')
			{
				// нужно выбрать таблицу для связи
				var trIndex = $(instance).parents('tr').index();
				var allTablesSelect = $('#multyTableTPLS .allTablesSelect').html();
				$(instance).parents('td').siblings('.tableCol').html(allTablesSelect);
				this._reinitCols();
			}
			else
			{
				$(instance).parents('td').siblings('.tableCol').html('-');
			}
		},
		addCol:function(instance)
		{
			var colTpl =  $('#multyTableTPLS .emptyCol').html();
			$(instance).parents('tr').before(colTpl);
			this._reinitCols();
		},
		removeCol:function(instance)
		{
			var self = this;
			$(instance).parents('tr').fadeOut(function()
			{
				$(this).remove();
				self._reinitCols();
			})
		},
		// пересчитывает номера полей
		// #number#
		_reinitCols:function()
		{
			$('.multy_node_table tr').each(function()
			{
				var trIndex = $(this).index();
				$(this).find('input,select').each(function()
				{
					var nameMask = $(this).data('namemask');
					if(typeof nameMask != 'undefined')
					{
						nameMask = nameMask.replace('#number#',trIndex);
						$(this).attr('name',nameMask);
					}
				})
			})
		}
	}
}