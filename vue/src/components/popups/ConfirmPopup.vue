<template>
	<div class="confirm-popup">
		<div class="confirm-popup__text">{{title}}</div>
		<div class="confirm-popup__btns">
			<button class="el-btn confirm-popup__right-margin" @click="$emit('response', false)">{{$t('no')}}</button>
			<button class="el-gbtn" @click="$emit('response', true)">{{$t('yes')}}</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['text'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				title: this.$t('popups.confirmPopup.are_you_sure')
			}
		},
		/**
		 * Хук при рендере компонента
		 */
		mounted()
		{
			if (typeof  this.text == 'undefined')
				return false;

			if (this.text != '')
				this.title = this.text;
		}
	}
</script>
<style>
	.confirm-popup__text
	{
		color: grey;
	}
	.confirm-popup__btns
	{
		text-align: right;
	}
	.confirm-popup__right-margin
	{
		margin-right: 10px;
	}
</style>