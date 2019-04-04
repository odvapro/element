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
		 * Достать колонки таблицы
		 */
		async getColumns(store, tableName)
		{
			var data = qs.stringify({tableName: tableName});

			var result = await axios({
				method: 'post',
				url: '/api/el/getColumns',
				data: data
			});

			if (!result.data.success)
				return false;

			store.commit('setTableColumns', result.data.columns);
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
		}
	}
}
export default table;