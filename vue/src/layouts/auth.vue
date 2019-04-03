<template>
	<div class="auth-content-wrapper">
		<div class="auth-content__logo">
			<img src="/images/logo.svg" alt="">
		</div>
		<div class="auth-form" v-if="activeForm == 'login'">
			<label class="auth-label">
				<div class="auth-label-title">Login</div>
				<input class="auth-form-input el-inp" type="text" placeholder="Enter your login" v-model="user.login.value" :class="{'el-inp--error': user.login.error}">
				<span class="auth__error-text">{{user.login.error}}</span>
			</label>
			<label class="auth-label">
				<div class="auth-label-title">Password</div>
				<input class="auth-form-input el-inp" type="password" placeholder="Enter your password" v-model="user.password.value" :class="{'el-inp--error': user.password.error}">
				<span class="auth__error-text">{{user.password.error}}</span>
			</label>
			<div class="auth-bottom-btns">
				<button class="auth-fill-btn el-btn" @click="authUser()">Log In</button>
				<button class="auth-transpar-btn" @click="activeForm = 'forgot'">Forgot your password?</button>
			</div>
		</div>
		<div class="auth-form" v-if="activeForm == 'forgot'">
			<label class="auth-label">
				<div class="auth-label-title">Login</div>
				<input type="text" placeholder="Enter your login" class="auth-form-input el-inp">
			</label>
			<div class="auth-bottom-btns">
				<button class="auth-fill-btn el-btn" @click="activeForm = 'sended'">Reset Password</button>
				<button class="auth-transpar-btn" @click="activeForm = 'login'">Return to Log In page?</button>
			</div>
		</div>
		<div class="auth-form auth-form-fix-heigth" v-if="activeForm == 'sended'">
			New password was sended to your emial.
		</div>
	</div>
</template>
<script>
export default
{
	name: 'Auth',
	/**
	 * Глобальные переменные странциы
	 */
	data()
	{
		return {
			user:
			{
				login: {value: '', error: ''},
				password: {value: '', error: ''}
			},
			errors: '',
			activeForm: 'login'
		}
	},
	methods:
	{
		/**
		 * Проверка на валидность полей
		 */
		isValid()
		{
			var isValid = true;

			this.user.login.error = '';
			this.user.password.error = '';

			if (this.user.login.value == '')
			{
				this.user.login.error = 'Incorrect username';
				isValid = false;
			}

			if (this.user.password.value == '')
			{
				this.user.password.error = 'Incorrect password';
				isValid = false;
			}

			return isValid;
		},
		/**
		 * Авторизоваться
		 */
		async authUser()
		{
			if (!this.isValid())
				return false;

			var data = new FormData();

			data.append('login', this.user.login.value);
			data.append('password', this.user.password.value);

			var result = await this.$axios.post('/api/auth/index/', data);

			if (!result.data.success)
				return false;

			this.$cookie.set('user', JSON.stringify(result.data.user), 12);

			this.$router.push('/');
		}
	}
}
</script>
<style lang="scss">
	.auth-content__logo
	{
		margin-bottom: 30px;
	}
	.auth-transpar-btn
	{
		font-size: 12px;
		color: rgba(103, 115, 135, 0.7);
		border: none;
		cursor: pointer;
	}
	.auth-bottom-btns
	{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	.auth-fill-btn {margin-bottom: 11px; }
	.auth-label
	{
		margin-bottom: 15px;
		display: block;
		position: relative;
	}
	.auth-label-title
	{
		color: #191C21;
		margin-bottom: 7px;
		font-size: 12px;
	}
	.auth-form-input.el-inp {width: 100%; }
	.auth-content-wrapper
	{
		display: flex;
		position: fixed;
		flex-direction: column;
		z-index: 2;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		align-items: center;
		justify-content: center;
	}
	.auth-form
	{
		width: 434px;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		padding: 30px 50px;
		&.auth-form-fix-heigth
		{
			height: 181px;
			align-items: center;
			display: flex;
			color: rgba(103, 115, 135, 0.7);
			font-size: 12px;
			justify-content: center;
		}
	}
	.auth__error-text
	{
		position: absolute;
		bottom: -13px;
		color: rgba(208, 18, 70, 0.4);
		font-size: 10px;
		right: 0;
	}
</style>