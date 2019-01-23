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
			path: '/configuration/',
			name: 'config',
			component: () => import(/* webpackChunkName: "about" */ './pages/configuration.vue')
		},
		{
			path: '/table/:name',
			name: 'tableDetail',
			component: () => import(/* webpackChunkName: "about" */ './pages/table.vue')
		}
	]
});

export default router;
