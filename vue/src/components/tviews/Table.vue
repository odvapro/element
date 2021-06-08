<template>
	<div class="table-vertical-scroll"
		@mousemove="resizeColumn($event)"
		@mouseup="endResize($event, columnDrug.col)">
		<div class="table__min-width" :style="{'min-width': getTableMinWidth + 'px'}">
			<div class="table-row no-hover">
				<div class="table-item table__many-box">
					<Checkbox
						:checked.sync="checkAll"
						@change="checkAllRows"
					></Checkbox>
					<svg class="table__many-arrow" width="7" height="13" @click="openedEditRowIndex = 'all'">
						<use xlink:href="#arrow"></use>
					</svg>
					<div class="table__many-modal" v-if="openedEditRowIndex === 'all'" v-click-outside="closeEditModal">
						<ul>
							<li @click="removeSelected()" class="table__many-delete">{{$t('delete')}}</li>
						</ul>
					</div>
				</div>
				<div
					class="table-item"
					v-for="(column, index) of tableColumns"
					v-if="column.visible"
					:class="`_table-col-${column.field}`"
					:style="{ width: column.width + 'px'}"
				>
					<div class="table-item-img">
						<img :src="require(`@/assets${column.em.type_info.iconPath}`)" alt="">
					</div>
					<div class="table-item-name-wrapper">
						<div class="table-item-overide-name">{{getOverideName(column)}}</div>
						<div class="table-item-real-name">{{column.field}}</div>
					</div>
					<div
						class="drug-col"
						@mousedown="registerEventResize($event, column)"
					></div>
				</div>
				<div class="table-item table-item--empty">
					<div class="table__add-column-item">
						<!-- <div class="table__add-col-img">
							<svg width="12" height="12">
								<use xlink:href="#plus-white"></use>
							</svg>
						</div>
						<span class="table__add-col-label">Add field</span> -->
					</div>
				</div>
			</div>
			<div class="table-row" data-test="table-row" v-for="(row, rowIndex) in tableContent.items">
				<div class="table-item table__many-box">
					<Checkbox
						:checked.sync="selectedRows[rowIndex]"
					></Checkbox>
					<svg class="table__many-arrow" width="7" height="13" @click="openedEditRowIndex = rowIndex">
						<use xlink:href="#arrow"></use>
					</svg>
					<div class="table__many-modal" v-if="openedEditRowIndex === rowIndex" v-click-outside="closeEditModal">
						<ul>
							<li @click="openDetail(row,rowIndex)">{{$t('edit')}}</li>
							<li @click="duplicate(row)">{{$t('duplicate')}}</li>
							<li @click="remove(row,rowIndex)" class="table__many-delete">{{$t('delete')}}</li>
						</ul>
					</div>
				</div>
				<div
					class="table-item"
					v-for="(column, index) of tableColumns"
					v-if="column.visible && typeof row[column.field] !== 'undefined'"
					:key="`${column.field}${rowIndex}`"
					:class="`_table-col-${column.field}`"
					:style="{width: column.width + 'px'}"
				>
					<MainField
						mode="edit"
						view="table"
						:fieldName="column.em.settings.code"
						:params="{
							value     : row[column.field],
							settings  : $store.getters.getColumnSettings(table.code, column.field, row)
						}"
						@onChange="changeFieldValue"
						@openEdit="openDetail(row,rowIndex)"
					/>
				</div>
				<div class="table-item table-item--empty">
					<div class="table-empty-col"></div>
				</div>
			</div>
			<div class="table-row table-row__empty" data-test="table-row" v-if="!hasTableItems">
				<span class="el-empty">{{$t('tviews.empty_table')}} - <span @click="addElement()" class="table-row__add-bnt">{{$t('add_element')}}</span></span>
			</div>
		</div>
		<Pagination
			v-if="tableContent.total_pages > 1"
			:maxPage="tableContent.total_pages"
			:current="tableContent.current"
			:currentLimit="tableContent.limit"
			@change="selectPage"
		/>
	</div>
</template>
<script>
	import MainField from '@/components/fields/MainField.vue';
	import Pagination from '@/components/layouts/Pagination.vue';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		props:['table', 'tview'],
		components: {MainField, Pagination},
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				columnDrug: {isDrug: false, posX: 0, start: 0, width: 0, col: ''},
				openProperties: false,
				openedEditRowIndex:false,
				checkAll:false,
				selectedRows:{}
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
			 * @return bool
			 */
			hasTableItems()
			{
				if(typeof this.$store.state.tables.tableContent.items == 'undefined')
					return false;
				if(this.$store.state.tables.tableContent.items.length > 0)
					return true;
				return false;
			},

			/**
			 * Определить минимальную ширину таблицу
			 */
			getTableMinWidth()
			{
				let sum = 0;
				for (let column in this.table.columns)
				{
					let columnObject = this.table.columns[column];
					if(columnObject.visible)
						sum += +columnObject.width;
				}
				return sum + 300;
			},

			/**
			 * Достать название таблицы из урла
			 */
			getUrlTableName()
			{
				return this.$route.params.tableCode;
			},

			/**
			 * упорядоченные колонки таблицы
			 */
			tableColumns()
			{
				if (!this.table || !this.table.columns) return [];

				return Object.values(this.table.columns).sort((item1, item2) => {
					return item1.order - item2.order;
				});
			},
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
					let curCols = Object.entries(oldVal);
					curCols = curCols.reduce((colsString,col)=>{
						return colsString += col[0];
					});
					let newCols = Object.entries(val);
					newCols = newCols.reduce((colsString,col)=>{
						return colsString += col[0];
					});

					if (this.columnDrug.isDrug == false && newCols == curCols)
						this.saveColumnsParams();
				},
				deep: true
			},

			'tableContent.last'(value, oldValue)
			{
				if (value < +this.$route.params.page)
					this.selectPage({
						limit: +this.$route.params.limit ? +this.$route.params.limit : 0,
						page: value,
					});
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
		/**
		 * Хук при загразке страницы
		 */
		mounted()
		{
			this.setDefaulParams();
			this.getTableContent();
		},
		methods:
		{
			/**
			 * Checkes all rows
			 */
			checkAllRows(checkedStatus)
			{
				if(checkedStatus === false)
				{
					this.selectedRows = {};
				}
				else
				{
					this.selectedRows = [];
					for(let rowIndex in this.tableContent.items)
						this.selectedRows[parseInt(rowIndex)] = true;
				}
			},

			/**
			 * Closw edit modal window
			 */
			closeEditModal()
			{
				this.openedEditRowIndex = false;
			},

			/**
			 * Сохранить локально измененные данные в таблице
			 * @fieldValue {
			 *     value
			 *     settings (main format)
			 * }
			 */
			changeFieldValue(fieldValue)
			{
				const id = fieldValue.settings.primaryKey.value;
				const tableCode = fieldValue.settings.tableCode;
				let selectedElement = null;

				for (let row of this.tableContent.items)
					if (row.id === id)
					{
						selectedElement = row;
						break;
					}

				if (!selectedElement || typeof selectedElement[fieldValue.settings.fieldCode] === 'undefined') return;
				selectedElement[fieldValue.settings.fieldCode] = fieldValue.value;

				this.$store.dispatch('saveSelectedElement', { selectedElement, tableCode });
			},

			/**
			 * Достать имя колонки
			 */
			getOverideName(column)
			{
				return column.em.name || column.field;
			},

			/**
			 * Сохранить параметры колонки
			 */
			saveColumnsParams()
			{
				let tableColumns = this.table.columns,
					request      = {};

				request.tviewId = this.tview.id;
				request.params = {};
				request.params.columns = {};

				for (let column in tableColumns)
				{
					if (typeof tableColumns[column].width == 'undefined')
						return false;

					request.params.columns[column] = {
						width: tableColumns[column].width,
						visible: tableColumns[column].visible,
						order: tableColumns[column].order || 0,
					};

					if (typeof this.tview.settings.columns == 'undefined')
						this.$set(this.tview.settings, 'columns', {});

					this.$set(this.tview.settings.columns, column, {});
					this.$set(this.tview.settings.columns[column], 'width', tableColumns[column].width);
					this.$set(this.tview.settings.columns[column], 'visible', tableColumns[column].visible ? 'true' : 'false');
					this.$set(this.tview.settings.columns[column], 'order', tableColumns[column].order || 0);
				}

				this.$store.dispatch('saveTableSettings', request);
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
				this.$store.commit('showLoader',true);
				var requestParams = {select : {}, };

				if (this.tview.filter)
					requestParams.select.where = this.tview.filter;

				if (this.tview.sort)
					requestParams.select.order = this.tview.sort;

				requestParams.select.from  = this.$route.params.tableCode || this.tview.table;
				requestParams.select.page  = this.$route.params.page      || 1;
				requestParams.select.tview = this.$route.params.tview     || this.tview.id;

				if(this.$route.params.limit)
					requestParams.select.limit = this.$route.params.limit

				await this.$store.dispatch('select', requestParams);
				this.$store.commit('showLoader',false);
			},

			/**
			 * Начальные значения для изменения ширины колонки
			 */
			registerEventResize(event, col)
			{
				this.columnDrug.start  = event.target.getBoundingClientRect().left;
				this.columnDrug.width  = col.width;
				this.columnDrug.isDrug = true;
				this.columnDrug.posX   = event.pageX;
				this.columnDrug.col    = col;
			},

			/**
			 * Убрать событие изменения ширины
			 */
			endResize(event, col)
			{
				if (this.columnDrug.isDrug)
				{
					let col      = this.columnDrug.col;
					let colDomEl = document.querySelector(`._table-col-${col.field}`);
					col.width    = +colDomEl.style.width.replace('px','');
					this.saveColumnsParams();
				}

				this.columnDrug.isDrug = false;
			},

			/**
			 * Изменять ширину колонки
			 */
			resizeColumn(event)
			{
				if (!this.columnDrug.isDrug)
					return false;
				let newWidth = Math.abs(this.columnDrug.posX - event.pageX - this.columnDrug.width);
				if (newWidth < 110)
					newWidth = 110;

				if (newWidth > 600)
					newWidth = 600;

				// col.width = newWidth;
				let col = this.columnDrug.col;
				document.querySelectorAll(`._table-col-${col.field}`).forEach((el)=>{
					el.style.width = `${newWidth}px`;
				});
			},

			/**
			 * Эмит с пагинации. Задает текущую страницу
			 */
			selectPage(pageParams)
			{
				this.$router.push(`/table/${this.table.code}/tview/${this.tview.id}/page/${pageParams.page}/limit/${pageParams.limit}`);
			},

			/**
			 * Removes selected records
			 */
			async removeSelected()
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.table.code);
				let requestWhere = {
					operation:'or',
					fields:[]
				}
				let rowIndexes = [];
				for(let rowIndex in this.selectedRows)
				{
					if(this.selectedRows[rowIndex] === false) continue;
					if(typeof this.tableContent.items[rowIndex] == 'undefined')
						continue;

					rowIndexes.push(parseInt(rowIndex));
					let row = this.tableContent.items[rowIndex];
					requestWhere.fields.push({
						code      : primaryKeyCode,
						operation : 'IS',
						value     : row[primaryKeyCode],
					});
				}

				await this.$store.dispatch('removeRecord', {
					rowIndex:rowIndexes,
					delete:
					{
						table: this.table.code,
						where: requestWhere
					}
				});

				this.selectedRows = {};
				this.openedEditRowIndex	= false;
				this.checkAll = false;
			},
			/**
			 * duplicate row
			 */
			async duplicate(row)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.table.code);

				let result = await this.$store.dispatch('duplicateRecord', {
					from: this.table.code,
					where:
					{
						operation:'and',
						fields:[
							{
								code      : `${primaryKeyCode} = ${row[primaryKeyCode]}`,
								operation : 'IS',
								value     : row[primaryKeyCode]
							}
						]
					}
				});
				this.openedEditRowIndex = false;
			},
			/**
			 * Удаляет запись
			 */
			async remove(row,rowIndex)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.table.code);
				await this.$store.dispatch('removeRecord', {
					rowIndex:rowIndex,
					delete:
					{
						table: this.table.code,
						where:
						{
							operation:'and',
							fields:[
								{
									code      : primaryKeyCode,
									operation : 'IS',
									value     : row[primaryKeyCode],
								}
							]
						}
					}
				});
				this.openedEditRowIndex	= false;
			},

			/**
			 * Opens detail page
			 */
			openDetail(row,rowIndex)
			{
				let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(this.table.code);
				this.$router.push({name:'tableDetail', params:{tableCode:this.table.code, id:row[primaryKeyCode] }});
			},

			addElement()
			{
				this.$router.push(`/table/${this.table.code}/add/`);
			},
		},
	};
</script>
<style lang="scss">
	.table-item-img
	{
		width: 14px;
		height: 14px;
		margin-right: 6px;
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
		&:hover{background: rgba(103, 115, 135, 0.05);}
		&--empty{padding: 0;width: 0;border: 0;}
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
		display: flex;
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		position: relative;
		&:last-child
		{
			border-bottom: 1px solid rgba(103, 115, 135, 0.1);
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
	.table-row__empty{padding:30px;}
	.table-row__add-bnt
	{
		text-decoration: underline;
		&:hover{text-decoration: none;}
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
		height: calc(100vh - 70px);
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
		svg{stroke:rgba(103, 115, 135, 0.4);}
	}
	.table__many-box
	{
		width:50px;
		padding:0 12px;
		text-align: center;
	}
	.table__many-box:hover .table__many-arrow{visibility: visible;}
	.table__many-arrow
	{
		transform: rotate(90deg);
		width:5px;
		margin-left:7px;
		visibility: hidden;
		cursor: pointer;
	}
	.table__many-modal
	{
		position: absolute;
		background: #fff;
		z-index:10;
		top:40px;
		border:1px solid #E2E4E8;
		border-radius: 2px;
		min-width:80px;
		text-align:left;
		padding:10px;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		ul li
		{
			font-style: normal;
			font-weight: normal;
			font-size: 10px;
			line-height: 13px;
			color: rgba(25, 28, 33, 0.7);
			margin-bottom: 7px;
			cursor: pointer;
			white-space: nowrap;
			&:last-child{margin-bottom: 0px;}
			&.table__many-delete
			{
				font-style: normal;
				font-weight: normal;
				font-size: 10px;
				line-height: 13px;
				color: rgba(208, 18, 70, 0.7);
			}
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
	@media (max-width: 768px)
	{
		.table-vertical-scroll
		{
			width: 100%;
			overflow: initial;
		}
	}
</style>
