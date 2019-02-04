import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
export default new Vuex.Store({
	state:
	{
		drugPosition: 400,
		openPopupName:false,
		popupCoords: '',
		openPopup: false,
		isAuth: false,
		isIntallDb: false
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
		 * Открытие попапов
		 */
		openPopup(state, popup)
		{
			state.openPopup = true;
			state.openPopupName = popup.name;
			state.popupCoords = popup.coords;
		},
		/**
		 * Закрытие всех попапов
		 */
		closePopup(state)
		{
			state.openPopup = false;
		}
	},
	actions:
	{

	}
})
