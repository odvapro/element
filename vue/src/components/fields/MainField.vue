<template>
	<div>
		<component v-bind:is="columnContent" :fieldValue="fieldValue"></component>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue'],
		computed:
		{
			/**
			 * Динамически отображаемая ячейка таблицы
			 */
			columnContent()
			{
				if (typeof this.fieldValue == 'undefined')
					return false;

				return () => import(`@/components/fields/${this.fieldValue.fieldName}.vue`);
			}
		}
	}
</script>