import Vue from 'vue';
import router from './router';
import Meta from 'vue-meta';
import scssVars from './assets/variables.scss';
import scssNorm from './assets/normalize.scss';
import scsssStyle from './assets/style.scss';
import './plugins/axios.js';
import './plugins/message.js';
import Popup from './plugins/popup/index.js';
import fonts from './assets/fonts.scss';
import App from './App.vue';
import store from './store/index.js';
import VueCookie from 'vue-cookie';
import Checkbox from './components/forms/Checkbox.vue';
import List from './components/forms/List.vue';
import ListOption from './components/forms/ListOption.vue';
import Select from './components/forms/Select.vue';
import SelectOption from './components/forms/SelectOption.vue';
import DateForm from './components/forms/DateForm.vue';
import Table from './components/tviews/Table.vue';
import './plugins/highlightjs/main.js';

Vue.use(VueCookie);
Vue.use(Meta);
Vue.use(Popup);
Vue.config.productionTip = false;

Vue.component('Checkbox', Checkbox);
Vue.component('List', List);
Vue.component('ListOption', ListOption);
Vue.component('Select', Select);
Vue.component('SelectOption', SelectOption);
Vue.component('DateForm', DateForm);
Vue.component('Table', Table);

window.Vue = Vue;

Vue.directive('click-outside', {
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
}).$mount('#app');
