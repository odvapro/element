<template>
	<div class="props-popup">
		<div class="props-list">
			<div class="props-list-item">
				<div class="props-small-icon">
					<img src="/images/lines.svg" alt="">
				</div>
				<div>
					<div class="props-big-icon">
						<img src="/images/sharp.svg" alt="">
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
		</div>
	</div>
</template>
<script>
	import Checkbox from '@/components/forms/Checkbox.vue';
	export default
	{
		components: { Checkbox }
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
	.props-popup
	{
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		min-width: 193px;
	}
	.props-list-item
	{
		display: flex;
		height: 38px;
		align-items: center;
	}
	.props-big-icon
	{
		width: 14px;
		height: 14px;
	}
	.props-small-icon
	{
		width: 10px;
		height: 10px;
	}
</style>