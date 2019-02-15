<template>
	<div id="app">
		<div class="app-wrapper" :style="{'grid-template-columns': sidebar['gridTemplateColumns']}">
			<Sidebar :sidebarStyle="sidebar" v-if="sidebar['gridTemplateColumns']"/>
			<router-view class="content-wrapper"/>
		</div>
		<MainPopup/>
		<SettingsPopup v-if="$store.state.settings.popupActive"/>
	</div>
</template>
<script>
	import Sidebar from '@/components/layouts/Sidebar.vue';
	import MainPopup from '@/components/popups/MainPopup.vue';
	import SettingsPopup from '@/components/popups/SettingsPopup.vue';
	export default
	{
		name: 'Content',
		components: { Sidebar, MainPopup, SettingsPopup },
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				sidebar: {}
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		async mounted()
		{
			await this.$store.dispatch('getTables');

			if (this.$cookie.get('drugPosition') >= 200)
				this.sidebar['gridTemplateColumns'] = this.$cookie.get('drugPosition') + 'px auto';
			else
				this.sidebar['gridTemplateColumns'] = '400px auto';
		}
	}
</script>
<style lang="scss">
	.app-wrapper
	{
		min-height: 100vh;
		user-select: none;
	}
	.app-wrapper
	{
		display: grid;
		grid-template-columns: 400px auto;
		grid-template-rows: 100%;
	}
	.content-wrapper
	{
		overflow: hidden;
	}
</style>
