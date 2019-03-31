<template>
	<div class="app-layouts-wrapper">
		<component :is="layout"></component>
		<Sprite/>
	</div>
</template>

<script>
	import Auth from './layouts/auth';
	import Content from './layouts/content';
	import SetupDb from './layouts/setupDb';
	import Sprite from './components/layouts/Sprite.vue';
	export default
	{
		components: { Sprite, SetupDb, Content, Auth },
		metaInfo:{
			title: 'Element'
		},
		computed:
		{
			/**
			 * Подключение нужного шаблона
			 */
			layout()
			{
				if (this.$store.state.isIntallDb == false)
					return 'SetupDb';

				if (this.$store.state.isAuth == false)
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