const languages =
{
	state:
	{
		list:
		[
			{
				short: 'en',
				long: 'English'
			},
			{
				short: 'ru',
				long: 'Русский'
			}
		],
		currentLang: '',
	},
	mutations:
	{
		setLanguage(state, newLang)
		{
			state.currentLang = newLang;
			Vue.i18n.set(newLang.short);
		}
	}
};

export default languages;