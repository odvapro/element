<template>
	<div id="app">
		<div class="app-wrapper" :style="{'grid-template-columns': sidebar['gridTemplateColumns']}">
			<transition name="el-sidebar-transition">
				<div
					class="app-wrapper__sidebar"
					v-if="sidebar['gridTemplateColumns'] && (!isMobile || isShowSidebar)"
					v-click-outside="closeSidebarIfIsMobile"
				>
					<Sidebar :sidebarStyle="sidebar" @closeSidebar="closeSidebarIfIsMobile" />
				</div>
			</transition>
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
	import { mapGetters } from 'vuex';

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
				isMobile: false,
			}
		},
		watch:
		{
			$route (to, from)
			{
				this.closeSidebarIfIsMobile();
			},
		},
		computed:
		{
			...mapGetters([
				'isShowSidebar',
			]),
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

			window.addEventListener('resize', () => { this.updateIsMobile(); });
			this.updateIsMobile();
		},
		methods:
		{

			closeSidebarIfIsMobile()
			{
				if (this.isMobile)
					this.$store.commit('updateShowSidebar', false);
			},
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
			updateIsMobile()
			{
				this.isMobile = window.innerWidth < 768;
				this.$store.commit('updateShowSidebar', !this.isMobile);
			},
		},
	}
</script>
<style lang="scss">
	.app-wrapper
	{
		min-height: 100vh;
		user-select: none;
		position: relative;
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

	@media (max-width: 768px)
	{
		.app-wrapper { display: flex; }
		.app-wrapper__sidebar
		{
			overflow: hidden;
			flex-basis: 320px;
			flex-shrink: 0;
		}
		.el-sidebar-transition-enter-active, .el-sidebar-transition-leave-active { transition: all ease 0.5s; }
		.el-sidebar-transition-enter, .el-sidebar-transition-leave-to { flex-basis: 0px; }
	}
</style>
