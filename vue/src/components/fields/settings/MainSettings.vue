<template>
	<div>
		<component
			:is="columnContent"
			:isRequired="params.required"
			:fieldSettings="params.settings"
			@changeSettings="changeSettings"
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
			 * Динамически отображаемый попап
			 */
			columnContent()
			{
				if (typeof this.params == 'undefined')
					return false;

				return () => import(`@/components/fields/settings/${this.params.fieldName}.vue`);
			}
		},
		methods:
		{
			/**
			 * Отслеживать изменения в филде
			 */
			changeSettings(value)
			{
				this.$emit('changeSettings', value);
			}
		}
	}
</script>