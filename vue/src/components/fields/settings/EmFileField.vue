<template>
	<div>
		<div class="settings-popup-row-params">
			<div class="settings-popup-item-wrapper">
				Required
			</div>
			<div class="settings-popup-item-wrapper">
				<div class="settings-popup-radio-wrapper">
					<div class="settings-popup-radio-btn" @click="setSettings(true)" :class="{active: settings.required}">Yes</div>
					<div class="settings-popup-radio-btn" @click="setSettings(false)" :class="{active: !settings.required}">No</div>
				</div>
			</div>
		</div>
		<div class="settings-popup-row-params">
			<div class="settings-popup-item-wrapper">
				File path
			</div>
			<div class="settings-popup-item-wrapper">
				<div class="setting-popup-item-table-search">
					<input type="text" v-model="settings.path" @keyup="setSettings(settings.required)" placeholder="path for uplaod">
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['isRequired', 'fieldSettings'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				settings: {}
			}
		},
		methods:
		{
			/**
			 * Задать обязательность поля
			 */
			setSettings(requiredStatus)
			{
				this.settings.required = requiredStatus;
				this.$emit('changeSettings', this.settings);
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			if (typeof this.settings.path == 'undefined')
				this.$set(
					this.settings,
					'path',
					typeof this.fieldSettings.path == 'undefined' ? 'public/images/upload' : this.fieldSettings.path
				);

			if (typeof this.settings.required == 'undefined')
				this.$set(this.settings, 'required', this.isRequired);

			this.setSettings(this.settings.required);
		}
	}
</script>