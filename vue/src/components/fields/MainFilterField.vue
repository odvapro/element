<template>
	<component
		:filter="filter"
		:settings="settings"
		v-bind:is="filterComponent"
		@onChange="changeValue"
		data-test="filter-value"
	></component>
</template>
<script>
	import Vue from 'vue';
	export default
	{
		props: ['filter','columns'],
		data()
		{
			return {
				settings:false
			}
		},
		computed:
		{
			filterComponent()
			{
				let column = this.columns[this.filter.code];
				if(typeof column.em == 'undefined')
					return false;

				let fieldName = column.em.type_info.code;
				this.settings = column.em.settings;

				return () => import(`@/components/fields/${fieldName}/Filter.vue`);
			}
		},
		methods:
		{
			changeValue(value)
			{
				this.$emit('onChange', {value, filter:this.filter});
			},
		}
	}
</script>