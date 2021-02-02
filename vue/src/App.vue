<template>
	<div class="app-layouts-wrapper">
		<component :is="layout"></component>
		<Sprite/>
	</div>
</template>

<script>
	import Vue from 'vue';
	import Auth from './layouts/auth';
	import Content from './layouts/content';
	import Setup from './layouts/setup';
	import Sprite from './components/layouts/Sprite.vue';
	import qs from 'qs';
	export default
	{
		components: { Sprite, Setup, Content, Auth },
		metaInfo:
		{
			title: 'Element',
			meta:
			[
				{ name: 'msapplication-TileColor', content: '#da532c' },
				{ name: 'theme-color', content: '#ffffff' },
				{ name: 'viewport min-width=375px', content: 'min-width=375, width=device-width, initial-scale=1' },
				{ name: 'MobileOptimized', content: '375' },
			],
			link:
			[
				{ rel:"apple-touch-icon", sizes:"180x180", href:`${process.env.BASE_URL}apple-touch-icon.png` },
				{ rel:"icon", type:"image/png", sizes:"32x32", href:`${process.env.BASE_URL}favicon-32x32.png` },
				{ rel:"icon", type:"image/png", sizes:"16x16", href:`${process.env.BASE_URL}favicon-16x16.png` },
				{ rel:"manifest", href:`${process.env.BASE_URL}site.webmanifest` },
				{ rel:"mask-icon", href:`${process.env.BASE_URL}safari-pinned-tab.svg`, color:"#5bbad5" }
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
		},
		methods:
		{
			async setLanguage(language = 'en', id = -1)
			{
				if (~id)
					await this.$store.dispatch('setLanguage', {language, id});
				this.$store.commit('setLanguage', language);
			},
		},
		async mounted()
		{
			if (!this.$store.isAuthUser)
				if (this.$cookie.get('user'))
					this.$store.commit('setAuthUser', JSON.parse(this.$cookie.get('user')));

			if (this.$store.state.users.authUser.language)
				this.setLanguage(this.$store.state.users.authUser.language);
			else
				this.setLanguage('en', this.$store.state.users.authUser.id);
		}
	}
</script>
<style lang="scss">
	.app-layouts-wrapper{height: 100vh; }
	*{box-sizing: border-box; }
</style>
