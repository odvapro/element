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
						<div class="settings-table-item-name">{{table.name}}</div>
					</div>
					<div class="settings-table-item">
						<div class="settings-table-item-flag">
							<!-- <MainField :fieldValue="{class: 'EmCheckField'}"/> -->
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
								:fieldValue="{fieldName: 'EmTagsField', value: !column.em.type ? column.type : column.em.type}"
							/>
						</div>
						<div class="settings-table-item centered">
							<button @click="$store.commit('setActivePopup', true)">settings</button>
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
	export default
	{
		mixins: [Popup],
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
						type      : typeof column.em.type == 'undefined' ? '' : column.em.type
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

				this.tables = JSON.parse(JSON.stringify(this.$store.state.tables.tables));

				for (let table of this.tables)
				{
					this.$set(table, 'showSettings', Object.assign({}, this.tableStyle));
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
	.settings-table-wrapper
	{
		padding-bottom: 100px;
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
</style>