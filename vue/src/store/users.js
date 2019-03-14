import axios from 'axios';

var qs = require('qs');
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
		}
	},
	actions:
	{

	}
}
export default users;