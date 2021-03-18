<template>
	<div class="sort-popup__wrapper">
		<div class="sort-popup__rows">
			<div class="sort-popup__row" v-for="sortLine, sortLineIndex in sortArray">
				<div class="sort-popup__operators-wrapper">
					<Select
						class="sort-popup__select"
						:defaultText="sortLine.selectedColumn.name ? sortLine.selectedColumn.name : sortLine.selectedColumn.code"
					>
						<SelectOption
							v-for="column in columns"
							:value="column.field"
							:key="column.field"
							@click.native="selectCol(sortLine,column)"
							:class="{active:column.field == sortLine.selectedColumn.code }"
						>{{!column.em.name ? column.field : column.em.name}}</SelectOption>
					</Select>
					<Select :defaultText="sortLine.default" class="sort-popup__select">
						<SelectOption
							v-for="sortItem, sortKey in sortValues"
							:key="sortKey"
							:class="{active:sortLine.default == sortItem}"
							@click.native="selectSorting(sortLine,sortKey,sortItem)"
						>{{sortItem}}</SelectOption>
					</Select>
				</div>
				<div class="sort-popup__delete-row-icon-wrapper" @click.stop="deleteRowSort(sortLineIndex)">
					<div class="sort-popup__delete-row-icon">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<button class="el-gbtn" @click="addSortRow">{{$t('popups.sortPopup.add_sort')}}</button>
	</div>
</template>
<script>
	export default
	{
		props: ['tview', 'columns'],
		/**
		 * Глобальные переменные страницы
		 */
		data()
		{
			return {
				sortParams: '',
				sortArray: [],
				sortValues:
				{
					ASC: 'Ascenging',
					DESC: 'Descending'
				},
				finalSort: [],
				sendRequest: true,
			}
		},
		watch:
		{
			'sortArray':
			{
				/**
				 * Следить за изменениями сортировки
				 */
				handler: function ()
				{
					if (!this.sendRequest)
						return false;

					let self = this;

					this.buildSortParams();
					self.sendRequest = false;

					setTimeout(function ()
					{
						self.sendRequest = true;
						self.buildSortParams();
						self.updateModified();
					}, 2000);
				},
				deep: true
			}
		},
		/**
		 * Хук при загрузке компонента
		 */
		mounted()
		{
			this.sortParams = this.tview.sort;
			this.parseSortParam();
			this.updateModified();
		},
		methods:
		{
			updateModified()
			{
				this.$parent.updateModified('sort', (this.sortArray && this.sortArray.length));
			},
			/**
			 * Выбор колонки
			 */
			selectCol(sortLine,column)
			{
				sortLine.selectedColumn.code = column.field;
				sortLine.selectedColumn.name = column.em.name;
			},
			/**
			 * Выбор сортировки
			 */
			selectSorting(sortLine,sortKey,sortItem)
			{
				sortLine.selectedSort = sortKey;
				sortLine.default      = sortItem;
			},
			/**
			 * Создать строку сортировки для отправки в бд
			 */
			async buildSortParams()
			{
				let sortParams = [];

				for (let sort of this.sortArray)
				{
					let sortOneElement = [];
					sortOneElement.push(sort.selectedColumn.code);
					sortOneElement.push(sort.selectedSort);
					sortParams.push(sortOneElement.join(' '));
				}
				this.finalStrSort = sortParams;
				this.sortParams = sortParams;
				this.tview.sort = sortParams;

				let self = this;
				let qs = require('qs');
				let data = qs.stringify({
					sort    : sortParams,
					tviewId : this.tview.id
				});

				await this.$store.dispatch('select', { select:
				{
					from: self.tview.table,
					page: 1,
					tview: self.tview.id,
					where: typeof self.tview.filter.fields == 'undefined' ? '' : self.tview.filter,
					order: sortParams
				}});

				let resultSaveSort = await this.$axios({
					method: 'GET',
					url: '/tview/saveSort/',
					params: data,
					/**
					 * сериализовать отправляемые данные
					 */
					paramsSerializer: function(params)
					{
						return data;
					},
				});

				if (!resultSaveSort.data.success)
					return false;
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
			 * Добавить сортировку
			 */
			addSortRow()
			{
				let startParams = {};
				let firstColumn = this.getFirstElement(this.columns);

				this.$set(startParams, 'default', 'Ascenging');
				this.$set(startParams, 'selectedSort', 'ASC');
				this.$set(startParams, 'selectedColumn', {code: firstColumn.field, name: firstColumn.em.name});
				this.$set(startParams, 'popups', {isOpenSelectColumn: false, isOpenSelectSort: false});

				this.sortArray.push(startParams);
			},
			/**
			 * Удалить строку фильтра
			 */
			deleteRowSort(indexRow)
			{
				this.sortArray.splice(indexRow, 1);
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
			 * преобразовать параметры сортировки в массив
			 */
			parseSortParam()
			{
				let sortCountParam = this.tview.sort;

				for (let param of sortCountParam)
				{
					let sortKey = param.trim().split(' ')[0];
					let sortValue = param.trim().split(' ')[1];
					let obj = {};

					this.$set(obj, 'default', this.sortValues[sortValue]);
					this.$set(obj, 'selectedSort', sortValue);
					this.$set(obj, 'selectedColumn', {code: sortKey, name: this.getColumnNameByCode(this.columns, sortKey)});
					this.$set(obj, 'popups', {isOpenSelectColumn: false, isOpenSelectSort: false});

					this.sortArray.push(obj);
				}
			},
		},
	};
</script>
<style lang="scss">
	.sort-popup__operators-wrapper
	{
		display: flex;
		align-items: center;
	}
	.sort-popup__delete-row-icon-wrapper
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
	.sort-popup__delete-row-icon
	{
		width: 12px;
		height: 12px;
		transform: rotate(45deg);
		svg{stroke:#677387;}
	}
	.sort-popup__row
	{
		margin-bottom: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.sort-popup__wrapper
	{
		position: absolute;
		top: calc(100% + 5px);
		right: 0;
		padding: 15px;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background-color: #fff;
		width:auto;
		min-width: 200px;
		transform: translate(calc(50% - 11px));
	}
	.sort-popup__select{margin-right: 10px;}
</style>
