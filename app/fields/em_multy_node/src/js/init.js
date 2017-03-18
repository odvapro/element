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
			});
		}
	},
	field:
	{
		addNodeLine:function(instance)
		{
			var tplClass = $(instance).parents('.filedEdit').data('addfieldtpl');
			var addTpl = $('.'+tplClass+' tbody').html();
			$(instance).parents('tr').before(addTpl);
			this._reinitFields($(instance).parents('table.em_multy_node'));
		},
		removeNodeLine:function(instance)
		{
			var self = this;
			var table = $(instance).parents('table.em_multy_node');
			$(instance).parents('tr').fadeOut(function()
			{
				$(this).remove();
				self._reinitFields(table);
			});
		},
		// instance - td
		// data-type = тип поля [input/select/textarea]
		edit:function(instance)
		{
			event.preventDefault();
			$this = $(instance);
			var offsetTd = $this.offset();
			var offsetLine = $this.parents('.filedEdit').offset();
			var $editBox = $this.parents('.line').find('.fieldEditorBlock');
			// показыввем только нужный тип
				var editType = $this.data('type');
			// устанаввливаем имя поля для редактирования
				var fieldName = $this.find('input').attr('name'); 
				var fieldVal = $this.find('input').val();
				var colName = $this.find('input').data('colname');
				$editBox.data('ename',fieldName);
			// установка текущего значения в нужнео поле
				var $needField = $editBox.find('.editinp').hide().end().find('.'+editType);
				if(typeof $needField.data('colname') != 'undefined')
					$needField = $needField.filter('[data-colname="'+colName+'"]');
				$needField.show().val(fieldVal).trigger('focus');
			$editBox.css(
        	{
        		top:offsetTd.top - offsetLine.top,
        		left:offsetTd.left - offsetLine.left-1,
        		width:$(instance).width()+20,
        	}).show();
		},
		hideEditing:function(instance)
		{
			$(instance).parents('.fieldEditorBlock').hide();
		},
		// сохраяняет значение поля
		save:function(instance)
		{
			var fieldName = $(instance).parents('.fieldEditorBlock').data('ename');
			var $inp = $(instance).parents('.filedEdit').find('input[name="'+fieldName+'"]');
			$inp.val($(instance).val());
			if(!$(instance).hasClass('selectinp'))
				$inp.siblings('span.value').html($(instance).val());
			else
			{
				var htmlVal = $(instance).find('option:selected').html();
				$inp.siblings('span.value').html(htmlVal);
			}
		},
		// пересчитывает номера полей
		// #number#
		_reinitFields:function(instance)
		{
			// instance - table.em_multy_node
			$(instance).find('tr').each(function()
			{
				var trIndex = $(this).index();
				$(this).find('input').each(function()
				{
					var nameMask = $(this).data('namemask');
					if(typeof nameMask != 'undefined')
					{
						nameMask = nameMask.replace('#number#',trIndex);
						$(this).attr('name',nameMask);
					}
				})
			});
		}
	}
}