<template>
	<div class="properties-popup">
		<div class="properties-list">
			<draggable
				@change=changeColumnOrder
				:list="localColumns"
			>
				<PropertyItem v-for="column in localColumns" :key="column.field" :column="column"/>
			</draggable>
		</div>
	</div>
</template>
<script>
	import PropertyItem from '@/components/forms/PropertyItem.vue';
	import draggable from 'vuedraggable';

	export default
	{
		props: ['columns'],
		components: { PropertyItem, draggable },
		data()
		{
			return {
				localColumns: [],
			};
		},
		watch:
		{
			columns:
			{
				handler(newValue)
				{
					this.updateModified();
				},
				deep: true,
			},
		},
		mounted()
		{
			this.updateModified();
			this.setLocalColumns();
		},
		methods:
		{
			changeColumnOrder(e)
			{
				if (!e.moved || !this.columns[e.moved.element.field]) return false;
				const columnsOrder = [];

				for (let [columnIndex, column] of Object.entries(this.localColumns))
					columnsOrder.push({
						code: column.field,
						order: columnIndex,
					});

				this.$emit('updateColumnsOrder', columnsOrder);
				return true;
			},
			setLocalColumns()
			{
				this.localColumns = Object.values(this.columns).sort((item1, item2) => {
					return item1.order - item2.order;
				});
			},
			updateModified()
			{
				let isModified = false;
				for (let column of Object.values(this.columns))
					if (!column.visible) { isModified = true; break; }

				this.$emit('updateModified', 'properties', isModified);
			},
		},
	};
</script>
<style lang="scss">
	.properties-popup
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		min-width: 200px;
		background-color: #fff;
		transform: translate(calc(50% - 40px));
	}
</style>
