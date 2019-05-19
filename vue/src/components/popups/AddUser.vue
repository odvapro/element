<template>
	<div>
		<div class="popup__name">Add User</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Name
				<small class="popup__field-error" v-if="errors.name">{{ errors.name }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter name" v-model="name">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Login
				<small class="popup__field-error" v-if="errors.login">{{ errors.login }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter login" v-model="login">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Email
				<small class="popup__field-error" v-if="errors.email">{{ errors.email }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter email" v-model="email">
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Password
				<small class="popup__field-error" v-if="errors.password">{{ errors.password }}</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter password" v-model="password">
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="$store.commit('closePopup');" class="el-gbtn">Cancel</button>
			<button @click="addUser()" class="el-btn">Add</button>
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
						this.errors[nedInp] = 'This field required';
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
					password :this.password
				}
				let data = qs.stringify(formData);
				formData.isShow = false;
				formData.newPassword = '';

				let result = await this.$axios.post('/users/addUser/',data);
				if(result.data.success)
				{
					formData.id = result.data.id;
					this.$store.commit('addUser',formData);
					return this.$store.commit('closePopup');
				}
				else
					return this.ElMessage.error(result.data.message);

			}
		}
	}
</script>
<style lang="scss"></style>