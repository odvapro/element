<template>
	<div class="em-check-wrapper">
		<Checkbox
			:checked.sync="isChecked"
			@change="changeStatus"
		></Checkbox>
	</div>
</template>
<script>
	import Checkbox from '@/components/forms/Checkbox.vue';
	export default
	{
		components: {Checkbox},
		props: ['fieldValue', 'fieldSettings'],
		/**
		 * Checke status
		 */
		data()
		{
			return {
				isChecked: false
			}
		},
		methods:
		{
			async changeStatus(checkboxStatus)
			{
				this.$emit('onChange', {
					value    : checkboxStatus,
					settings : this.fieldSettings
				});
			}
		},
		/**
		 * Set default value
		 */
		mounted()
		{
			this.isChecked = this.fieldValue;
		}
	}
</script>
<style lang="scss">
.em-check
{
	display: block;
	width: 100%;
	text-align: center;
}
</style>