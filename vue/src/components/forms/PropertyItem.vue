<template>
	<div class="property-item-list-item" @click.stop>
		<div class="property-item-small-icon">
			<svg width="6" height="5">
				<use xlink:href="#lines"></use>
			</svg>
		</div>
		<div>
			<div class="property-item-big-icon">
				<img :src="column.em.type_info.iconPath" alt="">
			</div>
		</div>
		<div class="property-item-names-wrapper">
			<div class="property-item-overide-name">{{column.field}}</div>
			<div class="property-item-real-name">{{column.field}}</div>
		</div>
		<div class="property-item-checkbox-wrapper">
			<div class="property-item-wrapper">
				<label class="property-item-label">
					<input type="checkbox" v-model="column.visible" class="property-item__checkbox">
					<span>
						<svg width="7" height="7">
							<use xlink:href="#check"></use>
						</svg>
					</span>
				</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['column'],
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
	}
	.property-item-overide-name
	{
		color: #677387;
		font-size: 12px;
		padding-right: 20px;
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
	.property-item-wrapper
	{
		display: inherit;
		.property-item-label
		{
			display: inline-block;
			position: relative;
			padding-left: 13px;
			font-size: 14px;
			height: 12px;
			color: #334D66;
			cursor: pointer;
		}
		.property-item__checkbox
		{
			visibility: hidden;
			position: absolute;
		}
		.property-item__checkbox:not(checked) + span
		{
			display: flex;
			align-items: center;
			justify-content: center;
			width: 13px;
			height: 13px;
			border: 1px solid rgba(103, 115, 135, 0.4);
			border-radius: 2px;
			position: absolute;
			left: 0;
			transition: border 0.3s;
			background-color: #fff;
		}
		.property-item__checkbox:checked + span
		{
			background: #7C7791;
			border: 1px solid #7C7791;
			background-repeat: no-repeat;
			background-size: contain;
			transition: background 0.3s;
			img
			{
				width: 7px;
				height: 7px;
				object-fit: contain;
			}
		}

		.property-item__checkbox:checked:hover + span
		{
			transition: background 0.3s;
			border: 1px solid rgba(103, 115, 135, 0.5);
		}
		.property-item__checkbox:not(checked):hover + span
		{
			border: 1px solid rgba(103, 115, 135, 0.8);
			transition: border 0.3s;
		}
	}
</style>