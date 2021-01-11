<template>
	<div class="auth-content-wrapper">
		<div class="auth-content__logo">
			<img src="@/assets/images/logo.svg" alt="">
		</div>
		<div class="auth-form" v-if="activeForm == 'login'">
			<form @submit.prevent="authUser">
				<label class="auth-label">
					<div class="auth-label-title">{{$t('login')}}</div>
					<input
						class="auth-form-input auth-form-input__login el-inp"
						type="text"
						data-test="input-login"
						:placeholder="$t('auth.enter_your_login_or_email')"
						v-model="user.login.value"
						:class="{'el-inp--error': user.login.error}"
					>
					<span class="auth__error-text" data-test="error-text">{{user.login.error}}</span>
				</label>
				<label class="auth-label">
					<div class="auth-label-title">{{$t('password')}}</div>
					<input
						class="auth-form-input auth-form-input__password el-inp"
						type="password"
						data-test="input-password"
						:placeholder="$t('auth.enter_your_password')"
						v-model="user.password.value"
						:class="{'el-inp--error': user.password.error}"
					>
					<span class="auth__error-text" data-test="error-text">{{user.password.error}}</span>
				</label>
				<div class="auth-bottom-btns">
					<button class="auth-fill-btn el-btn" data-test="auth-login">{{$t('auth.log_in')}}</button>
					<a class="auth-transpar-btn" data-test="btn-transpar" @click.prevent="activeForm = 'forgot'">{{$t('auth.forgot_your_password')}}</a>
				</div>
			</form>
		</div>
		<div class="auth-form" v-if="activeForm == 'forgot'">
			<form @submit="forgotPass">
				<label class="auth-label">
					<div class="auth-label-title">{{$t('auth.forgot_password')}}</div>
					<input
						type="text"
						v-model="forgot.email.value"
						:placeholder="$t('auth.enter_your_email')"
						class="auth-form-input auth-form-input__forgot-password el-inp"
						:class="{'el-inp--error': forgot.email.error}"
					>
					<span class="auth__error-text" data-test="error-text">{{forgot.email.error}}</span>
				</label>
				<div class="auth-bottom-btns">
					<button class="auth-fill-btn el-btn" data-test="auth-login">{{$t('auth.reset_password')}}</button>
					<a class="auth-transpar-btn" data-test="btn-transpar" @click="activeForm = 'login'">{{$t('auth.return_to_log_in_page')}}</a>
				</div>
			</form>
		</div>
		<div class="auth-form auth-form-fix-heigth" v-if="activeForm == 'sended'">
			{{$t('auth.new_password_was_sended_to_your_email')}}
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
			forgot:
			{
				email: {value: '', error: ''}
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
		validateAuth()
		{
			var isValid = true;

			this.user.login.error = '';
			this.user.password.error = '';

			if (this.user.login.value == '')
			{
				this.user.login.error = this.$t('auth.incorrect_login');
				isValid = false;
			}

			if (this.user.password.value == '')
			{
				this.user.password.error = this.$t('auth.incorrect_password');
				isValid = false;
			}

			return isValid;
		},
		/**
		 * Авторизоваться
		 */
		async authUser()
		{
			if (!this.validateAuth())
				return false;

			var data = new FormData();
			let user;
			data.append('login', this.user.login.value);
			data.append('password', this.user.password.value);

			var result = await this.$axios.post('/auth/index/', data);
			user = result.data.user;

			if (!result.data.success)
			{
				this.user.login.error = this.$t('auth.incorrect_login_or_password');
				this.user.password.error = this.$t('auth.incorrect_login_or_password');
				return false;
			}

			if (user.language)
				user.language = user.language;
			else
				user.language = 'en';

			this.$cookie.set('user', JSON.stringify(user), 12);

			await this.setLanguage(user.language, user.id);
			this.$router.push('/');
		},
		async setLanguage(language, id)
		{
			await this.$store.dispatch('setLanguage', {language, id});
			this.$store.commit('setLanguage', language);
		},
		/**
		 * Проверка данных для востановления пароля
		 */
		validateForgot()
		{
			var isValid = true;

			this.forgot.email.error = '';

			if(this.forgot.email.value == '')
			{
				this.forgot.email.error = this.$t('auth.empty_email');
				isValid = false;
			}
			else if(!/^\w.+@\w+\.\w{2,4}$/i.test(this.forgot.email.value))
			{
				this.forgot.email.error = this.$t('auth.invalid_email_format');
				isValid = false;
			}

			return isValid;
		},
		/**
		 * Востановление пароля
		 */
		async forgotPass(event)
		{
			event.preventDefault();

			if(!this.validateForgot())
				return false;

			var data = new FormData();

			data.append('email', this.forgot.email.value);

			var result = await this.$axios.post('/auth/forgotPass/', data);

			if(!result.data.success)
			{
				this.forgot.email.error = this.$t('auth.incorrect_email');
				return false;
			}

			this.activeForm = 'sended';
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
		&:hover{text-decoration: underline;}
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
