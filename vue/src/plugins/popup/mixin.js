export default {
	props:
	{
		name: {
			type: String,
			required: true
		}
	},
	data()
	{
		return {
			visible: false
		};
	},
	beforeMount()
	{
		this.$modal.subscriber.$on(
			'open',
			name =>
			{
				if(this.name != name)
					return;
				this.visible = true;
			}
		);
	}
};