<template>
		<component
			v-bind:is="columnContent"
			:fieldValue="params.value"
			:fieldSettings="params.settings"
			:mode="mode"
			:view="view"
			:isEditFieldPopup="isEditFieldPopup"
			@onChange="changeValue"
			@openEdit="openEdit"
			@closeEditFieldPopup="closeEditFieldPopup"
			@openEditFieldPopup="openEditFieldPopup"
		></component>
</template>
<script>
	import Vue from 'vue';
	export default
	{
		props: ['params','mode','view','fieldName'],
		computed:
		{
			/**
			 * Динамически отображаемая ячейка таблицы
			 */
			columnContent()
			{
				if(typeof this.params.settings.stylesCss != 'undefined' &&
				   this.params.settings.stylesCss !== false &&
				   window.importStyles.indexOf(this.fieldName) == -1)
				{
					var newSS       = document.createElement('style');
					newSS.innerHTML = this.params.settings.stylesCss;
					newSS.type      = 'text/css';
					document.getElementsByTagName("head")[0].appendChild(newSS);
					window.importStyles.push(this.fieldName);
				}

				if(this.params.settings.type == 'custom')
					return eval(this.params.settings.fieldJs);

				if (typeof this.fieldName == 'undefined' ||  this.fieldName === false)
					return false;

				return () => import(`@/components/fields/${this.fieldName}/Field.vue`);
			}
		},
		data()
		{
			return {
				isEditFieldPopup: false,
			}
		},
		methods:
		{
			/**
			 * Отслеживать изменения в филде
			 * @value {
			 *        value
			 *        settings (main format)
			 * }
			 */
			changeValue(value)
			{
				this.$emit('onChange', value);
			},

			/**
			 * Opens edit page
			 */
			openEdit()
			{
				this.$emit('openEdit');
			},
			closeEditFieldPopup()
			{
				this.isEditFieldPopup = false;
			},
			openEditFieldPopup()
			{
				this.isEditFieldPopup = true;
			}
		}
	}
</script>