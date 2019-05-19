<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				Email
				<small class="popup__field-error">example</small>
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" placeholder="Enter email">
			</div>
		</div>
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