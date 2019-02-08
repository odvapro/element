<template>
	<div class="property-item-list-item" @click.stop>
		<div class="property-item-small-icon">
			<img src="/images/lines.svg" alt="">
		</div>
		<div>
			<div class="property-item-big-icon">
				<img :src="icon" alt="">
			</div>
		</div>
		<div class="property-item-names-wrapper">
			<div class="property-item-overide-name">Identifier</div>
			<div class="property-item-real-name">id</div>
		</div>
		<div class="property-item-checkbox-wrapper">
			<Checkbox :fieldValue="{value: false}"/>
		</div>
	</div>
</template>
<script>
	import Checkbox from '@/components/fields/EmCheck.vue';
	export default
	{
		props: ['icon'],
		components: { Checkbox },
	}
</script>
<style lang="scss">
	.property-item-small-icon,
	.property-item-big-icon
	{
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
</style>