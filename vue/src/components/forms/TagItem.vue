<template>
	<div class="table-tag-item-wrapper">
		<div class="table-tag-item" @click="openPopup">
			{{name}}
		</div>
		<div class="tag-search-popup-wrapper">
			<TagSearch v-if="showTagPopup"/>
		</div>
	</div>
</template>
<script>
	import TagSearch from '@/components/popups/TagSearch.vue';
	export default
	{
		components: {TagSearch},
		props: ['name'],
		data()
		{
			return {
				showTagPopup: false
			}
		},
		methods:
		{
			/**
			 * Открыть попап для поиска тегов
			 */
			openPopup()
			{
				this.showTagPopup = !this.showTagPopup;
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
	.tag-search-popup-wrapper
	{
		position: absolute;
		z-index: 4;
		background-color: #fff;
	}
</style>