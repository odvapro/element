<template>
	<div @click="closePopups">
		<div class="settings-popup-row-params">
			<div class="settings-popup-item-wrapper">
				Required
			</div>
			<div class="settings-popup-item-wrapper">
				<div class="settings-popup-radio-wrapper">
					<div class="settings-popup-radio-btn" @click="settings.required = true" :class="{active: settings.required}">Yes</div>
					<div class="settings-popup-radio-btn" @click="settings.required = false" :class="{active: !settings.required}">No</div>
				</div>
			</div>
		</div>
		<div class="settings-popup-row-params">
			<div class="settings-popup-item-wrapper">Table</div>
			<div class="em-node__table-select em-node-field__position__revative">
				<div class="em-node-item-wrapper">
					<div class="em-node-item" @click.stop="togglePopup('showTablePopup')">
						{{settings.bindTable}}
					</div>
				</div>
				<div class="em-node-search" v-if="showTablePopup">
					<div class="em-node-search-popup-head">
						<div class="em-node-search-item">
							{{settings.bindTable}}
						</div>
					</div>
					<div class="em-node-search-popup-item" v-for="table in tables">
						<div class="em-node-search-icon">
							<svg width="6" height="5">
								<use xlink:href="#lines"></use>
							</svg>
						</div>
						<div class="em-node-search-item" @click="selectTable(table)">
							{{table.code}}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="settings-popup-row-params" v-if="selectedTable.code">
			<div class="settings-popup-item-wrapper">Bind field</div>
			<div class="em-node__table-select em-node-field__position__revative">
				<div class="em-node-item-wrapper">
					<div class="em-node-item" @click.stop="togglePopup('showBindPopup')">
						{{settings.bindField}}
					</div>
				</div>
				<div class="em-node-search" v-if="showBindPopup">
					<div class="em-node-search-popup-head">
						<div class="em-node-search-item">
							{{settings.bindField}}
						</div>
					</div>
					<div class="em-node-search-popup-item" v-for="column in tableColumns">
						<div class="em-node-search-icon">
							<svg width="6" height="5">
								<use xlink:href="#lines"></use>
							</svg>
						</div>
						<div class="em-node-search-item" @click="selectBindField(column)">
							{{column.field}}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="settings-popup-row-params" v-if="selectedTable.code">
			<div class="settings-popup-item-wrapper">Bind field</div>
			<div class="em-node__table-select em-node-field__position__revative">
				<div class="em-node-item-wrapper">
					<div class="em-node-item" @click.stop="togglePopup('showSearchPopup')">
						{{settings.searchField}}
					</div>
				</div>
				<div class="em-node-search" v-if="showSearchPopup">
					<div class="em-node-search-popup-head">
						<div class="em-node-search-item">
							{{settings.searchField}}
						</div>
					</div>
					<div class="em-node-search-popup-item" v-for="column in tableColumns">
						<div class="em-node-search-icon">
							<svg width="6" height="5">
								<use xlink:href="#lines"></use>
							</svg>
						</div>
						<div class="em-node-search-item" @click="selectSearchField(column)">
							{{column.field}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['isRequired', 'fieldSettings'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				settings        : {},
				showTablePopup  : false,
				showBindPopup   : false,
				showSearchPopup : false,
				selectedTable   : {}
			}
		},
		watch:
		{
			'settings':
			{
				/**
				 * Мониторить изменения полей
				 */
				handler: function (val, oldVal)
				{
					this.$emit('changeSettings', this.settings);
				},
				deep: true
			}
		},
		computed:
		{
			/**
			 * Достать все таблицы из стора
			 */
			tables()
			{
				return this.$store.state.tables.tables;
			},
			/**
			 * Отобразить колонки выбранной таблицы
			 */
			tableColumns()
			{
				if (!this.settings.bindTable)
					return [];

				return this.selectedTable.columns;
			}
		},
		methods:
		{
			/**
			 * Выбрать таблицу
			 */
			selectTable(table)
			{
				this.settings.bindTable   = table.code;
				this.settings.bindField   = table.columns['id'].field;
				this.settings.searchField = table.columns['id'].field;
				this.selectedTable        = table;
			},
			/**
			 * Открыть/Закрыть попап
			 */
			togglePopup(popup)
			{
				this.closePopups();
				this[popup] = !this[popup];
			},
			/**
			 * Выбрать поле привязки
			 */
			selectBindField(column)
			{
				this.settings.bindField = column.field;
			},
			/**
			 * Выбрать поле поиска
			 */
			selectSearchField(column)
			{
				this.settings.searchField = column.field;
			},
			/**
			 * Закрыть попап
			 */
			closePopups()
			{
				this.showTablePopup  = false;
				this.showBindPopup   = false;
				this.showSearchPopup = false;
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			if (typeof this.settings.bindTable == 'undefined')
			{
				this.$set(this.settings, 'bindTable', typeof this.fieldSettings.bindTable == 'undefined' ? 'Не выбрано' : this.fieldSettings.bindTable );

				this.$set(this.settings, 'bindField', typeof this.fieldSettings.bindField == 'undefined' ? 'Не выбрано' : this.fieldSettings.bindField );

				this.$set(this.settings, 'searchField', typeof this.fieldSettings.searchField == 'undefined' ? 'Не выбрано' : this.fieldSettings.searchField );

				if (typeof this.fieldSettings.bindTable != 'undefined')
				{
					for (let table of this.tables)
					{
						if (this.fieldSettings.bindTable == table.code)
							this.selectedTable = table;
					}
				}
			}

			if (typeof this.settings.required == 'undefined')
				this.$set(this.settings, 'required', this.isRequired);
		}
	}
</script>
<style>
	.em-node-field__position__revative
	{
		position: relative;
	}
	.em-node-item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
		cursor: pointer;
	}
	.em-node-search-popup-head
	{
		height: 49px;
		display: flex;
		align-items: center;
		padding: 0 9px;
		font-size: 10px;
		background-color: rgba(103, 115, 135, 0.1);
		color: rgba(25, 28, 33, 0.4);
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
	}
	.em-node-search
	{
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		width: 193px;
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background: white;
		position: absolute;
		top: -1px;
		background: white;
		z-index: 2;
		left: -1px;
	}
	.em-node-search-icon
	{
		width: 6px;
		height: 14px;
		display: flex;
		align-items: center;
		margin-right: 8px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	.em-node-search-item
	{
		padding: 4px 8px;
		background-color: rgba(124, 119, 145, 0.1);
		border-radius: 2px;
		font-size: 10px;
		margin-right: 2px;
		color: #7C7791;
		position: relative;
	}
	.em-node-search-popup-item
	{
		display: flex;
		padding: 0 9px;
		align-items: center;
		height: 30px;
		cursor: pointer;
		&:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
</style>