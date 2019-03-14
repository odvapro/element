<template>
	<div class="settings-popup-row-params">
		<div class="settings-popup-item-wrapper">
			Required
		</div>
		<div class="settings-popup-item-wrapper">
			<div class="settings-popup-radio-wrapper">
				<div class="settings-popup-radio-btn" @click="setStatus(true)" :class="{active: required}">Yes</div>
				<div class="settings-popup-radio-btn" @click="setStatus(false)" :class="{active: !required}">No</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['isRequired'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				required: false
			}
		},
		methods:
		{
			/**
			 * Задать обязательность поля
			 */
			setStatus(status)
			{
				this.required = status;
				this.$emit('changeSettings', {required: status});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.required = this.isRequired;
			this.setStatus(this.required);
		}
	}
</script>