import axios from 'axios';
import {message} from '../plugins/message.js';
import qs from 'qs';
const languages =
{
	state:
	{
		list:
		{
			en: 'English',
			ru: 'Русский'
		},
		currentLang: '',
	},
	getters:
	{
		lang:     state => state.currentLang,
		langStr:  state => state.list[state.currentLang],
		langList: state => state.list,
	},
	mutations:
	{
		setLanguage(state, newLang)
		{
			if (state.list[newLang])
				state.currentLang = newLang;
			else
				state.currentLang = 'en';

			Vue.i18n.set(state.currentLang);
		}
	},
	actions:
	{
		async setLanguage(state, {language, id})
		{
			if (id && language)
			{
				let result = await axios.post('/users/setLanguage', qs.stringify({id, language}));

				if (!result.data.success)
					return message.error(result.data.message);
			}
			else
				return message.error('Something wrong');
		}
	}
};

export default languages;