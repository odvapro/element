import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
export default new Vuex.Store({
	state:
	{
		drugPosition: 400,
		openPopupName:false,
		popupCoords: '',
		openPopup: false
	},
	mutations:
	{
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
