<template>
	<div class="settings-users-wrapper">
		<div class="settings-users-head">
			<div class="settings-users-row-data">
				<div class="settings-users-item">
					<div class="settings-users-item-title">{{$t('login')}}</div>
				</div>
				<div class="settings-users-item">
					<div class="settings-users-item-title">{{$t('name')}}</div>
				</div>
				<div class="settings-users-item"></div>
			</div>
			<div class="settings-users-row" v-for="user in $store.state.settings.users">
				<div class="settings-users-row-data">
					<div class="settings-users-item">
						<svg width="7" height="13" class="settings-users-item-img" :class="{active: user.isShow}" @click="setRowSetting(user)">
							<use xlink:href="#arrow"></use>
						</svg>
						<div class="settings-users-item-code">{{ user.login }}</div>
					</div>
					<div class="settings-users-item">{{ user.name }}</div>
					<div class="settings-users-item">
						<a @click="removeUser(user)" class="settings-users__remove-user" href="#">{{$t('remove')}}</a>
					</div>
				</div>
				<div class="settings-users-row-setting">
					<div class="settings-users-row-setting-item" :class="{active: user.isShow}">
						<div class="settings-users-item">
							id
						</div>
						<div class="settings-users-item">
							{{ user.id }}
						</div>
					</div>
					<div class="settings-users-row-setting-item" :class="{active: user.isShow}">
						<div class="settings-users-item"> {{$t('name')}} </div>
						<div class="settings-users-item">
							<input
								type="text"
								class="settings-users-input"
								v-model="user.name"
								@change="updateUser(user)"
								:placeholder="$t('set_something') + $t('name')"
							/>
						</div>
					</div>
					<div class="settings-users-row-setting-item" :class="{active: user.isShow}">
						<div class="settings-users-item"> {{$t('login')}} </div>
						<div class="settings-users-item">
							<input
								type="text"
								class="settings-users-input"
								v-model="user.login"
								@change="updateUser(user)"
								:placeholder="$t('set_something') + $t('login')"
							/>
						</div>
					</div>
					<div class="settings-users-row-setting-item" :class="{active: user.isShow}">
						<div class="settings-users-item"> Email </div>
						<div class="settings-users-item">
							<input
								type="text"
								class="settings-users-input"
								v-model="user.email"
								@change="updateUser(user)"
								:placeholder="$t('set_something') + 'Email'"
							/>
						</div>
					</div>
					<div class="settings-users-row-setting-item" :class="{active: user.isShow}">
						<div class="settings-users-item"> {{$t('password')}} </div>
						<div class="settings-users-item settings-users__password-field">
							<input
								type="password"
								class="settings-users-input"
								v-model="user.newPassword"
								:placeholder="$t('set_something') + $t('password')"
							>
							<button @click="updatePassword(user)" v-if="user.newPassword != ''" class="settings-users-btn">change</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button @click="openAddUser()" class="el-gbtn">{{$t('add_user')}}</button>
		<Popup
			:visible.sync="addPopupVisible"
		>
			<AddUser
				@cancel="addPopupVisible = false"
			></AddUser>
		</Popup>
	</div>
</template>
<script>
	import AddUser from '@/components/popups/AddUser.vue';
	import qs from 'qs';
	export default
	{
		components: { AddUser },
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				users: [],
				addPopupVisible:false
			}
		},
		methods:
		{
			/**
			 * Открыть/закрыть настройки колонок
			 */
			setRowSetting(settingItem)
			{
				settingItem.isShow = !settingItem.isShow;
			},

			/**
			 * Updates User data
			 */
			async updateUser(user)
			{
				let updateData   = {}
				updateData.id    = user.id;
				updateData.login = user.login;
				updateData.name  = user.name;
				updateData.email = user.email;
				updateData       = qs.stringify(updateData);

				var result = await this.$axios.post('/users/updateUser/',updateData);
				if(result.data.success)
					this.ElMessage(this.$t('elMessages.user_saved'));
			},

			/**
			 * Updates user password
			 */
			async updatePassword(user)
			{
				let updateData      = {}
				updateData.id       = user.id;
				updateData.password = user.newPassword;
				updateData          = qs.stringify(updateData);

				var result = await this.$axios.post('/users/updateUser/',updateData);
				if(result.data.success)
				{
					this.ElMessage(this.$t('elMessages.password_saved'));
					user.newPassword = '';
				}
			},
			/**
			 * Открыть
			 */
			openAddUser()
			{
				this.addPopupVisible = true;
			},
			/**
			 * Removes user
			 */
			removeUser(user)
			{
				this.$store.dispatch('removeUser',user);
			}
		},

		/**
		 * Get all users
		 */
		async mounted()
		{
			this.$store.dispatch('getUsers');
		}
	}
</script>
<style lang="scss">
	.settings-users-head {margin-bottom: 10px; }
	.settings-users-btn
	{
		width: 53px;
		min-width: 53px;
		height: 20px;
		font-size: 10px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		color: #7C7791;
		border: none;
		cursor: pointer;
	}
	.settings-users-input
	{
		border: none;
		background-color: transparent;
		width: 130px;
		height: 100%;
		font-size: 12px;
		&::placeholder{color: rgba(103, 115, 135, 0.4); }
	}
	.settings-users-item-img
	{
		margin-right: 11px;
		cursor: pointer;
		transition: all 0.3s;
		&.active {transform: rotate(90deg); }
	}
	.settings-users-item-name
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-users-item-code
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-users-item-title
	{
		color: #677387;
		font-size: 12px;
	}
	.settings-users-row-setting-item
	{
		display: flex;
		overflow: hidden;
		background: rgba(103, 115, 135, 0.1);
		transition: all 0.3s;
		height: 0;
		padding: 0 8px;
		.settings-users-item {border-right: none; }
		.settings-users-item:first-child {padding-left: 30px; }
		&.active {height: 39px; border-bottom: 1px solid rgba(103, 115, 135, 0.1); }
	}
	.settings-users-row-data
	{
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		padding: 0 8px;
	}
	.settings-users-row
	{
		display: flex;
		flex-direction: column;
		position: relative;
		.settings-users-row-setting-item.active:first-child {border-top: none; }
		.settings-users-row-setting-item.active:last-child {border-bottom: none; }
		&:last-child {border-bottom: 1px solid rgba(103, 115, 135, 0.1); }
	}
	.settings-users-item
	{
		display: flex;
		word-break: break-word;
		white-space: nowrap;
		align-items: center;
		height: 39px;
		overflow: hidden;
		padding: 0 11px;
		position: relative;
		min-width: 140px;
		width: 140px;
		color: rgba(25, 28, 33, 0.7);
		font-size: 12px;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&:last-child {border-right: none; }
	}
	.settings-users__password-field{width:210px;}
	.settings-users__remove-user
	{
		font-style: normal;
		font-weight: normal;
		font-size: 12px;
		line-height: 16px;
		color: rgba(25, 28, 33, 0.5);
		text-decoration: none;
		&:hover{text-decoration: underline;}
	}
</style>