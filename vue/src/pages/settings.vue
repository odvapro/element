<template>
	<div class="settings-wrapper">
		<div class="settings-head">
			<div class="settings-head__burger"><MobileBurger/></div>
			<div class="settings-head-name">
				<div class="settings-icon-wrapper">
					⚙️
				</div>
				<div class="settings-name-wrapper">
					<div class="settings-head-label">{{$t('settings')}}</div>
					<div class="settings-head-descr">{{$t('pages.settings.user_and_table_settings')}}</div>
				</div>
			</div>
		</div>
		<div class="settings-tab-wrapper">
			<div class="settings-tabs-head">
				<div
					class="settings-tab-item"
					@click="setActiveTab(item)"
					v-for="item in tabs"
					:class="{active: item.active}"
					v-if="item.noAdminRights || $store.state.users.authUser.is_admin"
				>{{item.name}}</div>
			</div>
			<div class="settings-tabs-content-wrapper">
				<div class="settings-tab-content" v-if="activeTab == $t('tables') && $store.state.users.authUser.is_admin">
					<SettingsTable/>
				</div>
				<div class="settings-tab-content" v-if="activeTab == $t('users') && $store.state.users.authUser.is_admin">
					<SettingsUser/>
				</div>
				<div class="settings-tab-content" v-if="activeTab == $t('languages')">
					<SettingsLang/>
				</div>
				<div class="settings-tab-content" v-if="activeTab == $t('groups') && $store.state.users.authUser.is_admin">
					<SettingsGroups/>
				</div>
				<div class="settings-tab-content" v-if="activeTab == 'API&Tokens' && $store.state.users.authUser.is_admin">
					<SettingsApi/>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import SettingsTable from '@/components/layouts/SettingsTable.vue';
	import SettingsUser from '@/components/layouts/SettingsUser.vue';
	import SettingsLang from '@/components/layouts/SettingsLang.vue';
	import MobileBurger from '@/components/blocks/MobileBurger.vue';
	import SettingsGroups from '@/components/layouts/SettingsGroups.vue';
	import SettingsApi from '@/components/layouts/SettingsApi.vue';

	export default
	{
		components: { SettingsTable, SettingsUser, SettingsLang, MobileBurger, SettingsGroups, SettingsApi, },
		metaInfo:{
			title: 'Settings'
		},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				tabs:[
					{ name: this.$t('tables'), active: true },
					{ name: this.$t('users'), active: false },
					{ name: this.$t('languages'), active: false, noAdminRights: true },
					{ name: this.$t('groups'), active: false },
					{ name: 'API&Tokens', active: false }
				],
				activeTab: this.$t('tables'),
			}
		},
		methods:
		{
			/**
			 * Задать активность табу
			 */
			setActiveTab(tab)
			{
				this.clearAllTabsActive();

				tab.active = true;
				this.activeTab = tab.name;
			},
			clearAllTabsActive()
			{
				for (var item of this.tabs)
					item.active = false;
			}
		},
		watch:
		{
			'$store.getters.lang'()
			{
				this.tabs[0].name = this.$t('tables');
				this.tabs[1].name = this.$t('users');
				this.tabs[2].name = this.$t('languages');

				this.setActiveTab(this.tabs[this.tabs.length-1]);
			}
		},
		mounted()
		{
			this.$store.commit('showLoader',true);
			if (!this.$store.state.users.authUser.is_admin)
				this.setActiveTab(this.tabs[2]);

		}
	}
</script>
<style lang="scss">
	.settings-wrapper
	{
		padding: 23px 0 23px 21px;
		height: 100%;
		display: flex;
		flex-direction: column;
	}
	.settings-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 13px;
		padding-right: 95px;
		padding-right: 14px;
		justify-content: flex-start;
		align-items: center;
	}
	.settings-head-name
	{
		display: flex;
		align-items: center;
	}
	.settings-icon-wrapper
	{
		margin-right: 3px;
		font-weight: 500;
		line-height: normal;
		font-size: 20px;
		color: #000000;
	}
	.settings-head-label
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.settings-head-descr
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.settings-tabs-head
	{
		display: flex;
		height: 37px;
		width: fit-content;
		margin-bottom: 18px;
		align-items: center;
		border-bottom: 2px solid rgba(103, 115, 135, 0.1);
	}
	.settings-tab-wrapper
	{
		display: flex;
		flex-direction: column;
	}
	.settings-tab-item
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);;
		padding: 0 12px;
		height: 38px;
		line-height: 38px;
		margin-right: 25px;
		border-bottom: 2px solid transparent;
		cursor: pointer;
		&:last-child
		{
			margin-right: unset;
		}
		&.active
		{
			border-bottom: 2px solid #191C21;
			color: #191C21;
		}
	}
	.settings-tabs-content-wrapper
	{
		display: flex;
		align-items: flex-start;
		overflow: auto;
		height: 100%;
	}
	.settings-head__burger { margin-right: 20px; }
	@media (max-width: 768px)
	{
		.settings-wrapper { min-width: 375px; }
		.settings-tab-wrapper
		{
			overflow-x: scroll;
			max-width: calc(100vw - 46px);
		}
		.settings-tabs-content-wrapper
		{
			overflow: initial;
		}
		.settings-tabs-head
		{
			padding-bottom: 0;
			height: auto;
			&::-webkit-scrollbar { display: none; }
		}
	}
</style>
