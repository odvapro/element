import Vue from 'vue';
import Router from 'vue-router';
import store from './store/index.js';

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
		{
			path: '/update/',
			name: 'update',
			component: () => import('./pages/update.vue')
		},
		{
			path: '/ext/:extname/*',
			name: 'extenstion',
			component: () => import('./pages/extension.vue')
		},
	]
});

router.beforeEach(async function(to, from, next)
{
	if(store.state.isIntallDb)
		return next();

	let valid = await router.app.$axios({url: '/' });

	if (!valid.data.success)
	{
		store.commit('setInstallDb', false);
		if (to.name === 'index')
			next();
		else
			next('/');
		return false;
	}
	store.commit('setInstallDb', true);
	next();
});

router.beforeEach(async function(to, from, next)
{
	if(store.state.isAuth === true)
	{
		next();
		return true;
	}

	let valid       = await router.app.$axios({url: '/auth/isLogged/' });
	let userCookies = router.app.$cookie.get('user');
	if (!valid.data.success || !userCookies)
	{
		store.commit('setAuth', false);
		return false;
	}

	store.commit('setAuth', true);
	next();
});

Vue.use(Router);

export default router;
