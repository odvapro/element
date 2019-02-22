import axios from 'axios';

var qs = require('qs');
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const settings =
{
	state:
	{
		popupActive: false,
		popupParams: {}
	},
	mutations:
	{
		/**
		 * Открыть/закрыть попап
		 */
		setActivePopup(state, status)
		{
			state.popupActive = status
		},
		/**
		 * Передать параметры для попапа
		 */
		setPopupParams(state, params)
		{
			state.popupParams = params;
		}
	},
	actions:
	{

	}
}
export default settings;