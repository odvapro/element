<template>
	<li @click="select"><slot></slot></li>
</template>
<script>
	export default
	{
		name:"SelectOption",
		methods:
		{
			/**
			 * Choose option and close dropdown
			 */
			select: function(event)
			{
				var instance = event.target;
				this.$parent.setContent(instance.innerHTML);
				this.$parent.toggleDropdown();
			}
		}
	}
</script>