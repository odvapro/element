<template>
	<div class="em-image-item-col" @click="togglePopup()">
		<div class="em-image-item-wrapper" v-for="item in fieldValue.value">
			<img :src="item.path" alt="">
		</div>
		<Upload v-if="showPopup" v-click-outside="closePopup"/>
	</div>
</template>
<script>
	import Upload from '@/components/popups/Upload.vue';
	export default
	{
		props: ['fieldValue'],
		components: {Upload},
		/**
		 * Глобальные переменные странциы
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
			 * Закрыть/Открыть попап
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