import axios from 'axios';
import {message} from '../plugins/message.js';
import qs from 'qs';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const settings =
{
	state:
	{
		users:[],
	},
	mutations:
	{
		/**
		 * Set Users
		 */
		setUsers(state,users)
		{
			state.users = users;
		},

		/**
		 * Add user
		 * @var user - object
		 */
		addUser(state,user)
		{
			state.users.push(user);
		},

		/**
		 * Remove User
		 */
		removeUser(state,user)
		{
			for(let cUserIndex in state.users)
			{
				if(state.users[cUserIndex].id == user.id)
				{
					state.users.splice(cUserIndex,1);
					break;
				}
			}
		},
	},
	actions:
	{
		/**
		 * Gets users from database
		 */
		async getUsers(store)
		{
			var result = await axios.get('/users/getUsers/');
			for(let user of result.data.users)
			{
				user.isShow = false;
				user.newPassword = '';
			}
			store.commit('setUsers',result.data.users);
		},

		async removeUser(store,user)
		{
			let data = qs.stringify({id:user.id});

			let result = await axios.post('/users/deleteUser/',data);
			if(result.data.success)
				return store.commit('removeUser',user);
			else
				return message.error(result.data.message);
		}
	}
}
export default settings;