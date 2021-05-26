<template>
	<div>
		<input
			type="text"
			v-if="showInput"
			class="filters-popup__filter-input el-inp"
			:placeholder="$t('value')"
			@keyup="changeValue"
			:value="filter.value"
		/>
	</div>
</template>
<script>
	export default
	{
		props: ['filter'],
		computed:
		{
			showInput()
			{
				let emptyCollations = ['IS EMPTY','IS NOT EMPTY'];
				if(emptyCollations.indexOf(this.filter.operation) != -1)
					return false;
				return true;
			}
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(event)
			{
				this.$emit('onChange', event.target.value);
			}
		}
	}
</script>
