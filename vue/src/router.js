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
		}
	]
});

export default router;
