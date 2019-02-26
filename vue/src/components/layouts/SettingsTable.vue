<template>
	<div class="settings-table-wrapper">
		<div class="settings-table-head">
			<div class="settings-table-row-data">
				<div class="settings-table-item">
					<div class="settings-table-item-title">Code</div>
				</div>
				<div class="settings-table-item">
					<div class="settings-table-item-title">Name</div>
				</div>
				<div class="settings-table-item">
					<div class="settings-table-item-title">Show</div>
				</div>
				<div class="settings-table-item"></div>
			</div>
			<div class="settings-table-row" v-for="table in tables">
				<div class="settings-table-row-data">
					<div class="settings-table-item"
						@click="toggleSettingsRow(table)">
						<svg
							width="7"
							height="13"
							class="settings-table-item-img"
							:class="{active: table.showSettings.overflow == 'visible' || table.showSettings.overflow == 'auto'}"
						>
							<use xlink:href="#arrow"></use>
						</svg>
						<div class="settings-table-item-code">{{table.code}}</div>
					</div>
					<div class="settings-table-item">
						<div class="settings-table-item-name">
							<input type="text" @change="setTviewSetting(table, 'table', {name: table.name})" v-model="table.name">
						</div>
					</div>
					<div class="settings-table-item">
						<div class="settings-table-item-flag">
							<div class="settings-table__check-wrapper">
								<label class="settings-table__check-label">
									<input type="checkbox" v-model="table.visible" @change="setTviewSetting(table, 'table', {visible: String(table.visible)})" class="settings-table__check">
									<span>
										<svg width="7" height="7">
											<use xlink:href="#check"></use>
										</svg>
									</span>
								</label>
							</div>
						</div>
					</div>
					<div class="settings-table-item"></div>
				</div>
				<div class="settings-table-row-setting" id="settings-table-row" :style="table.showSettings">
					<div class="settings-table-row-setting-item active" v-for="column in table.columns">
						<div class="settings-table-item">
							{{column.field}}
						</div>
						<div class="settings-table-item category-font">
							<input class="settings-table-input-name" type="text" v-model="column.em.name" @change="changeColumnName(table.code, column)">
						</div>
						<div class="settings-table-item table-item centered">
							<MainField
								:params="{
									fieldName : 'EmTagsField',
									value     : column.em.type_info.name,
									settings  : getFieldSettings(table, column)
								}"
								@onChange="changeType"
							/>
						</div>
						<div class="settings-table-item centered">
							<button @click="setSettingsPopupParams({fieldName: column.em.type_info.fieldComponent, required: column.em.required, settings: column.em.settings})">settings</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	import Popup from '@/mixins/popup.js';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		mixins: [Popup, TableWork],
		components: { MainField },
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				tableColumns: {},
				fieldTypes:[],
				tables: [],
				tableStyle:
				{
					height: '0px',
					overflow: 'hidden'
				}
			}
		},
		methods:
		{
			/**
			 * Передать параметры филда в попап и открыть
			 */
			setSettingsPopupParams(params)
			{
				this.$store.commit('setPopupParams', params);
				this.$store.commit('setActivePopup', true);
			},
			/**
			 * Достать данные колонки
			 */
			getFieldSettings(table, column)
			{
				let columns = table.columns;
				let primaryFieldCode = false;

				for(let columnCode in columns)
				{
					let column = columns[columnCode];
					if(column.key == 'PRI')
					{
						primaryFieldCode = columnCode;
						break;
					}
				}

				let primaryKey = {
					value: '',
					fieldCode: primaryFieldCode
				};

				let settings        = column.em.settings;
				settings.fieldCode  = column.field;
				settings.tableCode  = table.code;
				settings.fieldType  = column.em.type_info.code;
				settings.primaryKey = primaryKey;
				settings.values     = this.fieldTypes;

				return settings;
			},
			/**
			 * Изменение типа поля
			 */
			async changeType(values)
			{
				let qs = require('qs');

				let requestChangeType = qs.stringify({
					tableName: values.table,
					columnName: values.column,
					fieldType: values.data.code
				});

				let result = await this.$axios({
					method: 'post',
					url: '/api/settings/changeFieldType/',
					data: requestChangeType
				});

				if (!result.data.success)
					return false;

				for (let table of this.tables)
				{
					if (table.code != values.table)
						continue;

					table.columns[values.column].em.type_info = JSON.parse(JSON.stringify(values.data));
					table.columns[values.column].em.type = values.data.code;
					break;
				}
			},
			/**
			 * Анимация для открытия и закрытия аккордеона
			 */
			toggleSettingsRow(table)
			{
				if (table.showSettings.overflow == 'visible')
				{
					table.showSettings.overflow = 'hidden';
					table.showSettings.height = '0';
					return false;
				}

				var height = 0;

				for (var col in table.columns)
					height += 49;

				table.showSettings.height = height + 'px';
				table.showSettings.overflow = 'auto';

				setTimeout(function ()
				{
					table.showSettings.overflow = 'visible';
				}, 300);
			},
			/**
			 * Открыть/закрыть настройки колонок
			 */
			async setRowSetting(table)
			{
				table.showSettings = !table.showSettings;
			},
			/**
			 * Переопределить имя колонки
			 */
			async changeColumnName(tableName, column)
			{
				var qs   = require('qs'),
					data = qs.stringify({
						tableName : tableName,
						field     : column.field,
						name      : column.em.name,
						type      : column.em.type_info.code
					});

				var result = await this.$axios({
					method: 'POST',
					url: '/api/settings/changeName/',
					data: data
				});

				if (!result.data.success)
					return false;
			},
			/**
			 * Инициализация и преобразование массива таблицы
			 */
			async initTables()
			{
				let result = await this.$axios.get('/api/settings/getFiledTypes/');

				if(result.data.success)
					this.fieldTypes = result.data.types;

				this.tables = this.$store.state.tables.tables;

				for (let table of this.tables)
				{
					let tview = this.getDefaultTview(table);

					this.$set(table, 'showSettings', Object.assign({}, this.tableStyle));
					this.$set(table, 'visible', typeof tview.settings.table == 'undefined' ? false : tview.settings.table.visible === 'true' ? true : false);
				}
			}
		},
		computed:
		{
			/**
			 * Достать колонки столбца
			 */
			getColumns()
			{
				return this.$store.state.tables.tableColumns;
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.initTables();
		}
	}
</script>
<style lang="scss">
	.settings-tab-wrapper
	{
		height: 100%;
	}
	.settings-table-input-name
	{
		border: none;
		background-color: transparent;
		width: 100%;
	}
	.settings-table-item-img
	{
		margin-right: 11px;
		cursor: pointer;
		transition: all 0.3s;
		&.active
		{
			transform: rotate(90deg);
		}
	}
	.settings-table-item-flag
	{
		display: flex;
		width: 100%;
		justify-content: center;
	}
	.settings-table-item-name
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
		input
		{
			height: 100%;
			box-sizing: border-box;
			width: 100%;
			border: none;
		}
	}
	.settings-table-item-code
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
	}
	.settings-table-item-title
	{
		color: #677387;
		font-size: 12px;
	}
	.settings-table-row-setting
	{
		transition: all 0.3s;
	}
	.settings-table-row-setting-item
	{
		display: flex;
		background: rgba(103, 115, 135, 0.1);
		transition: all 0.3s;
		height: 0;
		padding: 0 8px;
		.settings-table-item
		{
			border-right: none;
		}
		.settings-table-item:first-child
		{
			padding-left: 30px;
		}
		&.active
		{
			height: 49px;
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
	}
	.settings-table-row-data
	{
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		padding: 0 8px;
	}
	.settings-table-row
	{
		display: flex;
		flex-direction: column;
		position: relative;
		.settings-table-row-setting-item.active:first-child
		{
			border-top: none;
		}
		.settings-table-row-setting-item.active:last-child
		{
			border-bottom: none;
		}
		&:last-child
		{
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
	}
	.settings-table-item
	{
		display: flex;
		word-break: break-word;
		align-items: center;
		height: 49px;
		padding: 0 11px;
		position: relative;
		min-width: 140px;
		width: 140px;
		color: rgba(25, 28, 33, 0.7);
		font-size: 12px;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&.table-item
		{
			height: 49px;
		}
		&:last-child
		{
			border-right: none;
		}
		&.centered
		{
			justify-content: center;
		}
		&.category-font
		{
			color: #191C21;
		}
		button
		{
			color: rgba(25, 28, 33, 0.7);
			background-color: transparent;
			border: none;
			cursor: pointer;
		}
	}
.settings-table__check-wrapper
{
	display: inherit;
	.settings-table__check-label
	{
		display: inline-block;
		position: relative;
		padding-left: 13px;
		font-size: 14px;
		height: 12px;
		color: #334D66;
		cursor: pointer;
	}
	.settings-table__check
	{
		visibility: hidden;
		position: absolute;
	}
	.settings-table__check:not(checked) + span
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
	.settings-table__check:checked + span
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

	.settings-table__check:checked:hover + span
	{
		transition: background 0.3s;
		border: 1px solid rgba(103, 115, 135, 0.5);
	}
	.settings-table__check:not(checked):hover + span
	{
		border: 1px solid rgba(103, 115, 135, 0.8);
		transition: border 0.3s;
	}
}
</style>