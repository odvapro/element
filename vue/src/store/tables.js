import axios from 'axios';

var qs = require('qs');
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const table =
{
	state:
	{
		selectRequest: {},
		tables   : [],
		tableColumns : [],
		tableContent : {},
		selectedElement:{}
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
		 * Достает ключ таблицы
		 */
		getPrimaryKeyCode: store=> tableCode =>
		{
			let primaryFieldCode = false;
			let tableColumns     = false;
			for(let table of store.tables)
			{
				if(table.code != tableCode)
					continue;

				for(let columnCode in table.columns)
				{
					let column = table.columns[columnCode];
					if(column.key != 'PRI')
						continue;

					primaryFieldCode = columnCode;
					break;
				}
			}
			return primaryFieldCode;
		},
		/**
		 * Достает колонки таблицы
		 */
		getColumns: store=> tableCode=>
		{
			for(let table of store.tables)
			{
				if(table.code != tableCode)
					continue;
				return table.columns;
			}
			return false;
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
				method: 'get',
				url: '/api/el/getTables'
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

			// if (!result.data.success)
			// 	return false;
		},

		/**
		 * Сохранить ширину колонок
		 */
		async saveColumnsWith(store, params)
		{
			var data = qs.stringify(params);

			var result = await axios({
				method: 'post',
				url: '/api/el/setTviewSettings/',
				data: data
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
			this.commit('setSelectRequest',result.data.result.items[0]);
		}
	}
}
export default table;