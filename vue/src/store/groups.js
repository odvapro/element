import axios from 'axios';
import qs from 'qs';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const groups =
{
	state:
	{
		groups: []
	},
	mutations:
	{
		setGroups(state, groups)
		{
			state.groups = groups;
		}
	},
	actions:
	{
		async getGroups(store)
		{
			var result = await axios.get('/groups/getGroups/');
			store.commit('setGroups', result.data.groups);
		},
	}
}
export default groups;