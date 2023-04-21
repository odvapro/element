export default class Core
{
	subscriber = false;

	constructor(Vue)
	{
		this.subscriber = new Vue;
	}

	show(name)
	{
		this.subscriber.$emit('open', name);
	}

	hide(name)
	{
		this.subscriber.$emit('hide', name);
	}
}