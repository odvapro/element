<template>
	<div>
		<component
			v-bind:is="columnContent"
			:fieldValue="params.value"
			:fieldSettings="params.settings"
			@onChange="changeValue"
		></component>
	</div>
</template>
<script>
	export default
	{
		props: ['params'],
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
			 */
			changeValue(value)
			{
				this.$emit('onChange', value);
			}
		}
	}
</script>