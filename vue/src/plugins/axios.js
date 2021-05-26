import Vue from 'vue';
import axios from 'axios';

axios.defaults.withCredentials = true;
Vue.prototype.$axios = axios.create({
	baseURL: process.env.VUE_APP_API_ENDPOINT,
});
