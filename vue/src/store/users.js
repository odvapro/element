import axios from 'axios';
import qs from 'qs';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const users =
{
	state:
	{
		authUser: {}
	},
	mutations:
	{
		/**
		 * Запомнить авторизованного пользователя
		 */
		setAuthUser(state, user)
		{
			state.authUser = user;
			if (state.authUser && typeof state.authUser.language === 'string')
				state.authUser.language = JSON.parse(state.authUser.language);
		}
	}
}
export default users;