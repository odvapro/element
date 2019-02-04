<template>
	<div class="props-popup">
		<div class="props-list">
			<PropertyItem icon="/images/sharp.svg"/>
			<PropertyItem icon="/images/price.svg"/>
			<PropertyItem icon="/images/category.svg"/>
			<PropertyItem icon="/images/sharp.svg"/>
		</div>
	</div>
</template>
<script>
	import PropertyItem from '@/components/forms/PropertyItem.vue';
	import PopupParams from '@/mixins/popupParams.js';
	export default
	{
		mixins: [PopupParams],
		components: { PropertyItem }
	}
</script>
<style lang="scss">
	.props-popup
	{
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		min-width: 191px;
		background-color: #fff;
	}
</style>