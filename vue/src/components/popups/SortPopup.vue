<template>
	<div class="sort-popup__wrapper" @click.stop>
		<div class="sort-popup__rows">
			<div class="sort-popup__row" v-for="param, index in sortArray">
				<div class="sort-popup__operators-wrapper">
					<div class="sort-popup__select-item" @click="param.popups.isOpenSelectColumn = !param.popups.isOpenSelectColumn">
						{{param.selectedColumn.name ? param.selectedColumn.name : param.selectedColumn.code}}
						<div class="sort-popup__select-column-name" v-if="param.popups.isOpenSelectColumn">
							<ul>
								<li v-for="column in columns" @click="param.selectedColumn.code = column.field; param.selectedColumn.name = column.em.name">{{!column.em.name ? column.field : column.em.name}}</li>
							</ul>
						</div>
					</div>
					<div class="sort-popup__select-item" @click="param.popups.isOpenSelectSort = !param.popups.isOpenSelectSort">
						{{param.default}}
						<div class="sort-popup__select-column-name" v-if="param.popups.isOpenSelectSort">
							<ul>
								<li v-for="sortItem, sortKey in sortValues" @click="param.selectedSort = sortKey; param.default = sortItem;">{{sortItem}}</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="sort-popup__delete-row-icon-wrapper" @click.stop="deleteRowSort(index)">
					<div class="sort-popup__delete-row-icon">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<button class="sort-popup__add-view" @click="addSortRow">Add view</button>
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
					}, 2000);
				},
				deep: true
			}
		},
		methods:
		{
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
					url: '/api/tview/saveSort/',
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
			}
		},
		/**
		 * Хук при загрузке компонента
		 */
		mounted()
		{
			this.sortParams = this.tview.sort;
			this.parseSortParam();
		}
	}
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
		min-width: 320px;
		z-index: 2;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		background-color: #fff;
	}
	.sort-popup__add-view
	{
		background-color: rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		border: none;
		padding: 6px 10px;
		font-size: 12px;
		color: #677387;
		cursor: pointer;
	}
	.sort-popup__select-item-input
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
	.sort-popup__select-item
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
	.sort-popup__select-item-input
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
	.sort-popup__select-column-name
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