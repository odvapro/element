<template>
	<div class="em-text__item-name-wrapper">
		<div class="em-text__item-overide-name">{{ fieldValue }}</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings'],
	}
</script>
<style>
	.em-text__item-overide-name
	{
		line-height: 12px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
	}
</style>