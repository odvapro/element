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
				sidebar: {},
				points:
				{
					isDrug: false,
					posX: this.$cookie.get('drugPosition')
				},
			}
		},
		methods:
		{
			/**
			 * Инициализация событий для уменьшения/увеливения сайдбара
			 */
			initEventScale()
			{
				var app = document.getElementsByClassName('app-wrapper')[0],
					self = this;

				document.addEventListener('mousedown', function(event)
				{
					if (event.target.classList.value != 'drug')
						return false;

					self.points.isDrug = true;
				}, false);

				document.addEventListener('mousemove', function(event)
				{
					if (!self.points.isDrug)
						return false;

					if (event.pageX < 200 || event.pageX > 480)
						return false;

					self.points.posX = event.pageX;
					app.style.gridTemplateColumns = event.pageX + 'px auto'
				}, false);
				document.addEventListener('mouseup', function(event)
				{
					self.points.isDrug = false;
					self.$cookie.set('drugPosition', self.points.posX, 111);
				}, false);
			},
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

			this.initEventScale();
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
