<template>
	<div class="wrapper">
		<div class="margin-bottom">
			<input placeholder="Имя пользователя" v-model="user.login">
		</div>
		<div class="margin-bottom">
			<input placeholder="Пароль" v-model="user.password">
		</div>
		<button class="primary" @click.stop.prevent="authUser">Отправить</button>
		<div style="color: red" v-if="errors">{{errors}}</div>
	</div>
</template>
<script>
export default
{
	name: 'Auth',
	data()
	{
		return {
			user:
			{
				login: '',
				password: ''
			},
			errors: ''
		}
	},
	methods:
	{
		/**
		 * Авторизоваться
		 */
		async authUser()
		{
			var data = new FormData();

			data.append('login', this.user.login);
			data.append('password', this.user.password);

			var result = await this.$axios.post('/api/auth/index/', data);

			if (!result.data.success)
				return false;

			this.$router.push('/');
		}
	}
}
</script>