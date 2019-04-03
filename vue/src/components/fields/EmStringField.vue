<template>
	<div class="em-string">
		{{ fieldValue }}
		<span v-if="!fieldValue" class="table__empty-field">Empty</span>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings'],
	}
</script>
<style>
	.em-string
	{
		line-height: 49px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		position: absolute;
		width:100%;
		left:0px;
		top:0px;
		height: 100%;
		padding-left: 10px;
		padding-right: 10px;
	}
</style>