<template>
	<div class="table-tag-item-wrapper">
		<div class="table-tag-item" @click="showTagPopup($event)">
			{{name}}
		</div>
	</div>
</template>
<script>
	import Popup from '@/mixins/popup.js';
	export default
	{
		props: ['name'],
		mixins: [Popup],
		methods:
		{
			/**
			 * Отобразить попап тегов
			 */
			showTagPopup(event)
			{
				this.showPopup(event.target.closest('.table-item'), 'TagSearch', 'left-top');
			}
		}
	}
</script>
<style lang="scss">
	.table-tag-item
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