<template>
	<div>
		<component
			:filter="filter"
			v-bind:is="filterComponent"
			@onChange="changeValue"
			></component>
	</div>
</template>
<script>
	import Vue from 'vue';
	export default
	{
		props: ['filter','columns'],
		computed:
		{
			filterComponent()
			{
				let column = this.columns[this.filter.code];
				if(typeof column.em == 'undefined')
					return false;

				let fieldName = column.em.type_info.code;
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