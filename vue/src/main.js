import scssVars from './assets/variables.scss'
import scssNorm from './assets/normalize.scss'
import scsssStyle from './assets/style.scss'
import fonts from './assets/fonts.scss'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './plugins/axios.js'

Vue.config.productionTip = false;

router.beforeEach(async function(to, from, next) {
	var valid = await router.app.$axios({url: '/api/' });

	if (!valid.data.success && to.name != 'config')
		next({name: 'config'});

	next();
});

router.beforeEach(async function(to, from, next) {
	var valid = await router.app.$axios({url: '/api/auth/isLogged/' });

	if (!valid.data.success && to.name != 'config' && to.name != 'auth')
		next({name: 'auth'});

	next();
});

new Vue({
	router,
	store,
	render: h => h(App)
}).$mount('#app')