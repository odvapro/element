<template>
	<div>
		<component
			v-bind:is="columnContent"
			:fieldValue="params.value"
			:fieldSettings="params.settings"
			:mode="mode"
			:view="view"
			@onChange="changeValue"
			@openEdit="openEdit"
		></component>
	</div>
</template>
<script>
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
				if (typeof this.fieldName == 'undefined' ||  this.fieldName === false )
					return false;

				return () => import(`@/components/fields/${this.fieldName}/Field.vue`);
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
			}
		}
	}
</script>