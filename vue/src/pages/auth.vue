<template>
	<div class="wrapper">
		<div class="margin-bottom">
			<input placeholder="Имя пользователя" v-model="user.username">
		</div>
		<div class="margin-bottom">
			<input placeholder="Пароль" v-model="user.password">
		</div>
		<button class="primary" @click.stop.prevent="submitConfig">Отправить</button>
		<div style="color: red" v-if="errors">{{errors}}</div>
	</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				user:
				{
					username: '',
					password: ''
				},
				errors: ''
			}
		},
	}
</script>