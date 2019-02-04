<template>
	<div id="app">
		<div class="app-wrapper">
			<Sidebar/>
			<router-view class="content-wrapper"/>
		</div>
		<MainPopup/>
	</div>
</template>
<script>
	import Sidebar from '@/components/layouts/Sidebar.vue';
	import MainPopup from '@/components/popups/MainPopup.vue';
	export default
	{
		name: 'Content',
		components: { Sidebar, MainPopup }
	}
</script>
<style lang="scss">
	.app-wrapper
	{
		min-height: 100vh;
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
