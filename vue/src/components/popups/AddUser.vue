<template>
	<div class="settings-popup-row-params">
		<div class="popup__name">{{$t('add_user')}}</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('name')}}
				<small class="popup__field-error" v-if="errors.name">{{ errors.name }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" :placeholder="$t('enter_something') + $t('name')" v-model="name">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('login')}}
				<small class="popup__field-error" v-if="errors.login">{{ errors.login }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" :placeholder="$t('enter_something') + $t('login')" v-model="login">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Email
				<small class="popup__field-error" v-if="errors.email">{{ errors.email }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" :placeholder="$t('enter_something') + 'Email'" v-model="email">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('password')}}
				<small class="popup__field-error" v-if="errors.password">{{ errors.password }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" :placeholder="$t('enter_something') + $t('password')" v-model="password">
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">{{$t('cancel')}}</button>
			<button @click="addUser()" class="el-btn">{{$t('add')}}</button>
		</div>
	</div>
</template>
<script>
	import qs from 'qs';
	export default
	{
		data()
		{
			return {
				name:'',
				login:'',
				email:'',
				password:'',
				errors:{
					name:false,
					login:false,
					email:false,
					password:false,
				}
			}
		},
		methods:
		{
			/**
			 * Validate form
			 */
			validate()
			{
				let needInpus = ['name','login','email','password'];
				let isValid = true;
				for(let nedInp of needInpus)
				{
					if(this[nedInp] == '')
					{
						this.errors[nedInp] = this.$t('addUser.this_field_required');
						isValid = false;
					}
					else
						this.errors[nedInp] = false;
				}
				return isValid;
			},
			/**
			 * Add user
			 */
			async addUser()
			{
				if(!this.validate())
					return false;

				let formData = {
					name     :this.name,
					login    :this.login,
					email    :this.email,
					password :this.password,
					language :this.$store.state.users.authUser.language
				}
				let data = qs.stringify(formData);
				formData.isShow = false;
				formData.newPassword = '';

				let result = await this.$axios.post('/users/addUser/',data);
				if(result.data.success)
				{
					formData.id = result.data.id;
					this.$store.commit('addUser',formData);
					this.cancel();
				}
				else
					return this.ElMessage.error(result.data.message);

			},

			/**
			 * Close popup
			 */
			cancel()
			{
				this.$emit('cancel');
			}
		}
	}
</script>
