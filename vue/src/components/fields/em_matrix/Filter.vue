<template>
	<div class="em-matrix-filter">
		<Select
			class="filters-popup__select"
			:defaultText="(value.field && value.field.code) || $t('empty')"
			v-if="value"
		>
			<SelectOption
				v-for="column,columnIndex in columns"
				@click.native="changeFieldValue(column)"
				:key="columnIndex"
			>{{ column }}</SelectOption>
		</Select>
		<input
			type="text"
			v-if="showInput"
			class="filters-popup__filter-input el-inp"
			:placeholder="$t('value')"
			@keyup="changeInputValue"
			:value="filter.value.value"
		/>
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
				columns: null,
				value: {
					field: null,
					value: null,
				},
			};
		},
		computed:
		{
			showInput()
			{
				return !(/IS NOT EMPTY|IS EMPTY/.test(this.filter.operation))
			},
		},
		methods:
		{
			changeInputValue(event)
			{
				this.value.value = event.target.value;
				this.saveValue();
			},
			changeFieldValue(field)
			{
				this.value.field = field;
				this.saveValue();
			},
			saveValue()
			{
				this.$emit('onChange', this.value);
			},
			async getColumns()
			{
				const columnsData = this.$store.getters.getColumns(this.settings.finalTableCode);
				const columns = Object.values(columnsData).map(el => el.field);

				this.$set(this, 'columns', columns);
			},
			setDefaultValue()
			{
				if (this.filter.value)
					this.$set(this, 'value', JSON.parse(JSON.stringify(this.filter.value)));
			},
		},
		async mounted()
		{
			this.getColumns();
			this.setDefaultValue();
		},
	};
</script>

<style lang="scss">
	.em-matrix-filter
	{
		display: flex;
	}
</style>
