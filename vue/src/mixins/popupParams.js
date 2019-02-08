export default
{
	/**
	 * Отправлять ширину попапа
	 */
	mounted()
	{
		this.$nextTick(function()
		{
			this.$emit('width', this.$el.clientWidth);
		})
	}
}