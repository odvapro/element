<template>
	<div id="app">
		<div class="app-wrapper">
			<Sidebar/>
			<router-view/>
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
</style>