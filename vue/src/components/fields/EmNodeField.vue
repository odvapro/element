<template>
	<div class="em-node__item-wrapper">
		<div class="em-node__item">
			{{fieldValue.name}}
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue']
	}
</script>
<style lang="scss">
	.em-node__item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
		cursor: pointer;
	}
</style>