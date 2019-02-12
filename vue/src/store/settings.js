import axios from 'axios';

var qs = require('qs');
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const settings =
{
	state:
	{
		popupActive: false
	},
	mutations:
	{
		/**
		 * Открыть/закрыть попап
		 */
		setActivePopup(state, status)
		{
			state.popupActive = status
		}
	},
	actions:
	{

	}
}
export default settings;