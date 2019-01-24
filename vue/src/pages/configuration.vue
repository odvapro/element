<template>
	<div class="wrapper">
		<div class="logoLine">
			<span class="logo">Данные БД</span>
		</div>
		<div class="margin-bottom">
			<input placeholder="Хост" v-model="config.host">
		</div>
		<div class="margin-bottom">
			<input placeholder="Название БД" v-model="config.dbname">
		</div>
		<div class="margin-bottom">
			<input placeholder="Имя пользователя" v-model="config.username">
		</div>
		<div class="margin-bottom">
			<input placeholder="Пароль" v-model="config.password">
		</div>
		<div class="margin-bottom">
			<input placeholder="Путь к админке" v-model="config.baseUrl">
		</div>
		<button class="primary" @click.stop.prevent="submitConfig">Отправить</button>
		<div style="color: red" v-if="errors">{{errors}}</div>
	</div>
</template>
<script>
	export default
	{
		data() {
			return {
				errors: '',
				config:
				{
					host: 'localhost',
					dbname: '',
					username: '',
					password: 'Hi8R28XY|P',
					baseUrl: '/',
				}
			}
		},
		methods:
		{
			/**
			 * отправить конфигурацию БД на сервер
			 */
			async submitConfig()
			{
				this.errors = '';

				var oldPass = this.config.password,
					data = new FormData();

				this.config.password = this.config.password.replace(/[\`\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\{\}\[\]\\\|]/g, '\\' + '$&');

				for (var item in this.config)
					data.append(item, this.config[item]);

				let result = await this.$axios.post('/install.php', data);

				this.config.password = oldPass;

				if (!result.data.success)
				{
					this.errors = result.data.message;
					return false;
				}

				this.$router.push('/');
			}
		}
	}
</script>
<style>
	.margin-bottom
	{
		margin-bottom: 10px;
	}
	body, html, #app
	{
		height: 100%;
	}
	.wrapper
	{
		height: 100%;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
</style>