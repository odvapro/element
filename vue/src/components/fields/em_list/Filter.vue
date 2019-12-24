<template>
	<div
		class="em-list__filter"
		v-if="showInput"
	>
		<List
			:searchText.sync="searchText"
		>
			<template v-slot:selected>
				<ListOption
					v-for="listItem in selectedItems"
					:key="listItem.key"
					@remove="removeItem(listItem)"
				>{{ listItem.value }}</ListOption>
			</template>
			<ListOption
				v-for="listItem in itemsList"
				:key="listItem.key"
				@select="selectItem(listItem)"
			>{{ listItem.value }}</ListOption>
		</List>
	</div>
</template>
<script>
	import List from '@/components/forms/List.vue';
	import ListOption from '@/components/forms/ListOption.vue';
	export default
	{
		props: ['filter', 'settings'],
		components: { List, ListOption },
		data()
		{
			return {
				searchText: '',
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
			},
			itemsList()
			{
				return this.settings.list.filter(listItem =>
				{
					if(listItem.value.indexOf(this.searchText) !== -1)
						return true;
				});
			},
			selectedItems()
			{
				if(this.filter.value.length == 0)
					return [];

				return this.settings.list.filter(listItem =>
				{
					if(this.filter.value.indexOf(listItem.key) !== -1)
						return true;
				});
			},
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(newValue)
			{
				this.$emit('onChange', newValue);
			},
			removeItem()
			{
				this.changeValue('');
			},
			selectItem(listItem)
			{
				this.changeValue(listItem.key);
			}
		}
	}
</script>
<style lang="scss">
	.em-list__filter
	{
		position: relative;
		display: flex;
		align-items: center;
		border:1px solid rgba(103,115,135,0.4);
		height: 30px;
		padding-left: 4px;
		padding-right: 10px;
		border-radius: 2px;
		margin-right: 10px;
		min-width: 160px;
		&:hover{border: 1px solid rgba(103,115,135,0.7);}
	}
</style>