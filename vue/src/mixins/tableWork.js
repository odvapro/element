export default
{
	methods:
	{
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