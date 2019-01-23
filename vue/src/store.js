import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
export default new Vuex.Store({
	state:
	{
		user: {name:''},
		auth:false
	},
	mutations:
	{
		/**
		 * Установка авторизованности
		 */
		setAuth(state,auth)
		{
			state.auth = auth;
		},
		/**
		 * Установка пользователя
		 */
		setUser(state,user)
		{
			state.user = user;
		}
	},
	actions:
	{
	}
})
