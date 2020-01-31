import axios from 'axios';
import {message} from '../plugins/message.js';
import qs from 'qs';
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
			Vue.i18n.set(state.currentLang.short);
		}
	},
	actions:
	{
		async setLanguage(state, {newLang, id})
		{
			if (id && newLang)
			{
				let data = qs.stringify({id: id, language: newLang});
				let result = await axios.post('/users/setLanguage', data);

				if (!result.data.success)
					return message.error(result.data.message);
			}
			else if (!id && !newLang)
				return message.error('Something wrong');
		}
	}
};

export default languages;