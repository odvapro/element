<template>
	<div class="confirm-popup__wrapper">
		<div class="confirm-popup__text">{{title}}</div>
		<div class="confirm-popup__btns">
			<button class="el-btn confirm-popup__right-margin" @click="response(false)">Нет</button>
			<button class="el-gbtn" @click="response(true)">Да</button>
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
				title: 'Вы уверены ?'
			}
		},
		methods:
		{
			/**
			 * Отдать овет
			 */
			response(answer)
			{
				this.$emit('response', answer);
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