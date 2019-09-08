<template>
	<div class="settings-table-wrapper">
		<div class="settings-empty-tables" v-if="tables.length < 1">
			No tables
		</div>
		<div class="settings-table-head" v-if="tables.length > 0">
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
							<input
								type="text"
								@change="saveTableSettings(table)"
								v-model="table.name"
								placeholder="Set Name"
							/>
						</div>
					</div>
					<div class="settings-table-item">
						<div class="settings-table-item-flag">
							<Checkbox
								:checked.sync="table.visible"
								@change="saveTableSettings(table)"
							></Checkbox>
						</div>
					</div>
					<div class="settings-table-item"></div>
				</div>
				<div class="settings-table-row-setting" id="settings-table-row" :style="table.showSettings">
					<div class="settings-table-row-setting-item active" v-for="column in table.columns">
						<div class="settings-table-item"> {{column.field}} </div>
						<div class="settings-table-item category-font">
							<input class="settings-table-input-name" type="text" v-model="column.em.name" @change="changeColumnName(table.code, column)" placeholder="Set Name">
						</div>
						<div class="settings-table-item centered">
							<Select
								:defaultText="column.em.type_info.name"
							>
								<SelectOption
									v-for="field in fieldTypes"
									@click.native="changeType({table: table.code, column: column.field, fieldType: field})"
									:key="field.code"
								>{{ field.name }}</SelectOption>
							</Select>
						</div>
						<div class="settings-table-item centered">
							<button
								class="settings-table__open-settings"
								@click="openSettingsPopup(table,column)"
								v-if="checkSettingComponent(table,column)"
							>settings</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<Popup :visible.sync="settingsPopup">
			<div class="popup__name">{{ settingsColumn.field }} settings</div>
			<component
				:is="settingsComponent"
				:settings="currentSettings"
				@save="saveSettings"
				@cancel="settingsPopup = false"
			></component>
		</Popup>
	</div>
</template>
<script>
	import qs from 'qs';
	import Select from '@/components/forms/Select.vue';
	import SelectOption from '@/components/forms/SelectOption.vue';
	import Checkbox from '@/components/forms/Checkbox.vue';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		mixins: [TableWork],
		components: {Select, Checkbox, SelectOption},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				fieldTypes:[],
				tables: [],
				tableStyle: {height: '0px', overflow: 'hidden'},

				settingsPopup:false,
				settingsFielType:false,
				currentSettings:false,
				settingsTable:false,
				settingsColumn:false,
			}
		},
		computed:
		{
			/**
			 * Field settings component
			 * gets settings component
			 * @return vue component or false
			 */
			settingsComponent()
			{
				if(this.settingsColumn === false)
					return false;

				// add styles
				if(typeof this.settingsColumn.em.stylesCss != 'undefined' &&
				   this.settingsColumn.em.stylesCss !== false &&
				   window.importStyles.indexOf(this.fieldName) == -1)
				{
					var newSS       = document.createElement('style');
					newSS.innerHTML = this.settingsColumn.em.stylesCss;
					newSS.type      = 'text/css';
					document.getElementsByTagName("head")[0].appendChild(newSS);
					window.importStyles.push(this.fieldName);
				}

				if(this.settingsColumn.em.type_info.type == 'custom' &&
				   this.settingsColumn.em.settingsJs != false)
				{
					return eval(this.settingsColumn.em.settingsJs);
				}


				if (this.settingsFielType == false)
					return false;

				return () => import(`@/components/fields/${this.settingsFielType}/Settings.vue`);
			},

			/**
			 * Достать колонки столбца
			 */
			getColumns()
			{
				return this.$store.state.tables.tableColumns;
			}
		},
		methods:
		{
			/**
			 * check exist settings component for field
			 */
			checkSettingComponent(table, column)
			{
				if(column.em.type_info.type == 'custom' && column.em.settingsJs != false)
					return true;

				try
				{
					require(`@/components/fields/${column.em.settings.code}/Settings.vue`)
				}
				catch (e)
				{
					return false;
				}

				return true;
			},

			/**
			 * Opens field settgins popups
			 */
			openSettingsPopup(table, column)
			{
				this.settingsTable    = table,
				this.settingsColumn   = column,
				this.settingsFielType = column.em.type_info.code;
				this.currentSettings  = column.em.settings;
				this.settingsPopup    = true;
			},

			/**
			 * Saves field settings
			 */
			async saveSettings(settings)
			{
				let data = {
					tableName  : this.settingsTable.code,
					columnName : this.settingsColumn.field,
					fieldType  : this.settingsFielType,
					settings   : settings
				};

				data = qs.stringify(data);
				let result = await this.$axios.post('/settings/setFieldSettings/',data);
				if(result.data.success)
				{
					this.settingsPopup = false;

					//TODO - rebuild
					//just set new settings in state
					for(let table of this.tables)
					{
						if(table.code != this.settingsTable.code)
							continue;
						for(let columnCode in table.columns)
						{
							if(columnCode == this.settingsColumn.field)
								table.columns[columnCode].em.settings = result.data.settings;
						}
					}
					this.ElMessage('😎 Settings saved!');
				}
			},

			/**
			 * Достать данные колонки
			 */
			getFieldSettings(table, column)
			{
				return this.$store.getters.getColumnSettings(table.code, column.field);
			},

			/**
			 * Изменение типа поля
			 */
			async changeType(values)
			{
				let requestChangeType = qs.stringify({
					tableName  : values.table,
					columnName : values.column,
					fieldType  : values.fieldType.code
				});

				let result = await this.$axios({
					method: 'post',
					url: '/settings/changeFieldType/',
					data: requestChangeType
				});

				if (!result.data.success)
					return false;

				let table = this.getTableByCode(values.table, this.tables);

				this.$set(table.columns[values.column], 'em', result.data.settings);
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
					url: '/settings/changeName/',
					data: data
				});

				if (!result.data.success)
					return false;
			},

			/**
			 * Set show setting for table
			 * set for default table view name and show props
			 */
			async saveTableSettings(table)
			{
				// defina default tview
				// check settings definition
				// set sibible settings
				let tview = false;
				for (var cTview of table.tviews)
					if (cTview.default === '1')
						tview = cTview;

				let data = qs.stringify({
					tviewId : tview.id,
					params  : {table:{visible:table.visible,name:table.name}}
				});

				let result = await this.$axios({
					method : 'POST',
					data   : data,
					url    : '/el/setTviewSettings/'
				});
			},

			/**
			 * Инициализация и преобразование массива таблицы
			 */
			async initTables()
			{
				let result = await this.$axios.get('/settings/getFiledTypes/');

				if(result.data.success)
					this.fieldTypes = result.data.types;

				this.tables = this.$store.state.tables.tables;

				for (let table of this.tables)
				{
					this.$set(table, 'showSettings', Object.assign({}, this.tableStyle));
				}
				this.$store.commit('showLoader',false);
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
	.settings-tab-wrapper {height: 100%; }
	.settings-empty-tables{font-size:14px; color:#191C21;}
	.settings-table-input-name
	{
		border: none;
		background-color: transparent;
		width: 100%;
		color: #191C21;
		&::placeholder{color: rgba(103, 115, 135, 0.4); }
	}
	.settings-table-item-img
	{
		margin-right: 11px;
		cursor: pointer;
		transition: all 0.3s;
		&.active {transform: rotate(90deg); }
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
			color: #191C21;
			&::placeholder{color: rgba(103, 115, 135, 0.4); }
		}
	}
	.settings-table-item-code
	{
		font-size: 12px;
		color: rgba(25, 28, 33, 0.7);
		cursor: pointer;
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
		min-width: 200px;
		width: 200px;
		color: rgba(25, 28, 33, 0.7);
		font-size: 12px;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&.table-item {height: 49px; }
		&:last-child {border-right: none; }
		&.centered {justify-content: center; }
		&.category-font {color: #191C21; }
		.settings-table__open-settings
		{
			color: rgba(25, 28, 33, 0.7);
			background-color: transparent;
			border: none;
			cursor: pointer;
			&:hover{text-decoration: underline;}
		}
	}
</style>