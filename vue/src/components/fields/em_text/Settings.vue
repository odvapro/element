<template>
	<div class="settings-popup-row-params">
		<div class="el-empty">No settings</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">Cancel</button>
			<button @click="save()" class="el-btn">Save settigns</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['settings','isRequired'],
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
			 * Cancel editing settgins
			 */
			cancel()
			{
				this.$emit('cancel');
			},
			/**
			 * Save settings
			 */
			save()
			{
				this.$emit('save',{})
			},
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