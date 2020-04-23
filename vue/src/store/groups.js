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
		},

		addGroup(state, group)
		{
			state.groups.push(group);
		},

		/**
		 * Removes group from grpups
		 */
		removeGroup(state, groupId)
		{
			for(const [groupIndex, group] of state.groups.entries())
			{
				if(group.id == groupId)
					state.groups.splice(groupIndex,1);
			}
		},

		/**
		 * Sets group name
		 * params = {name,id}
		 */
		setGroupName(state, params)
		{
			for(const [groupIndex, group] of state.groups.entries())
			{
				if(group.id == params.id)
				{
					state.groups[groupIndex].name = params.name;
					break;
				}
			}
		}
	},
	actions:
	{
		async getGroups(store)
		{
			let result = await axios.get('/groups/get/');
			store.commit('setGroups', result.data.groups);
		},

		async addGroup(store)
		{
			let result = await axios.get('/groups/add/');
			if(!result.data.success)
				return false;

			store.commit('addGroup', result.data.group);
		},

		async setGroupName(store,params)
		{
			let data = qs.stringify({id:params.id, name:params.name});
			let result = await axios({
				method : 'POST',
				url    : '/groups/update/',
				data   : data
			});
			if(!result.data.success)
				return false;

			store.commit('setGroupName', params);
		},

		async removeGroup(store, groupId)
		{
			let data = qs.stringify({id:groupId});
			let result = await axios({
				method : 'POST',
				url    : '/groups/remove/',
				data   : data
			});
			if(!result.data.success)
				return false;

			store.commit('removeGroup', groupId);
		}
	}
}
export default groups;