<template>
	<List
		class="em-node__filter"
		:settings="{placeholder:'Select an Option'}"
	>
		<ListOption
			v-for="listItem in list"
		>{{ listItem.name }}</ListOption>
	</List>
</template>
<script>
	import List from '@/components/forms/List.vue';
	import ListOption from '@/components/forms/ListOption.vue';
	export default
	{
		props: ['filter','settings'],
		components: { List, ListOption },
		data()
		{
			return {
				list:[
					{name:'option one',code:'1'},
					{name:'option one',code:'2'},
					{name:'option one',code:'3'},
					{name:'option one',code:'4'},
				]
			}
		},
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
		},
		mounted()
		{
			console.log(this.settings);
		}
	}
</script>
<style lang="scss">
	.em-node__filter
	{
		border:1px solid rgba(103,115,135,0.4);
		height: 30px;
		padding:0 10px;
		border-radius: 2px;
		margin-right: 10px;
		min-width: 140px;
		&:hover{border: 1px solid rgba(103,115,135,0.7);}
	}
</style>