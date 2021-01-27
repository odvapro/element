<template>
	<div class="settings-wrapper">
		<div class="settings-head">
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
			<div
				class="settings-tabs-head"
				:class="settingsTabsHeadClass"
			>
				<div
					class="settings-tab-item"
					@click="setActiveTab(item)"
					v-for="item in tabs"
					:class="{active: item.active}"
				>{{item.name}}</div>
			</div>
			<div class="settings-tabs-content-wrapper">
				<div class="settings-tab-content" v-if="activeTab == $t('tables')">
					<SettingsTable/>
				</div>
				<div class="settings-tab-content" v-if="activeTab == $t('users')">
					<SettingsUser/>
				</div>
				<div class="settings-tab-content" v-if="activeTab == $t('languages')">
					<SettingsLang/>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import SettingsTable from '@/components/layouts/SettingsTable.vue';
	import SettingsUser from '@/components/layouts/SettingsUser.vue';
	import SettingsLang from '@/components/layouts/SettingsLang.vue';

	export default
	{
		components: { SettingsTable, SettingsUser, SettingsLang },
		metaInfo:{
			title: 'Settings'
		},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				tabs: [{ name: this.$t('tables'), active: true }, { name: this.$t('users'), active: false }, { name: this.$t('languages'), active: false } ],
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
		computed:
		{
			settingsTabsHeadClass()
			{
				return 'settings-tabs-head_' + this.$store.getters.lang;
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
		width: 265px;
		margin-bottom: 18px;
		align-items: center;
		border-bottom: 2px solid rgba(103, 115, 135, 0.1);
		&_ru
		{
			width: 294px;
		}
		&_en
		{
			width: 265px;
		}
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
</style>
