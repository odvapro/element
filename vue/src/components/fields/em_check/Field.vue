<template>
	<div class="em-check-wrapper">
		<Checkbox
			:checked.sync="isChecked"
			@change="changeStatus()"
		></Checkbox>
	</div>
</template>
<script>
	import Checkbox from '@/components/forms/Checkbox.vue';
	export default
	{
		components: {Checkbox},
		props: ['fieldValue', 'fieldSettings'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				isChecked: false
			}
		},
		methods:
		{
			/**
			 * Изменить статус
			 */
			async changeStatus()
			{
				let qs = require('qs');

				let data = qs.stringify({
					tableCode       : this.fieldSettings.tableCode,
					fieldCode       : this.fieldSettings.fieldCode,
					primaryKey      : this.fieldSettings.primaryKey.fieldCode,
					primaryKeyValue : this.fieldSettings.primaryKey.value,
					status          : this.isChecked
				});

				let result = await this.$axios({
					method : 'POST',
					data   : data,
					url    : '/field/em_check/index/changeStatus/'
				});

				if (!result.data.success)
					return false;

				this.$emit('onChange', {value: this.isChecked, settings: this.fieldSettings});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.isChecked = this.fieldValue;
		}
	}
</script>
<style lang="scss">
.em-check
{
	display: block;
	width: 100%;
	text-align: center;
}
</style>