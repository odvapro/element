export default
{
	methods:
	{
		/**
		 * Обновить данные таблицы
		 */
		setTableValue(primaryKey, primaryVal, tableContent, field, value)
		{
			for (let tableValue of tableContent)
				if (tableValue[primaryKey].value === primaryVal)
					tableValue[field].value = value;
		},
		/**
		 * Достать таблицу по коду
		 */
		getTableByCode(code, tables)
		{
			for (let table of tables)
				if (table.code == code)
					return table;
		},
		/**
		 * Достать активный/дефолтный tview
		 */
		getDefaultTview(table)
		{
			for (var tview of table.tviews)
				if (tview.default === '1')
					return tview;
		},
		/**
		 * Сохранить настройки tview
		 */
		async setTviewSetting(table, settingName, settingValue)
		{
			let tview         = this.getDefaultTview(table),
				tviewSettings = tview.settings,
				qs            = require('qs');

			if (typeof tviewSettings[settingName] == 'undefined')
			{
				tviewSettings = {};
				this.$set(tviewSettings, settingName, settingValue);
			}
			else
			{
				for (let settingItem in settingValue)
				{
					tviewSettings[settingName][settingItem] = settingValue[settingItem];
				}
			}

			let data = qs.stringify({
				tviewId : tview.id,
				params  : tviewSettings
			});

			let result = await this.$axios({
				method : 'POST',
				data   : data,
				url    : '/api/el/setTviewSettings/'
			})

			if (!result.data.success)
				return false;

			tview.settings = tviewSettings;
		}
	}
}