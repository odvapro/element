<template>
	<div class="em-string-item-name-wrapper">
		<div class="em-string-item-overide-name">{{fieldValue.value}}</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue']
	}
</script>
<style>
	.em-string-item-overide-name
	{
		line-height: 12px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
	}
</style>