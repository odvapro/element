<template>
	<div class="property-item-list-item" @click.stop>
		<div class="property-item-small-icon">
			<svg width="6" height="5">
				<use xlink:href="#lines"></use>
			</svg>
		</div>
		<div>
			<div class="property-item-big-icon">
				<img :src="require(`@/assets${column.em.type_info.iconPath}`)" alt="">
			</div>
		</div>
		<div class="property-item-names-wrapper">
			<div class="property-item-overide-name">{{ getColName(column) }}</div>
			<div class="property-item-real-name">{{ column.field }}</div>
		</div>
		<div class="property-item-checkbox-wrapper">
			<Checkbox :checked.sync="column.visible"></Checkbox>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['column'],
		methods:
		{
			getColName(column)
			{
				if(typeof column.em == 'undefined' || !column.em.name)
					return column.field;
				return column.em.name;
			}
		}
	}
</script>
<style lang="scss">
	.property-item-small-icon,
	.property-item-big-icon
	{
		display: flex;
		align-items: center;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	.property-item-list-item
	{
		display: flex;
		height: 40px;
		align-items: center;
		padding: 0 9px;
		&:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
	.property-item-big-icon
	{
		width: 14px;
		height: 14px;
		margin-right: 6px;
	}
	.property-item-small-icon
	{
		width: 6px;
		height: 14px;
		margin-right: 8px;
		cursor: grab;
	}
	.property-item-names-wrapper { cursor: default; }
	.property-item-overide-name
	{
		color: #677387;
		font-size: 12px;
		padding-right: 20px;
	    text-transform: capitalize;
	}
	.property-item-real-name
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		padding-right: 20px;
	}
	.property-item-checkbox-wrapper
	{
		position: absolute;
		right: 10px;
	}
</style>
