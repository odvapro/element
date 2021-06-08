<template>
	<div id="app">
		<div class="app-wrapper">
			<transition name="el-sidebar-transition">
				<div
					class="app-wrapper__sidebar"
					v-if="sidebar.flexBasis && isShowSidebar"
					v-click-outside="closeSidebarIfIsMobile"
					:style="{ 'flex-basis': sidebar.flexBasis, 'min-width': sidebar.flexBasis }"
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
				sidebar:
				{
					flexBasis: '400px',
				},
				points:
				{
					isDrug: false,
					posX: this.$cookie.get('drugPosition')
				},
			};
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
				this.sidebar.flexBasis = `${this.$cookie.get('drugPosition')}px`;
			else
				this.sidebar.flexBasis = '400px';

			this.$store.commit('setAuthUser', JSON.parse(this.$cookie.get('user')));

			this.initEventScale();
			if (window.innerWidth > 768)
				this.$store.commit('updateShowSidebar', true);
		},
		methods:
		{
			closeSidebar()
			{
				this.$store.commit('updateShowSidebar', false);
			},
			closeSidebarIfIsMobile()
			{
				if (window.innerWidth < 768)
					this.closeSidebar();
			},
			/**
			 * Инициализация событий для уменьшения/увеливения сайдбара
			 */
			initEventScale()
			{
				let app = document.getElementsByClassName('app-wrapper')[0],
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
					self.sidebar.flexBasis = `${event.pageX}px`;
				}, false);
				document.addEventListener('mouseup', function(event)
				{
					self.points.isDrug = false;
					self.$cookie.set('drugPosition', self.points.posX, 111);
				}, false);
			},
		},
	};
</script>
<style lang="scss">
	.app-wrapper
	{
		min-height: 100vh;
		position: relative;
		display: flex;
	}
	.app-wrapper__sidebar
	{
		flex-basis: 400px;
	}
	.content-wrapper
	{
		overflow: hidden;
		position: relative;
		width: 100%;
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
		.content-wrapper
		{
			overflow: auto;
			max-height: 100vh;
			&::-webkit-scrollbar { width: 0; }
		}
		.app-wrapper__sidebar
		{
			overflow: hidden;
			flex-basis: 270px!important;
			min-width: 270px!important;
			flex-shrink: 0;
		}
	}
	.el-sidebar-transition-enter-active, .el-sidebar-transition-leave-active { transition: all ease .5s; }
	.el-sidebar-transition-enter, .el-sidebar-transition-leave-to { flex-basis: 0px!important; min-width: 0px!important; }
</style>
