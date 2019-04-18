<template>
	<div>
		<component
			v-bind:is="columnContent"
			:fieldValue="params.value"
			:fieldSettings="params.settings"
			:mode="mode"
			@onChange="changeValue"
		></component>
	</div>
</template>
<script>
	export default
	{
		props: ['params','mode'],
		computed:
		{
			/**
			 * Динамически отображаемая ячейка таблицы
			 */
			columnContent()
			{
				if (typeof this.params == 'undefined')
					return false;

				return () => import(`@/components/fields/${this.params.fieldName}.vue`);
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
			}
		}
	}
</script>