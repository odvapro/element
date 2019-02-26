import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

var router = new Router({
	mode: 'history',
	base: '/element',
	routes: [
		{
			path: '/',
			name: 'index',
			component: () => import(/* webpackChunkName: "about" */ './pages/index.vue')
		},
		{
			path: '/table/:tableCode/tview/:tview/page/:page/',
			name: 'table',
			component: () => import(/* webpackChunkName: "about" */ './pages/table/index.vue')
		},
		{
			path: '/settings/',
			name: 'settings',
			component: () => import(/* webpackChunkName: "about" */ './pages/settings.vue')
		},
	]
});

export default router;
