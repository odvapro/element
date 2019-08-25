<template>
	<div id="app">
		<div class="app-wrapper" :style="{'grid-template-columns': sidebar['gridTemplateColumns']}">
			<Sidebar :sidebarStyle="sidebar" v-if="sidebar['gridTemplateColumns']"/>
			<div class="content-wrapper">
				<router-view/>
				<div class="content__loader" v-if="$store.state.showLoader">
					<Loader class="content__loader-block" />
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Loader from '@/components/forms/Loader.vue';
	import Sidebar from '@/components/layouts/Sidebar.vue';
	export default
	{
		name: 'Content',
		components: { Sidebar, Loader },
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
					if (event.target.classList.value != 'sidebar_drug')
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


			this.$store.commit('setAuthUser', JSON.parse(this.$cookie.get('user')));

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
	}
	.content-wrapper
	{
		overflow: hidden;
		position: relative;
	}
	.content__loader
	{
		position: absolute;
		top:0px;
		left:0px;
		width:100%;
		height: 100%;
		background: rgba(255,255,255,1);
	}
	.content__loader-block
	{
		position: absolute;
		top:140px;
		left:50%;
		margin-left: -50px;
	}
</style>
