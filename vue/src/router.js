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
			component: () => import('./pages/index.vue')
		},
		{
			path: '/table/:tableCode/tview/:tview/page/:page/',
			name: 'table',
			component: () => import('./pages/table/index.vue')
		},
		{
			path: '/table/:tableCode/tview/:tview/page/:page/limit/:limit/',
			name: 'tableWithLimit',
			component: () => import('./pages/table/index.vue')
		},
		{
			path: '/table/:tableCode/el/:id/',
			name: 'tableDetail',
			component: () => import('./pages/table/detail.vue')
		},
		{
			path: '/table/:tableCode/add/',
			name: 'tableAddElement',
			component: () => import('./pages/table/detail.vue')
		},
		{
			path: '/settings/',
			name: 'settings',
			component: () => import('./pages/settings.vue')
		},
	]
});

export default router;
