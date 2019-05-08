<template>
	<div class="setup-wrapper">
		<div class="setup-form">
			<div class="setup-head">
				<img src="@/assets/images/logo.svg" alt="">
			</div>
			<div class="setup-content">
				<label class="setup-label">
					Host
					<input
						type="text"
						class="el-inp setup__input"
						:class="{'el-inp--error':config.host.error}"
						v-model="config.host.value"
						placeholder="Enter hostname"
					/>
				</label>
				<label class="setup-label">
					Database username
					<input
						type="text"
						class="el-inp setup__input"
						:class="{'el-inp--error':config.username.error}"
						v-model="config.username.value"
						placeholder="Enter username"
					/>
				</label>
				<label class="setup-label">
					Database Password
					<input
						type="password"
						class="el-inp setup__input"
						:class="{'el-inp--error':config.password.error}"
						v-model="config.password.value"
						placeholder="Enter password"
					/>
				</label>
				<label class="setup-label">
					Database name
					<input
						type="text"
						class="el-inp setup__input"
						:class="{'el-inp--error':config.dbname.error}"
						v-model="config.dbname.value"
						placeholder="Enter database name"
					/>
				</label>
				<div class="setup-errors">{{errors}}</div>
				<div class="setup__success" v-if="successCheck">Success check</div>
				<div class="setup-button-wrapper">
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
	name: 'Setup',
	/**
	 * Глобальные переменные страницы
	 */
	data()
	{
		return {
			errors: '',
			successCheck: false,
			config:
			{
				host     : {value: 'localhost', error: false},
				dbname   : {value: '', error: false},
				username : {value: '', error: false},
				adapter  : {value: 'Mysql', error: false},
				password : {value: '', error: false},
				baseUrl  : {value: '/element/', error: false},
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
			let qs = require('qs');
			let formData = {};
			this.errors = '';
			this.successCheck = false;

			for (var configItemCode in this.config)
			{
				if(this.config[configItemCode].value == '')
				{
					this.config[configItemCode].error = true;
					this.errors             = 'Fill all fields';
				}
				else
				{
					this.config[configItemCode].error = false;
					formData[configItemCode] = this.config[configItemCode].value;
				}
			}
			if(this.errors != '') return false;

			formData.isCheck = isCheck;
			let result = await this.$axios.post('/install.php', qs.stringify(formData));
			if(result.data.success == false)
			{
				this.errors = result.data.message;
				return false;
			}

			if(isCheck == true)
			{
				this.successCheck = true;
				return false;
			}

			this.$router.push('/');

		}
	}
}
</script>
<style lang="scss">
	.setup-head {text-align: center; margin-bottom: 26px; }
	.setup-content
	{
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		width: 434px;
		padding: 27px 50px;
		background-color: #fff;
	}
	.setup-wrapper
	{
		display: flex;
		align-items: center;
		justify-content: center;
		height: 100%;
	}
	.setup-label
	{
		font-size: 12px;
		color: #191C21;
		display: block;
		margin-bottom:15px;
	}
	.setup__input {width:100%; margin-top: 7px; }
	.setup-button-wrapper
	{
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 8px 0;
		.el-gbtn{margin-right: 15px;}
	}
	.setup-errors
	{
		margin:20px 0 10px 0;
		color: rgba(208, 18, 70, 0.7);
		font-size: 12px;
		text-align: center;
		word-break: break-word;
	}
	.setup__success
	{
		margin:20px 0 10px 0;
		color: #3A8406;
		font-size: 12px;
		text-align: center;
	}
</style>