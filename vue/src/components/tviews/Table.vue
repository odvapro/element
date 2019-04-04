<template>
	<div class="table-vertical-scroll"
		@mousemove="resizeColumn($event, columnDrug.col)"
		@mouseup="endResize($event, columnDrug.col)">
		<div class="table__min-width" :style="{'min-width': getTableMinWidth + 'px'}">
			<div class="table-row no-hover">
				<div
					class="table-item"
					v-for="column in table.columns"
					v-if="column.visible"
					:style="{ width: column.width + 'px', 'min-width': column.width + 'px' }"
				>
					<div class="table-item-img">
						<img :src="column.em.type_info.iconPath" alt="">
					</div>
					<div class="table-item-name-wrapper">
						<div class="table-item-overide-name">{{getOverideName(column)}}</div>
						<div class="table-item-real-name">{{column.field}}</div>
					</div>
					<div class="drug-col" @mousedown="reginsterEventResize($event, column)"></div>
				</div>
				<div class="table-item">
					<div class="table__add-column-item">
						<div class="table__add-col-img">
							<svg width="12" height="12">
								<use xlink:href="#plus-white"></use>
							</svg>
						</div>
						<span class="table__add-col-label">Add field</span>
					</div>
				</div>
			</div>
			<div class="table-row" v-for="(row, rowIndex) in tableContent.items">
				<div class="table-overlay-row">
					<button class="table-row-btn">edit</button>
					<button class="table-row-btn">remove</button>
				</div>
				<div

					class="table-item"
					v-for="column, index in table.columns"
					v-if="column.visible && row[column.field]"
					:style="{width: column.width + 'px', 'min-width': column.width + 'px'}"
				>
					<MainField
						:params="{
							fieldName : row[column.field].fieldName,
							value     : row[column.field].value,
							settings  : getFieldSettings(column, row)
						}"
						@onChange="changeTableValue"
					/>
				</div>
				<div class="table-item">
					<div class="table-empty-col"></div>
				</div>
			</div>
		</div>
		<Pagination
			v-if="tableContent.total_pages > 1"
			:maxPage="tableContent.total_pages"
			:current="tableContent.current"
			@change="selectPage"
		/>
	</div>
</template>
<script>
	import EmCheck from '@/components/fields/EmCheckField.vue';
	import MainField from '@/components/fields/MainField.vue';
	import Pagination from '@/components/layouts/Pagination.vue';
	import TagItem from '@/components/forms/TagItem.vue';
	import Popup from '@/mixins/popup.js';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		props:['table', 'tview'],
		mixins: [Popup, TableWork],
		components: {MainField, EmCheck, TagItem, Pagination},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				columnDrug:
				{
					isDrug: false,
					posX: 0,
					start: 0,
					top: 0,
					width: 0,
					col: ''
				},
				openProperties: false
			}
		},
		computed:
		{
			/**
			 * Достать из стора содержимое таблицы
			 */
			tableContent()
			{
				return this.$store.state.tables.tableContent;
			},
			/**
			 * Определить минимальную ширину таблицу
			 */
			getTableMinWidth()
			{
				let sum = 0;

				for (let column in this.table.columns)
					sum += +this.table.columns[column].width;

				return sum + 300;
			},
			/**
			 * Достать название таблицы из урла
			 */
			getUrlTableName()
			{
				return this.$route.params.tableCode;
			}
		},
		watch:
		{
			/**
			 * отслеживать изменение колонки
			 */
			'table.columns':
			{
				/**
				 * Следить за изменением всего объекта
				 */
				handler: function (val, oldVal)
				{
					if (this.columnDrug.isDrug == false)
						this.saveColumnsParams();
				},
				deep: true
			},
			/**
			 * Если меняется урл то выполнять
			 */
			'$route.fullPath'()
			{
				this.setDefaulParams();
				this.getTableContent();
			}
		},
		methods:
		{
			/**
			 * Сохранить локально измененные данные в таблице
			 */
			changeTableValue(tableValues)
			{
				this.setTableValue(
					tableValues.settings.primaryKey.fieldCode,
					tableValues.settings.primaryKey.value,
					this.tableContent.items,
					tableValues.settings.fieldCode,
					tableValues.value,
				);
			},
			/**
			 * Достать имя колонки
			 */
			getOverideName(column)
			{
				if (typeof column.em.name == 'undefined' || column.em.name == '')
					return column.field;

				return column.em.name;
			},
			/**
			 * Задать настройки для одного филда
			 */
			getFieldSettings(column, row)
			{
				let primaryFieldCode = false;

				for(let columnCode in this.table.columns)
				{
					let column = this.table.columns[columnCode];
					if(column.key == 'PRI')
					{
						primaryFieldCode = columnCode;
						break;
					}
				}

				let primaryKey = {
					value: row[primaryFieldCode].value,
					fieldCode: primaryFieldCode
				};

				var settings        = column.em.settings;
				settings.fieldCode  = column.field;
				settings.tableCode  = this.table.code;
				settings.primaryKey = primaryKey;

				return Object.assign({}, settings);
			},
			/**
			 * Сохранить параметры колонки
			 */
			saveColumnsParams()
			{
				let tableColumns = this.table.columns,
					request      = {};

				request['tviewId'] = this.tview.id;
				request['params'] = {};
				request['params']['columns'] = {};

				for (let column in tableColumns)
				{
					if (typeof tableColumns[column].width == 'undefined')
						return false;

					request['params']['columns'][column] = {};
					request['params']['columns'][column] = {
						width: tableColumns[column].width,
						visible: tableColumns[column].visible
					};

					if (typeof this.tview.settings.columns == 'undefined')
						this.$set(this.tview.settings, 'columns', {});

					this.$set(this.tview.settings.columns, column, {});
					this.$set(this.tview.settings.columns[column], 'width', tableColumns[column].width);
					this.$set(this.tview.settings.columns[column], 'visible', tableColumns[column].visible ? 'true' : 'false');
				}

				this.$store.dispatch('saveColumnsWith', request);
			},
			/**
			 * Задать колонкам ширину
			 */
			setDefaulParams()
			{
				let colObject = {};

				for (var colIndex in this.table.columns)
				{
					colObject = this.table.columns[colIndex];

					if (typeof this.tview.settings.columns == 'undefined')
					{
						this.$set(colObject, 'width', 140);
						this.$set(colObject, 'visible', true);
					}
					else if (typeof this.tview.settings.columns[colIndex] == 'undefined')
					{
						this.$set(colObject, 'width', 140);
						this.$set(colObject, 'visible', true);
					}
					else
					{
						this.$set(colObject, 'width', this.tview.settings.columns[colIndex].width);

						if (typeof this.tview.settings.columns[colIndex].visible == 'undefined')
							this.$set(colObject, 'visible', true);
						else
							this.$set(colObject, 'visible', this.tview.settings.columns[colIndex].visible === 'true' ? true : false);
					}
				}
			},
			/**
			 * Достать содержимое таблицы
			 */
			async getTableContent()
			{
				var select = [];
				var where  = [];
				var sort   = [];

				if (typeof this.tview.filter.operation != 'undefined')
					where = this.tview.filter;

				if (typeof this.tview.sort != 'undefined')
					sort = this.tview.sort;

				select.from = this.$route.params.tableCode;
				select.page = this.$route.params.page;
				select.tview = this.$route.params.tview;
				select.where = where;
				select.order = sort;

				await this.$store.dispatch('select', {select: select});
			},
			/**
			 * Начальные значения для изменения ширины колонки
			 */
			reginsterEventResize(event, col)
			{
				this.columnDrug.start = event.target.getBoundingClientRect().left;
				this.columnDrug.width = col.width;
				this.columnDrug.isDrug = true;
				this.columnDrug.posX = event.pageX;
				this.columnDrug.col = col;
			},
			/**
			 * Убрать событие изменения ширины
			 */
			endResize(event, col)
			{
				if (this.columnDrug.isDrug)
					this.saveColumnsParams();

				this.columnDrug.isDrug = false;
			},
			/**
			 * Изменять ширину колонки
			 */
			resizeColumn(event, col)
			{
				if (!this.columnDrug.isDrug)
					return false;

				col.width = Math.abs(this.columnDrug.posX - event.pageX - this.columnDrug.width);

				if (col.width < 110)
					col.width = 110;

				if (col.width > 600)
					col.width = 600;
			},
			/**
			 * Эмит с пагинации. Задает текущую страницу
			 */
			selectPage(pageParams)
			{
				this.$store.dispatch('selectPage', pageParams);
				this.$router.push(`/table/${this.table.code}/tview/${this.tview.id}/page/${pageParams.page}`);
			},
		},
		/**
		 * Хук при загразке страницы
		 */
		mounted()
		{
			this.setDefaulParams();
			this.getTableContent();
		}
	}
</script>
<style lang="scss">
	.table-item-img
	{
		width: 14px;
		height: 14px;
		margin-right: 6px;
	}
	.table-overlay-row-option-icon
	{
		width: 19px;
		height: 2px;
		position: relative;
		z-index: 2;
	}
	.table-row-btn
	{
		position: relative;
		z-index: 2;
		color: rgba(103, 115, 135, 0.4);
		font-size: 12px;
		background-color: transparent;
		border: none;
		cursor: pointer;
		padding: 0;
		margin-right: 10px;
		&:hover
		{
			color: rgba(25, 28, 33, 0.7);
			text-decoration: underline;
		}
	}
	.table-item
	{
		display: flex;
		word-break: break-word;
		align-items: center;
		height: 49px;
		padding-right: 10px;
		padding-left: 9px;
		position: relative;
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		&:last-child
		{
			border-right: none;
		}
	}
	.table-item-overide-name
	{
		line-height: 14px;
		font-size: 12px;
		color: #677387;
		text-transform: capitalize;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
	}
	.table-item-real-name
	{
		line-height: 14px;
		font-size: 10px;
		color: rgba(103, 115, 135, 0.4);
		text-transform: lowercase;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
	}
	.table-row
	{
		.table-overlay-row
		{
			display: none;
		}
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		position: relative;
		&:last-child
		{
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		}
		&.no-hover:hover .table-overlay-row
		{
			display: none;
		}
		&:hover
		{
			.table-overlay-row
			{
				background-color: rgba(103, 115, 135, 0.1);
				width: 100%;
				padding-left: 10px;
				display: flex;
				align-items: center;
				position: absolute;
				height: 100%;
				top: 0;
				left: 0;
				&:after
				{
					content: '';
					background-color: #f0f1f3;
					width: 139px;
					height: 100%;
					z-index: 1;
					position: absolute;
					left: 0;
				}
			}
		}
		.em-check-label
		{
			margin-right: 8px;
			z-index: 2;
			span
			{
				background-color: #fff;
			}
		}
	}
	.table-item-image-col
	{
		display: flex;
		flex-wrap: wrap;
	}
	.table-item-img-wrapper
	{
		width: 14px;
		height: 14px;
		margin-right: 3px;
		cursor: pointer;
		margin-bottom: 3px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	.table-item-checkbox
	{
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.table-vertical-scroll
	{
		overflow: auto;
		padding-bottom: 120px;
	}
	.table__add-column-item
	{
		display: flex;
		align-items: center;
		cursor: pointer;
	}
	.table__add-col-label
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 12px;
	}
	.table__add-col-img
	{
		margin-right: 9px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	// TODO убрать
	.drug-col
	{
		width: 4px;
		height: 100%;
		position: absolute;
		top: 0;
		right: -2px;
		cursor: col-resize;
		transition: all 0.3s;
		&:hover
		{
			background-color: #e6e6e6;
		}
	}
	.table__empty-field
	{
		font-style: normal;
		font-weight: normal;
		font-size: 12px;
		line-height: normal;
		color: rgba(103, 115, 135, 0.4);
		cursor: pointer;
	}
</style>