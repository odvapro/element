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
				List
			</div>
			<div class="settings-popup-item-wrapper">
				<div class="settings-popup__list-wrapper" v-for="itemList in settings.list">
					<input type="text" v-model="itemList.key" placeholder="key">
					<input type="text" v-model="itemList.value" placeholder="value">
				</div>
				<button @click="addListItem()">добавить</button>
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
				 * Мониторить изменения настроек
				 */
				handler: function (argument)
				{
					this.$emit('changeSettings', this.settings);
				},
				deep: true
			}
		},
		methods:
		{
			/**
			 * Добавить поле в список
			 */
			addListItem()
			{
				this.settings.list.push({key: '', value: ''});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			if (typeof this.settings.list == 'undefined')
				this.$set(this.settings, 'list', typeof this.fieldSettings.list == 'undefined' ? [{key: '', value: ''}] : this.fieldSettings.list);

			if (typeof this.settings.required == 'undefined')
				this.$set(this.settings, 'required', this.isRequired);
			this.$emit('changeSettings', this.settings);
		}
	}
</script>