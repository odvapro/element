<template>
	<div>
		<div class="settings-popup-row-params">
			<div class="settings-popup-item-wrapper">
				Required
			</div>
			<div class="settings-popup-item-wrapper">
				<div class="settings-popup-radio-wrapper">
					<div class="settings-popup-radio-btn" @click="settings.required = true" :class="{active: settings.required}">Yes</div>
					<div class="settings-popup-radio-btn" @click="settings.required = false" :class="{active: !settings.required}">No</div>
				</div>
			</div>
		</div>
		<div class="settings-popup-row-params">
			<div class="settings-popup-item-wrapper">
				Choose redactor
			</div>
			<div class="settings-popup-item-wrapper">
				<div class="settings-popup-radio-wrapper">
					<div class="settings-popup-radio-btn" @click="settings.textType = 'Text'" :class="{active: settings.textType == 'Text'}">Text</div>
					<div class="settings-popup-radio-btn" @click="settings.textType = 'Code'" :class="{active: settings.textType == 'Code'}">Code</div>
					<div class="settings-popup-radio-btn" @click="settings.textType = 'Visual'" :class="{active: settings.textType == 'Visual'}">Visual</div>
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
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				settings: {}
			}
		},
		watch:
		{
			'settings':
			{
				/**
				 * Мониторить изменения полей
				 */
				handler: function (val, oldVal)
				{
					this.$emit('changeSettings', this.settings);
				},
				deep: true
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			if (typeof this.settings.textType == 'undefined')
				this.$set(this.settings, 'textType',
				typeof this.fieldSettings.textType == 'undefined' ? 'Text' : this.fieldSettings.textType );

			if (typeof this.settings.required == 'undefined')
				this.$set(this.settings, 'required', this.isRequired);

			this.$emit('changeSettings', this.settings);
		}
	}
</script>