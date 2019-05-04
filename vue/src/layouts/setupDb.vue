<template>
	<div class="setup-db-wrapper">
		<div class="setup-db-form">
			<div class="setup-db-head">
				<img src="images/logo.svg" alt="">
			</div>
			<div class="setup-db-content">
				<label class="setup-db-label">
					Host
					<input
						type="text"
						class="el-inp setup-db__input"
						v-model="config.host.value"
						placeholder="Enter hostname"
					/>
				</label>
				<label class="setup-db-label">
					Database username
					<input
						type="text"
						class="el-inp setup-db__input"
						v-model="config.username.value"
						placeholder="Enter username"
					/>
				</label>
				<label class="setup-db-label">
					Database Password
					<input
						type="password"
						class="el-inp setup-db__input"
						v-model="config.password.value"
						placeholder="Enter password"
					/>
				</label>
				<label class="setup-db-label">
					Database name
					<input
						type="text"
						class="el-inp setup-db__input"
						v-model="config.dbname.value"
						placeholder="Enter database name"
					/>
				</label>
				<label class="setup-db-label">
					BaseUrl
					<input
						type="text"
						class="el-inp setup-db__input"
						v-model="config.baseUrl.value"
						placeholder="Enter BaseUrl"
					/>
				</label>
				{{errors}}
				<div class="setup-db-button-wrapper">
					<div class="el-gbtn" @click="submitConfig(true)">Check</div>
					<div class="el-btn" @click="submitConfig(false)">Install</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default
{
	name: 'SetupDb',
	/**
	 * Глобальные переменные страницы
	 */
	data()
	{
		return {
			errors: '',
			config:
			{
				host: {value: 'localhost', error: ''},
				dbname: {value: '', error: ''},
				username: {value: '', error: ''},
				adapter: {value: 'Mysql', error: ''},
				password: {value: 'Hi8R28XY|P', error: ''},
				baseUrl: {value: '/', error: ''},
			},
			isCheck: false
		}
	},
	methods:
	{
		/**
		 * отправить конфигурацию БД на сервер
		 */
		async submitConfig(isCheck)
		{
			this.errors = '';

			var oldPass = this.config.password,
				data = new FormData();

			this.config.password = this.config.password.value.replace(/[\`\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\{\}\[\]\\\|]/g, '\\' + '$&');
			this.config.isCheck = isCheck;

			for (var item in this.config)
				data.append(item, this.config[item]);

			let result = await this.$axios.post('/install.php', data);

			this.config.password = oldPass;

			if (!result.data.success)
			{
				this.errors = result.data.message;
				return false;
			}

			if (result.data.success && isCheck)
			{
				this.errors = result.data.message;
				return false;
			}

			this.$router.push('/');
		}
	}
}
</script>
<style lang="scss">
	.setup-db-head {text-align: center; margin-bottom: 26px; }
	.setup-db-content
	{
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		width: 434px;
		padding: 27px 50px;
		background-color: #fff;
	}
	.setup-db-wrapper
	{
		display: flex;
		align-items: center;
		justify-content: center;
		height: 100%;
	}
	.setup-db-label
	{
		font-size: 12px;
		color: #191C21;
		display: block;
		margin-bottom:15px;
	}
	.setup-db__input {width:100%; margin-top: 7px; }
	.setup-db-button-wrapper
	{
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 8px 0;
		.el-gbtn{margin-right: 15px;}
	}
</style>