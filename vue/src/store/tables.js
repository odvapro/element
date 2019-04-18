import axios from 'axios';

var qs = require('qs');
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const table =
{
	state:
	{
		selectRequest   : {},
		tables          : [],
		tableColumns    : [],
		tableContent    : {},
		selectedElement : {}
	},
	mutations:
	{
		/**
		 * Select запрос шаблон
		 */
		setSelectRequest(state, select)
		{
			state.selectRequest = select;
		},

		/**
		 * Записать название таблицы
		 */
		setTableName(state, names)
		{
			state.tableName = names;
		},

		/**
		 * Добавить список таблиц
		 */
		setTables(state, tables)
		{
			state.tables = tables;
		},

		/**
		 * Записать названия столбцов таблицы
		 */
		setTableColumns(state, columns)
		{
			state.tableColumns = columns;
		},

		/**
		 * Записать содержимое таблицы
		 */
		setTableContent(state, tableContent)
		{
			state.tableContent = tableContent;
		},

		/**
		 * Записать содержимое таблицы
		 */
		setSelectedElement(state, selectedElement)
		{
			state.selectedElement = selectedElement;
		},
	},
	getters:
	{
		/**
		 * Возвращет таблику по коду
		 * @param  table code
		 * @return table object
		 */
		getTable: store => tableCode =>
		{
			for(let table of store.tables)
			{
				if(table.code != tableCode)
					continue;
				return table;
			}
			return false;
		},

		/**
		 * Достает ключ таблицы
		 */
		getPrimaryKeyCode: (store, getters )=> tableCode =>
		{
			let primaryFieldCode = false;
			let table = getters.getTable(tableCode);
			if(table === false)
				return false;

			for(let columnCode in table.columns)
			{
				let column = table.columns[columnCode];
				if(column.key != 'PRI')
					continue;

				primaryFieldCode = columnCode;
				break;
			}
			return primaryFieldCode;
		},

		/**
		 * Достает колонки таблицы
		 */
		getColumns: (store, getters) => tableCode =>
		{
			let table = getters.getTable(tableCode);
			if(table === false)
				return false;
			return table.columns;
		},

		/**
		 * Формирует настройки колоки
		 * основной формат настроек на всем проекте
		 * {
		 * 		primartyKey
		 * 		fieldCode
		 * 		tableCode
		 * 		...  (остальые поля для каждого филда свои)
		 * 	}
		 */
		getColumnSettings: (store, getters) => (tableCode, column, row) =>
		{
			let primaryKeyCode = getters.getPrimaryKeyCode(tableCode);
			let primaryKey = {
				value     : row[primaryKeyCode].value,
				fieldCode : primaryKeyCode
			};

			var settings        = column.em.settings;
			settings.fieldCode  = column.field;
			settings.tableCode  = tableCode;
			settings.primaryKey = primaryKey;

			return Object.assign({}, settings);
		}
	},
	actions:
	{
		/**
		 * Достать список таблиц
		 */
		async getTables(store)
		{
			var result = await axios({
				method : 'get',
				url    : '/api/el/getTables'
			});

			if (!result.data.success)
				return false;

			store.commit('setTables', result.data.tables);
		},

		/**
		 * Достать содержимое таблицы
		 */
		async select(store, params)
		{
			store.commit('setSelectRequest', params);
			var data = qs.stringify(params);

			var result = await axios({
				method: 'get',
				url: '/api/el/select/',
				params: data,
				/**
				 * сериализовать отправляемые данные
				 */
				paramsSerializer: function(params)
				{
					return data;
				},
			});

			if (!result.data.success)
				return false;

			store.commit('setTableContent', result.data.result);
		},

		/**
		 * задать страницу
		 */
		async selectPage(store, pageParams)
		{
			var newParams = Object.assign(store.state.selectRequest, {});
			newParams.select.page = pageParams.page;
			newParams.limit = pageParams.limit;
			await store.dispatch('select', newParams);
		},

		/**
		 * Удалить запись
		 */
		async removeRecord(store, recordPrams)
		{
			store.state.tableContent.items.splice(recordPrams.rowIndex,1);
			var result = await axios({
				method: 'post',
				url: '/api/el/delete/',
				data: qs.stringify({delete:recordPrams.delete}),
			});

			if (!result.data.success)
				return false;
		},

		/**
		 * Сохранить ширину колонок
		 */
		async saveColumnsWith(store, params)
		{
			var data   = qs.stringify(params);
			var result = await axios({
				method : 'post',
				url    : '/api/el/setTviewSettings/',
				data   : data
			});

			if (!result.data.success)
				return false;

			return true;
		},

		/**
		 * Достать содержимое таблицы
		 */
		async selectElement(store, params)
		{
			store.commit('setSelectRequest', params);
			var data = qs.stringify(params);

			var result = await axios({
				method : 'get',
				url    : '/api/el/select/',
				params : data,
				/**
				 * сериализовать отправляемые данные
				 */
				paramsSerializer: function(params)
				{
					return data;
				},
			});

			if (!result.data.success || result.data.result.items.length == 0)
				return false;
			this.commit('setSelectedElement',result.data.result.items[0]);
		}
	}
}
export default table;