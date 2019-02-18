<template>
	<div class="em-tags-wrapper" @click="togglePopup()">
		<TagItem :name="fieldValue.value"/>
		<TagSearch v-if="showPopup" v-click-outside="closePopup"/>
	</div>
</template>
<script>
	import TagSearch from '@/components/popups/TagSearch.vue';
	import TagItem from '@/components/forms/TagItem.vue';
	export default
	{
		components: {TagItem, TagSearch},
		props: ['fieldValue'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				showPopup: false
			}
		},
		methods:
		{
			/**
			 * Открыть/Закрыть попап
			 */
			togglePopup()
			{
				this.showPopup = !this.showPopup;
			},
			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.showPopup = false;
			}
		}
	}
</script>