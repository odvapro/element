import axios from 'axios';
import qs from 'qs';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const groups =
{
	state:
	{
		groups: [],
		// варианты доступа - чтение, запись,..
		accessOptions: [],
		tokens: [],
		apiDocs: [],
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
		setAccessOptions(state, accessOptions)
		{
			state.accessOptions = accessOptions;
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
		},

		/**
		 * Add user to the group
		 * params = {user (object),groupId (int)}
		 */
		addUser(state, params)
		{
			for(const [groupIndex, group] of state.groups.entries())
			{
				if(group.id == params.groupId)
				{
					state.groups[groupIndex].members.push(params.user);
					break;
				}
			}
		},

		/**
		 * Removes user from the group
		 * params = {userId (int),groupId (int)}
		 */
		removeGroupUser(state, params)
		{
			for (let group of state.groups)
				if (group.id === params.groupId)
					group.members = group.members.filter(member=>
					{
						if(member.id !== params.userId)
							return true;
						return false;
					});
		},
		setTokens(state, tokens)
		{
			state.tokens = tokens;
		},
		addToken(state, token)
		{
			state.tokens.push(token);
		},
		removeToken(state, tokenId)
		{
			let tokenInd = state.tokens.findIndex(token=>token.id === tokenId);
			if (!~tokenInd)
				return;

			state.tokens.splice(tokenInd, 1);
		},
		changeToken(state, token)
		{
			let tokenInd = state.tokens.findIndex(stateToken=>stateToken.id === token.id);
			if (!~tokenInd)
				return;

			state.tokens[tokenInd] = token;
		},
		setApiDocs(state, apiDocs)
		{
			state.apiDocs = apiDocs;
		},
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
		// запрашивает возможные варианты доступа
		async getAccessOptions(store)
		{
			let result = await axios.get('/groups/getAccessOptions/');
			if(!result.data.success)
			{
				store.commit('setAccessOptions', []);
				return;
			}
			store.commit('setAccessOptions', result.data.options);
		},
		async setGroupAccess(store, {groupId, accessStr, tableName})
		{
			let result = await axios.post('/groups/setGroupAccess/', qs.stringify({groupId, accessStr, tableName}));

			if (!result.data.success)
				Vue.prototype.ElMessage.error(result.data.msg);
			else
				Vue.prototype.ElMessage(Vue.prototype.$t('elMessages.settings_saved'));
		},
		/**
		 * params = {user (object),groupId (int)}
		 */
		async addUser(store,params)
		{
			let data = qs.stringify({id:params.user.id, group:params.groupId});
			let result = await axios({
				method : 'POST',
				url    : '/groups/addUser/',
				data   : data
			});
			if(!result.data.success)
				return false;

			store.commit('addUser', params);
		},

		/**
		 * params = {userId (int),groupId (int)}
		 */
		async removeGroupUser(store,params)
		{
			let data = qs.stringify({id:params.userId, group:params.groupId});
			let result = await axios({
				method : 'POST',
				url    : '/groups/removeUser/',
				data   : data
			});
			if(!result.data.success)
				return false;

			store.commit('removeGroupUser', params);
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
		},

		async getTokens(store)
		{
			let result = await axios.get('/tokens/getTokens/');

			if (result.data.success)
				store.commit('setTokens', result.data.tokens);
		},

		async createToken(store, {groupId})
		{
			let result = await axios.post('/tokens/createToken/', qs.stringify({group_id: groupId}));
			if (!result.data.success)
			{
				Vue.prototype.ElMessage.error(result.data.msg);
				return false;
			}

			store.commit('addToken', result.data.token);
			return true;
		},

		async removeToken(store, {tokenId})
		{
			let result = await axios.post('/tokens/removeToken/', qs.stringify({token_id: tokenId}));

			if (!result.data.success)
			{
				Vue.prototype.ElMessage.error(result.data.msg);
				return false;
			}

			store.commit('removeToken', tokenId);
			return true;
		},
		async changeToken(store, {tokenId, groupId})
		{
			let result = await axios.post('/tokens/changeToken/', qs.stringify({token_id: tokenId, group_id: groupId}));

			if (!result.data.success)
			{
				Vue.prototype.ElMessage.error(result.data.msg);
				return false;
			}

			store.commit('changeToken', result.data.token);
			return true;
		},
		async getApiDocs(store, {table_name})
		{
			let result = await axios.post('/tokens/getApiDocs/', qs.stringify({table_name}));
			if (!result.data.success)
			{
				Vue.prototype.ElMessage.error(result.data.msg);
				return false;
			}

			store.commit('setApiDocs', result.data.docs);
			return true;
		},
	}
}
export default groups;
