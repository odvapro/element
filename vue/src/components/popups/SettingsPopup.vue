<template>
	<div class="settings-popup-overlay">
		<div class="settings-popup-wrapper" v-click-outside="closePopup">
			<div class="settings-popup-title">Name field settings</div>
			<MainSettings
				v-if="popupParams.fieldName"
				:params="{fieldName: popupParams.fieldName, required: popupParams.required, settings: popupParams.settings}"
				@changeSettings="setSettingsForField"
			/>
			<!-- <div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Choose redactor
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="settings-popup-radio-wrapper">
						<div class="settings-popup-radio-btn active">Text</div>
						<div class="settings-popup-radio-btn">Code</div>
						<div class="settings-popup-radio-btn">Visual</div>
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Choose linked table
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="settings-popup-table-select-btn">
						<MainField
							:params="{
								fieldName : row[column.field].fieldName,
								value     : row[column.field].value,
								settings  : getFieldSettings(column, row)
							}"
						/>
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Choose search field
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="setting-popup-item-table-search">
						<input type="text" placeholder="set title">
					</div>
				</div>
			</div>
			<div class="settings-popup-row-params">
				<div class="settings-popup-item-wrapper">
					Data field sample
				</div>
				<div class="settings-popup-item-wrapper">
					<div class="setting-popup-item-input-wrapper">
						12/12/1991 12:30
					</div>
				</div>
			</div> -->
			<div class="settings-popup-bottom-btns">
				<button class="settings-popup-bottom-btn-cancel" @click="$store.commit('setActivePopup', false)">Cancel</button>
				<button class="settings-popup-bottom-btn-save" @click="submitSettings()">Save</button>
			</div>
			<div class="settings-popup-close-icon" @click="$store.commit('setActivePopup', false)"></div>
		</div>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	import MainSettings from '@/components/fields/settings/MainSettings.vue';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		mixins: [TableWork],
		components: { MainField, MainSettings },
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				popupParams: {},
				newSettings: {},
				curTable: {}
			}
		},
		methods:
		{
			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.$store.commit('setActivePopup', false);
			},
			/**
			 * Записать измененные настройки филда
			 */
			setSettingsForField(value)
			{
				this.newSettings = value;
			},
			/**
			 * Отправить настройки филда
			 */
			async submitSettings()
			{
				let qs = require('qs');

				if (typeof this.newSettings.list != 'undefined')
					for (let index = 0; index < this.newSettings.list.length; index ++)
						if (this.newSettings.list[index].key == '' || this.newSettings.list[index].value == '')
						{
							this.newSettings.list.splice(index, 1);
							index --;
						}

				let data = {
					tableName  : this.popupParams.settings.tableCode,
					columnName : this.popupParams.settings.fieldCode,
					fieldType  : this.popupParams.settings.fieldType,
					settings   : this.newSettings
				};

				let dataStr = qs.stringify(data);

				let result =  await this.$axios({
					method: 'POST',
					url: '/api/settings/setFieldSettings/',
					data: dataStr
				});

				if (!result.data.success)
					return false;

				for (let paramKey in this.newSettings)
				{
					this.curTable.columns[data.columnName].em[paramKey] = this.newSettings[paramKey];
					this.curTable.columns[data.columnName].em.settings[paramKey] = this.newSettings[paramKey];
				}

				this.closePopup();
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.popupParams = this.$store.state.settings.popupParams;
			this.curTable = this.getTableByCode(this.popupParams.settings.tableCode, this.$store.state.tables.tables);
		}
	}
</script>
<style lang="scss">
	.settings-popup__list-wrapper
	{
		display: flex;
	}
	.settings-popup-close-icon
	{
		width: 20px;
		height: 20px;
		position: absolute;
		top: 20px;
		right: 20px;
		cursor: pointer;
		&:before, &:after
		{
			width: calc(100% - 8px);
			height: 2px;
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			margin: auto;
			content: '';
			background-color: rgba(25, 28, 33, 0.7);
			transform: rotate(45deg);
		}
		&:after
		{
			transform: rotate(-45deg);
		}
	}
	.settings-popup-bottom-btns
	{
		display: flex;
		justify-content: center;
	}
	.settings-popup-bottom-btn-save
	{
		background: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		font-size: 12px;
		color: rgba(103, 115, 135, 0.7);
		padding: 8px 11px;
		border: none;
		cursor: pointer;
		color: #fff;
	}
	.settings-popup-bottom-btn-cancel
	{
		background: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		font-size: 12px;
		color: rgba(103, 115, 135, 0.7);
		padding: 8px 11px;
		border: none;
		margin-right: 15px;
		cursor: pointer;
	}
	.setting-popup-item-input-wrapper
	{
		font-size: 10px;
		color: #191C21;
	}
	.setting-popup-item-table-search
	{
		font-size: 10px;
		color: rgba(25, 28, 33, 0.4);
		input
		{
			border: none;
		}
	}
	.settings-popup-table-select-btn
	{
		padding: 4px 8px;
		position: relative;
		border-radius: 2px;
		display: inline-block;
		font-size: 10px;
		color: #7C7791;
	}
	.settings-popup-radio-btn
	{
		padding: 8px 9px;
		color: rgba(103, 115, 135, 0.7);
		cursor: pointer;
		&.active
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
	.settings-popup-radio-wrapper
	{
		display: inline-flex;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
	}
	.settings-popup-row-params
	{
		display: flex;
		align-items: center;
		margin-bottom: 20px;
		min-height: 31px;
	}
	.settings-popup-item-wrapper
	{
		font-size: 12px;
		color: #191C21;
		width: 175px;
	}
	.settings-popup-title
	{
		font-size: 16px;
		color: #191C21;
		font-family: $rMedium;
		margin-bottom: 24px;
	}
	.settings-popup-overlay
	{
		position: fixed;
		top: 0;
		left: 0;
		background: rgba(124, 119, 145, 0.1);
		width: 100%;
		height: 100%;
		z-index: 100;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.settings-popup-wrapper
	{
		position: relative;
		width: 565px;
		background: #FFFFFF;
		box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.08);
		border-radius: 2px;
		padding: 20px;
	}
</style>