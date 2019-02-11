import Vue from 'vue'
import router from './router'
import scssVars from './assets/variables.scss'
import scssNorm from './assets/normalize.scss'
import scsssStyle from './assets/style.scss'
import './plugins/axios.js'
import fonts from './assets/fonts.scss'
import App from './App.vue'
import store from './store/index.js'

var VueCookie = require('vue-cookie');

Vue.use(VueCookie);

Vue.config.productionTip = false;

router.beforeEach(async function(to, from, next)
{
	var valid = await router.app.$axios({url: '/api/' });

	if (!valid.data.success)
	{
		store.commit('setInstallDb', false);
		return false;
	}
	store.commit('setInstallDb', true);
	next();
});

router.beforeEach(async function(to, from, next)
{
	var valid = await router.app.$axios({url: '/api/auth/isLogged/' });

	if (!valid.data.success)
	{
		store.commit('setAuth', false);
		return false;
	}

	store.commit('setAuth', true);
	next();
});

Vue.directive('click-outside',
{
	bind: function (el, binding, vnode)
	{
		el.clickOutsideEvent = function (event)
		{
			if (!(el == event.target || el.contains(event.target)))
			{
				vnode.context[binding.expression](event);
			}
		};
		document.body.addEventListener('click', el.clickOutsideEvent)
	},
	unbind:function (el)
	{
		document.body.removeEventListener('click', el.clickOutsideEvent)
	},
});

new Vue({
	router,
	store,
	render: h => h(App)
}).$mount('#app')
