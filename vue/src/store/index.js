import Vue from 'vue';
import Vuex from 'vuex';
import tables from './tables.js';
import users from './users.js';
import groups from './groups.js';
import settings from './settings.js';
import languages from './languages.js';
import vuexI18n from 'vuex-i18n';
import translationsEn from '../locale/en';
import translationsRu from '../locale/ru';


Vue.use(Vuex)

const store = new Vuex.Store({
	modules:
	{
		tables    : tables,
		settings  : settings,
		users     : users,
		languages : languages,
		groups    : groups
	},
	state:
	{
		drugPosition: 400,
		isAuth: true,
		isIntallDb: true,
		showLoader: false
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
		},

		/**
		 * ShowOrHideLoader
		 */
		showLoader(state,hide)
		{
			state.showLoader = hide;
		}
	}
})

Vue.use(vuexI18n.plugin, store);
Vue.i18n.add('en', translationsEn);
Vue.i18n.add('ru', translationsRu);

export default store;