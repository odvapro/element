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
		props: ['params','mode','view'],
		computed:
		{
			/**
			 * Динамически отображаемая ячейка таблицы
			 */
			columnContent()
			{
				if (typeof this.params == 'undefined')
					return false;

				return () => import(`@/components/fields/${this.params.fieldName}/Field.vue`);
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