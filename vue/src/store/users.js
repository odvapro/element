import axios from 'axios';
import qs from 'qs';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const users =
{
	state:
	{
		authUser: {}
	},
	getters:
	{
		isAuthUser: state=>state.authUser && state.authUser.id,
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
	}
}
export default users;