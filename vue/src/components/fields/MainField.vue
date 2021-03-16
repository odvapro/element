<template>
	<component
		v-bind:is="columnContent"
		:fieldValue.sync="params.value"
		:fieldSettings="params.settings"
		:mode="mode"
		:view="view"
		@onChange="changeValue"
		@openEdit="openEdit"
	></component>
</template>
<script>
	import Vue from 'vue';
	export default
	{
		props: {
			params:{type: Object, required: true },
			mode:{type: String, required: true },
			view:{type: String, required: true },
			fieldName:{type: String, required: true }
		},
		computed:
		{
			/**
			 * Динамически отображаемая ячейка таблицы
			 */
			columnContent()
			{
				if (typeof this.fieldName == 'undefined' || this.fieldName === false)
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
