<template>
	<div class="em-image-item-col">
		<div class="em-image-item-wrapper" v-for="item in fieldValue.value" @click.stop.prevent="showPopup($event.target.closest('.table-item'), 'Upload', 'left-top')">
			<img :src="item.path" alt="">
		</div>
	</div>
</template>
<script>
	import Popup from '@/mixins/popup.js';
	export default
	{
		mixins: [Popup],
		props: ['fieldValue']
	}
</script>
<style lang="scss">
	.em-image-item-col
	{
		display: flex;
		flex-wrap: wrap;
	}
	.em-image-item-wrapper
	{
		width: 14px;
		height: 14px;
		margin-right: 3px;
		cursor: pointer;
		margin-bottom: 3px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
</style>