<template>
	<div id="app">
		<div class="app-wrapper">
			<Sidebar/>
			<router-view class="content-wrapper"/>
		</div>
	</div>
</template>

<script>
	import Sidebar from '@/components/layouts/Sidebar.vue'
	export default
	{
		name: 'App',
		components: { Sidebar }
	}
</script>
<style lang="scss">
	body
	{
		min-width: 1180px;
	}
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