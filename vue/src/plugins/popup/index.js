import Popop from './component';
import ConfirmPopup from './components/ConfirmPopup.vue';
import Core from './core';

const Plugin = {
	install(Vue, options = {})
	{
		if (Vue.prototype.$modal)
			return;

		const core = new Core(Vue);

		Object.defineProperty(
			Vue.prototype,
			'$modal',
			{
				get: () => core
			}
		);

		Vue.component(Popop.name, Popop);
		Vue.component(ConfirmPopup.name, ConfirmPopup);
	}
};

export default Plugin;