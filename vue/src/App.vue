<template>
	<div class="app-layouts-wrapper">
		<component :is="layout"></component>
		<Sprite/>
	</div>
</template>

<script>
	import Auth from './layouts/auth';
	import Content from './layouts/content';
	import Setup from './layouts/setup';
	import Sprite from './components/layouts/Sprite.vue';
	export default
	{
		components: { Sprite, Setup, Content, Auth },
		metaInfo:{
			title: 'Element',
			meta:
			[
				{ name: 'msapplication-TileColor', content: '#da532c' },
				{ name: 'theme-color', content: '#ffffff' },
			],
			link:
			[
				{ rel:"apple-touch-icon", sizes:"180x180", href:"/apple-touch-icon.png" },
				{ rel:"icon", type:"image/png", sizes:"32x32", href:"/favicon-32x32.png" },
				{ rel:"icon", type:"image/png", sizes:"16x16", href:"/favicon-16x16.png" },
				{ rel:"manifest", href:"/site.webmanifest" },
				{ rel:"mask-icon", href:"/safari-pinned-tab.svg", color:"#5bbad5" }
			]
		},
		computed:
		{
			/**
			 * Подключение нужного шаблона
			 */
			layout()
			{
				if (this.$store.state.isIntallDb == false)
					return 'Setup';

				if (this.$store.state.isAuth == false)
					return 'Auth';

				return 'Content';
			}
		}
	}
</script>
<style lang="scss">
	.app-layouts-wrapper{height: 100vh; }
	*{box-sizing: border-box; }
	body{min-width: 1180px; }
	.app-wrapper
	{
		min-height: 100vh;
		display: grid;
		grid-template-columns: 400px auto;
		grid-template-rows: 100%;
	}
	.content-wrapper {overflow: hidden; }
</style>