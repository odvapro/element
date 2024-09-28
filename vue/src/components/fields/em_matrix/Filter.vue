<template>
	<div class="em-matrix-filter" v-if="showSelect">
		<List
			class="filters-popup__select"
			:settings="{placeholder: $t('select_an_option') }"
			:searchText.sync="searchText"
		>
			<template v-slot:selected>
				<ListOption
					v-if="localFieldValue.value"
					@remove="removeItem"
					:current=true
				>{{ localFieldValue.name }}</ListOption>
			</template>
			<ListOption
				v-for="node in nodes"
				:key="node.code"
				@select="selectItem(node)"
			>{{ node.name }}</ListOption>
		</List>
	</div>
</template>

<script>
	export default
	{
		props: ['filter', 'settings'],
		data()
		{
			return {
				isManyToMany: false,
				nodes: [],
				searchText:'',
				value: {
					field: null,
					value: null,
				},
				localFieldValue:false
			};
		},
		computed:
		{
			showSelect()
			{
				return !(/IS NOT EMPTY|IS EMPTY/.test(this.filter.operation))
			},
		},
		methods:
		{
			changeFieldValue(field)
			{
				this.value.field = field;
				this.saveValue();
			},
			/**
			 * Send change current value
			 */
			changeValue(newValue)
			{
				this.$emit('onChange', newValue);
				this.getNodes();
			},

			selectItem(listItem)
			{
				this.changeValue(listItem.value);
			},
			removeItem()
			{
				this.localFieldValue = { value: false };
				this.changeValue(false);
			},

			async getNodes()
			{
				var data = new FormData();
				data.append('nodeFieldCode', this.settings.finalTableField);
				data.append('nodeTableCode', this.settings.finalTableCode);
				data.append('nodeSearchCode', 'name');
				data.append('q', this.searchText);

				let result = await this.$axios({
					method : 'POST',
					data   : data,
					headers: { 'Content-Type': 'multipart/form-data' },
					url    : '/field/em_matrix/index/autoComplete/'
				});

				if (!result.data.success)
					return false;
				this.nodes = result.data.result;
				this.localFieldValue = this.nodes.filter(item => {return Number(item.value) === Number(this.filter.value)})[0] || { id: false };
			},
		},
		mounted()
		{
			this.getNodes();
		}
	};
</script>

<style lang="scss">
	.em-matrix-filter
	{
		position: relative;
		display: flex;
	}
</style>
