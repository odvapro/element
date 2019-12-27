<template>
	<div class="loader">
		<svg width="20" height="20">
			<use xlink:href="#loader"></use>
		</svg>
		<span>{{$t('forms.loader.loading')}}</span>
	</div>
</template>
<script> export default {} </script>
<style lang="scss">
	.loader
	{
		display: flex;
		align-items: center;
		font-size:12px;
		svg{margin-right:10px;}
	}
</style>