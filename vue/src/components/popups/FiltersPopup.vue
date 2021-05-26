<template>
	<div class="filters-popup__wrapper">
		<div class="filters-popup__rows">
			<div class="filters-popup__row" v-for="filterItem, index in filter">
				<div class="filters-popup__operators-wrapper">
					<Select
						class="filters-popup__select"
						:defaultText="defaultOper"
						v-if="index > 0"
					>
						<SelectOption
							v-for="oper in binOperations"
							@click.native="selectLogic(oper)"
							:key="oper"
							:class="{active:defaultOper == oper}"
						>{{ oper }}</SelectOption>
					</Select>
					<Select class="filters-popup__select" :defaultText="filterItem.name ? filterItem.name : filterItem.code">
						<SelectOption
							v-for="column in columns"
							@click.native="selectColumn(filterItem,column)"
							:key="column.field"
							:class="{active:filterItem.code == column.field}"
						>{{column.em.name ? column.em.name : column.field}}</SelectOption>
					</Select>
					<Select class="filters-popup__select" :defaultText="getColumnCollationName(filterItem.code,filterItem.operation)">
						<SelectOption
							v-for="operation in getColumnCollations(filterItem.code)"
							@click.native="selectOperation(filterItem,operation)"
							:key="operation.code"
							:class="{active:filterItem.operation == operation.code}"
						>{{ operation.name }}</SelectOption>
					</Select>
					<MainFilterField
						:filter="filterItem"
						:columns="columns"
						@onChange="changeFilterValue"
					></MainFilterField>
				</div>
				<div class="filters-popup__delete-row-icon-wrapper" @click.stop="deleteRowFilter(index)">
					<div class="filters-popup__delete-row-icon" data-test="filter-delete-icon">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<button class="el-gbtn" @click="addFilterRow">{{$t('popups.filtersPopup.add_filter')}}</button>
	</div>
</template>
<script>
	import MainFilterField from '@/components/fields/MainFilterField.vue';
	import TableWork from '@/mixins/tableWork.js';
	export default
	{
		components: { MainFilterField },
		props: ['columns', 'tview'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				select:
				{
					columnSelect    : false,
					operationSelect : false,
					binSelect       : false
				},
				operations    : [],
				binOperations : ['AND', 'OR'],
				defaultOper   : 'AND',
				filter        : [],
				newFilters    : [],
				sourceFilters : [],
				sendRequest   : true,
			}
		},
		watch:
		{
			'filter':
			{
				handler: function()
				{
					this.newFilters = [];
					this.buildNewFilter(this.newFilters, JSON.parse(JSON.stringify(this.filter)));
					this.tview.filter = this.newFilters[0].fields.length == 0 ? [] : this.newFilters[0];

					var self = this;

					if (!this.sendRequest)
						return false;

					this.saveFilters();
					this.sendRequest = false;

					setTimeout(function ()
					{
						self.sendRequest = true;
						self.saveFilters();
						self.updateModified();
					}, 2000);
				},
				deep: true
			}
		},
		mounted()
		{
			this.parseFilers(this.tview.filter);
			this.sourceFilters = this.tview.filter;
			this.updateModified();
		},
		methods:
		{
			updateModified()
			{
				this.$parent.updateModified('filter', (this.filter && this.filter.length));
			},
			/**
			 * Select column for filtering
			 */
			selectColumn(filterItem,column)
			{
				filterItem.code = column.field;
				filterItem.name = column.em.name;
				this.operations = column.em.collations;
			},

			selectOperation(filterItem,operation)
			{
				filterItem.operation = operation.code
			},

			selectLogic(oper)
			{
				this.defaultOper = oper;
			},

			async saveFilters()
			{
				let self = this;
				let qs = require('qs');
				let data = qs.stringify({
					filters: this.newFilters[0].fields.length == 0 ? [] : this.newFilters[0],
					tviewId: this.tview.id
				});

				await this.$store.dispatch('select', { select:
				{
					from: self.tview.table,
					page: 1,
					tview: self.tview.id,
					where: this.newFilters[0].fields.length == 0 ? '' : self.newFilters[0],
					order: self.tview.sort
				}});

				let resultSaveFilters = await this.$axios({
					method : 'GET',
					url    : '/tview/saveFilters/',
					params : data,
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
			 * Adds filter line
			 */
			addFilterRow()
			{
				let firstColumn = this.getFirstElement(this.columns);
				this.filter.push({
					code      : firstColumn.field,
					operation : firstColumn.em.collations[0].code,
					name      : firstColumn.em.name,
					value     : '',
					select    : JSON.parse(JSON.stringify(this.select))
				});
			},

			deleteRowFilter(indexRow)
			{
				this.filter.splice(indexRow, 1);
			},

			getColumnNameByCode(code)
			{
				if(typeof this.columns[code] != 'undefined' && typeof this.columns[code].em.name != 'undefined')
					return this.columns[code].em.name;
				return code;
			},

			getColumnCollations(columnCode)
			{
				if(typeof this.columns[columnCode] != 'undefined' && typeof this.columns[columnCode].em.collations != 'undefined')
					return this.columns[columnCode].em.collations;
				return [];
			},

			getColumnCollationName(columnCode,collationCode)
			{
				if(typeof this.columns[columnCode] == 'undefined' || typeof this.columns[columnCode].em.collations == 'undefined')
				{
					throw new Error(`No collation with code ${columnCode}`);
					return 'no collation';
				}
				let collations = this.columns[columnCode].em.collations;
				for(let collation of collations)
					if(collation.code == collationCode)
						return collation.name;
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
					this.$set(filters[filterIndex], 'name', this.getColumnNameByCode(filters[filterIndex].code));

					this.filter.push(JSON.parse(JSON.stringify(filters[filterIndex])));
				}
			},
			changeFilterValue(e)
			{
				e.filter.value = e.value;
			}
		},
	};
</script>
<style lang="scss">
	.filters-popup__operators-wrapper
	{
		display: flex;
		align-items: center;
	}
	.filters-popup__delete-row-icon-wrapper
	{
		width: 24px;
		height: 24px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 2px;
		&:hover {background-color: rgba(103, 115, 135, 0.1); }
		svg{stroke:#c2c7cf;}
	}
	.filters-popup__delete-row-icon
	{
		transform: rotate(45deg);
		font-size: 0;
		line-height: 0;
		svg
		{
			width: 12px;
			height: 12px;
		}
	}
	.filters-popup__row
	{
		margin-bottom: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.filters-popup__wrapper
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		padding: 15px;
		min-width: 200px;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background-color: #fff;
		transform: translate(calc(50% - 30px));
	}
	.filters-popup__filter-input {width: 80px; margin-right: 10px; }
	.filters-popup__select{margin-right: 10px;}
</style>
