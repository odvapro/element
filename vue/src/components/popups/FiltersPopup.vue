<template>
	<div class="filters__wrapper" @click.stop>
		<div class="filters__rows">
			<div class="filters__row" v-for="filterItem, index in filter">
				<div class="filters__operators-wrapper">
					<div class="filters__select-item" @click="openSelect(filterItem, 'binSelect')" v-if="index > 0">
						{{defaultOper}}
						<div class="filters__select-column-name" v-if="filterItem.select.binSelect">
							<ul>
								<li v-for="oper in binOperations" @click="defaultOper = oper">{{oper}}</li>
							</ul>
						</div>
					</div>
					<div class="filters__select-item" @click="openSelect(filterItem, 'columnSelect')">
						{{filterItem.name ? filterItem.name : filterItem.code}}
						<div class="filters__select-column-name" v-if="filterItem.select.columnSelect">
							<ul>
								<li v-for="column in columns" @click="filterItem.code = column.field; filterItem.name = column.em.name;">{{column.em.name ? column.em.name : column.field}}</li>
							</ul>
						</div>
					</div>
					<div class="filters__select-item" @click="openSelect(filterItem, 'operationSelect')">
						{{filterItem.operation}}
						<div class="filters__select-column-name" v-if="filterItem.select.operationSelect">
							<ul>
								<li v-for="operationValue in operations" @click="filterItem.operation = operationValue">{{operationValue}}</li>
							</ul>
						</div>
					</div>
					<input type="text" v-model="filterItem.value" class="filters__select-item-input" placeholder="value">
				</div>
				<div class="filters__delete-row-icon-wrapper" @click.stop="deleteRowFilter(index)">
					<div class="filters__delete-row-icon">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<button class="filters__add-filter" @click="addFilterRow">Add filter</button>
	</div>
</template>
<script>
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		mixins: [TableWork],
		props: ['columns', 'tview'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				select:
				{
					columnSelect: false,
					operationSelect: false,
					binSelect: false
				},
				operations:
				[
					'IS', 'IS NOT', 'CONTAINS', 'DOES NOT CONTAIN', 'START WITH', 'ENDS WITH', 'IS EMPTY', 'IS NOT EMPTY'
				],
				binOperations: ['AND', 'OR'],
				defaultOper: 'AND',
				filter: [],
				newFilters: [],
				sourceFilters: [],
				sendRequest: true,
			}
		},
		watch:
		{
			'filter':
			{
				/**
				 * Следить за изменениями фильтров
				 */
				handler: function()
				{
					this.newFilters = [];
					this.buildNewFilter(this.newFilters, JSON.parse(JSON.stringify(this.filter)));
					this.tview.filter = typeof this.newFilters[0].fields == 'undefined' ? [] : this.newFilters[0];

					var self = this;


					if (!this.sendRequest)
						return false;

					this.saveFilters();
					this.sendRequest = false;

					setTimeout(function ()
					{
						self.sendRequest = true;
						self.saveFilters();
					}, 2000);
				},
				deep: true
			}
		},
		methods:
		{
			/**
			 * Сохранить фильтры
			 */
			async saveFilters()
			{
				let self = this;
				let qs = require('qs');
				let data = qs.stringify({
					filters: typeof this.newFilters[0].fields == 'undefined' ? '' : this.newFilters[0],
					tviewId: this.tview.id
				});

				await this.$store.dispatch('select', { select:
				{
					from: self.tview.table,
					page: 1,
					tview: self.tview.id,
					where: typeof self.newFilters[0].fields == 'undefined' ? '' : self.newFilters[0],
				}});

				let resultSaveFilters = await this.$axios({
					method: 'GET',
					url: '/api/tview/saveFilters/',
					params: data,
					/**
					 * сериализовать отправляемые данные
					 */
					paramsSerializer: function(params)
					{
						return data;
					},
				});

				if (!resultSaveFilters.data.success)
					return false;
			},
			/**
			 * Построить новый объект фильтров
			 */
			buildNewFilter(newFilters, filters)
			{
				if (typeof filters == 'undefined')
					return false;

				newFilters.push({operation: this.defaultOper, fields: []});

				for (let filter of filters)
					newFilters[0].fields.push({code: filter.code, operation: filter.operation, value: filter.value});
			},
			/**
			 * достать первый элемент массива/объекта
			 */
			getFirstElement(items)
			{
				for (let item in items)
					return items[item]
			},
			/**
			 * Добавить строку фильтра
			 */
			addFilterRow()
			{
				this.filter.push({code: this.getFirstElement(this.columns).field, operation: this.operations[0], value: '', select: JSON.parse(JSON.stringify(this.select))});
			},
			/**
			 * Удалить строку фильтра
			 */
			deleteRowFilter(indexRow)
			{
				this.filter.splice(indexRow, 1);
			},
			/**
			 * Открыть селект
			 */
			openSelect(filterItem, select)
			{
				filterItem.select[select] = !filterItem.select[select];
			},
			/**
			 * Достать название колонки по коду
			 */
			getColumnNameByCode(columns, code)
			{
				for (let column in columns)
				{
					if (columns[column].field == code)
						return columns[column].em.name;
				}
				return code;
			},
			/**
			 * Распарсить фильтры/преобразовать
			 */
			parseFilers(filters, operation)
			{
				if (typeof filters.fields != 'undefined')
				{
					this.defaultOper = filters.operation;
					this.parseFilers(filters.fields, filters.operation);
					return false;
				}

				for (let filterIndex in filters)
				{
					if (typeof filters[filterIndex].fields != 'undefined')
					{
						this.parseFilers(filters[filterIndex].fields, filters[filterIndex].operation);
						continue;
					}
					this.$set(filters[filterIndex], 'select', JSON.parse(JSON.stringify(this.select)));
					this.$set(filters[filterIndex], 'name', this.getColumnNameByCode(this.columns, filters[filterIndex].code));

					this.filter.push(JSON.parse(JSON.stringify(filters[filterIndex])));
				}
			}
		},
		/**
		 * При загрузке компонента
		 */
		mounted()
		{
			this.parseFilers(this.tview.filter);
			this.sourceFilters = this.tview.filter;
		}
	}
</script>
<style lang="scss">
	.filters__operators-wrapper
	{
		display: flex;
		align-items: center;
	}
	.filters__delete-row-icon-wrapper
	{
		width: 24px;
		height: 24px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 2px;
		&:hover
		{
			background-color: rgba(103, 115, 135, 0.1);
		}
	}
	.filters__delete-row-icon
	{
		width: 12px;
		height: 12px;
		transform: rotate(45deg);
	}
	.filters__row
	{
		margin-bottom: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.filters__wrapper
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		padding: 15px;
		min-width: 320px;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background-color: #fff;
	}
	.filters__add-filter
	{
		background-color: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		border: none;
		padding: 6px 10px;
		font-size: 12px;
		color: #677387;
		cursor: pointer;
	}
	.filters__select-item
	{
		white-space: nowrap;
		padding: 6px 30px 6px 10px;
		position: relative;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		margin-right: 10px;
		&:after
		{
			content: '';
			width: 7px;
			height: 7px;
			position: absolute;
			border-bottom: 1px solid #677387;
			border-right: 1px solid #677387;
			transform: rotate(45deg);
			right: 10px;
			top: calc(50% - 7px);
		}
	}
	.filters__select-item-input
	{
		width: 80px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		border-radius: 2px;
		padding: 6px 10px;
		margin-right: 10px;
		color: #677387;
		&::placeholder
		{
			color: rgba(103, 115, 135, 0.4);
		}
	}
	.filters__select-column-name
	{
		position: absolute;
		top: calc(100% + 5px);
		left: 0;
		padding: 5px 0;
		min-width: 120px;
		z-index: 5;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background-color: #fff;
		ul li
		{
			margin: 0;
			line-height: 18px;
			white-space: nowrap;
		}
	}
</style>