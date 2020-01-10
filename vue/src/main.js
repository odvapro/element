import Vue from 'vue'
import router from './router'
import Meta from 'vue-meta'
import scssVars from './assets/variables.scss'
import scssNorm from './assets/normalize.scss'
import scsssStyle from './assets/style.scss'
import './plugins/axios.js'
import './plugins/message.js'
import Popup from './plugins/popup/index.js'
import Checkbox from './components/forms/Checkbox.js'
import List from './components/forms/List.js'
import ListOption from './components/forms/ListOption.js'
import Select from './components/forms/Select.js'
import SelectOption from './components/forms/SelectOption.js'
import fonts from './assets/fonts.scss'
import App from './App.vue'
import store from './store/index.js'
import VueCookie from 'vue-cookie';

Vue.use(VueCookie);
Vue.use(Meta)
Vue.use(Popup)
Vue.use(Checkbox);
Vue.use(List);
Vue.use(ListOption);
Vue.use(Select);
Vue.use(SelectOption);
Vue.config.productionTip = false;
window.Vue = Vue;
window.importStyles = [];

router.beforeEach(async function(to, from, next)
{
	var valid = await router.app.$axios({url: '/' });
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
	var valid       = await router.app.$axios({url: '/auth/isLogged/' });
	let userCookies = router.app.$cookie.get('user');
	if (!valid.data.success || !userCookies)
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
		el.fisrtClick = true;
		el.clickOutsideEvent = function(event)
		{
			if(el.fisrtClick === true)
			{
				el.fisrtClick = false;
				return false;
			}
			if(!(el == event.target || el.contains(event.target)))
			{
				vnode.context[binding.expression](event,binding.arg);
				event.stopPropagation();
			}
		};
		document.body.addEventListener('click', el.clickOutsideEvent);
	},
	unbind:function(el)
	{
		document.body.removeEventListener('click', el.clickOutsideEvent);
		el.clickOutsideEvent = null;
	},
});

new Vue({
	router,
	store,
	render: h => h(App)
}).$mount('#app')
