<template>
	<div class="app-layouts-wrapper">
		<component v-bind:is="layout"></component>
	</div>
</template>

<script>
	import Auth from './layouts/auth';
	import Content from './layouts/content';
	import SetupDb from './layouts/setupDb';

	export default
	{
		components: { SetupDb, Content, Auth },
		computed:
		{
			/**
			 * Подключение нужного шаблона
			 */
			layout()
			{
				if (!this.$store.state.isIntallDb)
					return 'SetupDb';

				if (!this.$store.state.isAuth)
					return 'Auth';

				return 'Content';
			}
		}
	}
</script>
<style lang="scss">
	.app-layouts-wrapper
	{
		height: 100vh;
	}
	*
	{
		box-sizing: border-box;
	}
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