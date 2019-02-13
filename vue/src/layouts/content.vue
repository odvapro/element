<template>
	<div id="app">
		<div class="app-wrapper">
			<Sidebar/>
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
		async mounted()
		{
			await this.$store.dispatch('getTables');
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
