<template>
	<div class="em-check-wrapper">
		<label class="em-check-label">
			<input type="checkbox" v-model="isChecked" @change="changeStatus()" class="em-check">
			<span>
				<svg width="7" height="7">
					<use xlink:href="#check"></use>
				</svg>
			</span>
		</label>
	</div>
</template>
<script>
	export default
	{
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
					url    : '/api/field/em_check/index/changeStatus/'
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
.em-check-wrapper
{
	display: block;
	width: 100%;
	text-align: center;
	.em-check-label
	{
		display: inline-block;
		position: relative;
		padding-left: 12px;
		font-size: 14px;
		height: 12px;
		color: #334D66;
		cursor: pointer;
	}
	.em-check
	{
		visibility: hidden;
		position: absolute;
	}
	.em-check:not(checked) + span
	{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 13px;
		height: 13px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		position: absolute;
		left: 0;
		transition: border 0.3s;
		background-color: #fff;
	}
	.em-check:checked + span
	{
		background: #7C7791;
		border: 1px solid #7C7791;
		background-repeat: no-repeat;
		background-size: contain;
		transition: background 0.3s;
		img
		{
			width: 7px;
			height: 7px;
			object-fit: contain;
		}
	}

	.em-check:checked:hover + span
	{
		transition: background 0.3s;
		border: 1px solid rgba(103, 115, 135, 0.5);
	}
	.em-check:not(checked):hover + span
	{
		border: 1px solid rgba(103, 115, 135, 0.8);
		transition: border 0.3s;
	}
}
</style>