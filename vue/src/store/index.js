import Vue from 'vue'
import Vuex from 'vuex'
import tables from './tables.js'
import users from './users.js'
import settings from './settings.js'
import axios from 'axios'

var qs = require('qs');
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

Vue.use(Vuex)
export default new Vuex.Store({
	modules:
	{
		tables: tables,
		settings: settings,
		users: users
	},
	state:
	{
		drugPosition: 400,
		isAuth: true,
		isIntallDb: true
	},
	mutations:
	{
		/**
		 * Установить статус конфигурации бд
		 */
		setInstallDb(state, status)
		{
			state.isIntallDb = status;
		},

		/**
		 * Установить статус авторизации
		 */
		setAuth(state, status)
		{
			state.isAuth = status;
		},

		/**
		 * Установить ширину сайдбара
		 */
		drugPosition(state, position)
		{
			state.drugPosition = position;
		}
	}
})
