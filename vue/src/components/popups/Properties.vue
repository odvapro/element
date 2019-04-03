<template>
	<div class="properties-popup">
		<div class="properties-list">
			<PropertyItem v-for="column in columns" :column="column"/>
		</div>
	</div>
</template>
<script>
	import PropertyItem from '@/components/forms/PropertyItem.vue';
	export default
	{
		props: ['columns'],
		components: { PropertyItem }
	}
</script>
<style lang="scss">
	.properties-popup
	{
		position: absolute;
		top: calc(100% + 5px);
		left: 0;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		min-width: 193px;
		background-color: #fff;
	}
</style>