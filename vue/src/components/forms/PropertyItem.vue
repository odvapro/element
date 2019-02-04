<template>
	<div class="props-list-item" @click.stop>
		<div class="props-small-icon">
			<img src="/images/lines.svg" alt="">
		</div>
		<div>
			<div class="props-big-icon">
				<img :src="icon" alt="">
			</div>
		</div>
		<div class="props-names-wrapper">
			<div class="props-overide-name">Identifier</div>
			<div class="props-real-name">id</div>
		</div>
		<div class="props-checkbox-wrapper">
			<Checkbox/>
		</div>
	</div>
</template>
<script>
	import Checkbox from '@/components/forms/Checkbox.vue';
	export default
	{
		props: ['icon'],
		components: { Checkbox },
	}
</script>
<style lang="scss">
	.props-small-icon,
	.props-big-icon
	{
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	.props-list-item
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
	.props-big-icon
	{
		width: 14px;
		height: 14px;
		margin-right: 6px;
	}
	.props-small-icon
	{
		width: 6px;
		height: 14px;
		margin-right: 8px;
	}
	.props-overide-name
	{
		color: #677387;
		font-size: 12px;
		padding-right: 20px;
	}
	.props-real-name
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		padding-right: 20px;
	}
	.props-checkbox-wrapper
	{
		position: absolute;
		right: 10px;
	}
</style>