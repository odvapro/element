<template>
	<div class="setup-db-wrapper">
		<div class="setup-db-form">
			<div class="setup-db-head">
				<img src="/images/logo.svg" alt="">
			</div>
			<div class="setup-db-content">
				<label class="setup-db-label">
					Host
					<div class="setup-db-input-wrapper">
						<input type="text" v-model="config.host" placeholder="Enter hostname">
					</div>
				</label>
				<label class="setup-db-label">
					Database username
					<div class="setup-db-input-wrapper">
						<input type="text" v-model="config.username" placeholder="Enter username">
					</div>
				</label>
				<label class="setup-db-label">
					Database Password
					<div class="setup-db-input-wrapper">
						<input type="password" v-model="config.password" placeholder="Enter password">
					</div>
				</label>
				<label class="setup-db-label">
					Database name
					<div class="setup-db-input-wrapper">
						<input type="text" v-model="config.dbname" placeholder="Enter database name">
					</div>
				</label>
				<label class="setup-db-label">
					BaseUrl
					<div class="setup-db-input-wrapper">
						<input type="text" v-model="config.baseUrl" placeholder="Enter BaseUrl">
					</div>
				</label>
				<label class="setup-db-label">
					Select Database
					<div class="setup-db-input-wrapper">
						<select v-model="config.adapter">
							<option value="Postgres">Postgres</option>
							<option value="Mysql">MySql</option>
						</select>
					</div>
				</label>
				<div class="setup-db-button-wrapper">
					<div class="setup-db-button-light">Check</div>
					<div class="setup-db-button-dark" @click="submitConfig">Install</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default
{
	name: 'SetupDb',
	data() {
		return {
			errors: '',
			config:
			{
				host: 'localhost',
				dbname: '',
				username: '',
				adapter: 'Mysql',
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
<style lang="scss">
	.setup-db-head
	{
		text-align: center;
		margin-bottom: 26px;
	}
	.setup-db-label
	{
		font-size: 12px;
		color: #000;
	}
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
	.setup-db-input-wrapper
	{
		background: #FFFFFF;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		margin: 7px 0 14px;
		height: 30px;
		select
		{
			border: none;
			background: none;
			width: 100%;
			height: 100%;
		}
		input
		{
			border: none;
			font-size: 10px;
			padding: 0 11px;
			width: 100%;
			height: 100%;
			box-sizing: border-box;
		}
	}
	.setup-db-button-wrapper
	{
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 8px 0;
	}
	.setup-db-button-light
	{
		color: rgba(103, 115, 135, 0.7);
		font-size: 12px;
		padding: 7px 11px;
		background: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		margin-right: 15px;
		cursor: pointer;
	}
	.setup-db-button-dark
	{
		padding: 7px 11px;
		background: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		font-size: 12px;
		cursor: pointer;
		color: #fff;
	}
</style>