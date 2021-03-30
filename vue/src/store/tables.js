import axios from 'axios';
import {message} from '../plugins/message.js';
import qs from 'qs';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT;

const table =
{
	state:
	{
		selectRequest   : {},
		tables          : [],
		tableColumns    : [],
		tableContent    : {},
		selectedElement : {},
		extensionsLinks : [],
		tableSearch     : '',
	},
	mutations:
	{
		setExtensionsLinks(state, links)
		{
			state.extensionsLinks = links;
		},
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
		setSelectedElement(state, {selectedElement, columns})
		{
			state.selectedElement = selectedElement;
		},

		/**
		 * Установка значания поля
		 * @params {
		 *     fieldValue,
		 *     fieldSettings(основной формат)
		 * }
		 */
		setFieldValue(state,fieldValue)
		{
			let primaryKey = fieldValue.settings.primaryKey;
			for(let tableLine of state.tableContent.items)
			{
				if(tableLine[primaryKey.fieldCode] != primaryKey)
					continue;

				tableLine[fieldValue.settings.fieldCode] = fieldValue;

				break;
			}
		},

		setSearchValue(state, search)
		{
			state.tableSearch = search;
		},
	},
	getters:
	{
		getTableFieldsNames: (state, getters) => tableCode =>
		{
			let table  = getters.getTable(tableCode),
				result = {};
			if (!table || !table.columns)
				return result;

			for (let column in table.columns)
				result[column] = table.columns[column].em.type_info.code;

			return result;
			return [table, tableCode];
		},
		/**
		 * отдает ссылки на дополнения в сайдбар
		 */
		getExtensionsLinks: state => state.extensionsLinks || [],
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
		 * Get column by code
		 */
		getColumn: (store, getters) => (tableCode, columnCode) =>
		{
			let table = getters.getTable(tableCode);

			if(table === false)
				return false;

			for(var index in table.columns)
			{
				if(table.columns[index].field == columnCode)
					return table.columns[index];
			}

			return false;
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
		getColumnSettings: (store, getters) => (tableCode, columnCode, row) =>
		{
			const column         = getters.getColumn(tableCode, columnCode);
			const primaryKeyCode = getters.getPrimaryKeyCode(tableCode);
			let   settings       = column.em.settings;

			settings.primaryKey = {
				value     : (row) ? row[primaryKeyCode] : '',
				fieldCode : primaryKeyCode
			};
			settings.fieldCode  = column.field;
			settings.tableCode  = tableCode;

			return Object.assign({}, settings);
		},
		getTableContent: (state) =>
		{
			const search = state.tableSearch;
			const newTableContent = JSON.parse(JSON.stringify(state.tableContent));

			if (!newTableContent || !newTableContent.items || !newTableContent.items.length) return newTableContent;

			const searchedFields = [];

			for (let columnName in newTableContent.columns_types)
				if (/em_string|em_text|em_int/.test(newTableContent.columns_types[columnName]))
					searchedFields.push(columnName);

			let newTableContentItems = newTableContent.items.filter(item =>
			{
				for (let searchField of searchedFields)
					if (new RegExp(search.trim(), 'i').test(JSON.stringify(item[searchField])))
						return true;
				return false;
			});

			newTableContent.items = newTableContentItems;
			return newTableContent;
		},
	},
	actions:
	{
		/**
		 * получить ссылки на дополнения в сайдбар
		 */
		async setExtensionsLinks(store)
		{
			const result = await axios.get('/extensions/getLinks/');
			store.commit('setExtensionsLinks', result.data.links);
		},
		/**
		 * Достать список таблиц
		 */
		async getTables(store)
		{
			var result = await axios({
				method : 'get',
				url    : '/el/getTables'
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
				url: '/el/select/',
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
			{
				message.error(Vue.prototype.$t('accessDenied'));
				return false;
			}

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
		async duplicateRecord(store, recordParams)
		{
			let result = await axios({
				method : 'post',
				url    : '/el/duplicate/',
				data   : qs.stringify({duplicate:recordParams}),
			});

			if (!result.data.success)
				return false;

			const newContent = store.state.tableContent;
			const duplicatedId = recordParams.where.fields[0].value;
			const duplicatedRow = JSON.parse(JSON.stringify(newContent.items.find(item => item.id === duplicatedId)));
			duplicatedRow.id = result.data.lastId;
			newContent.items.push(duplicatedRow);

			this.commit('setTableContent', newContent);

			return true;
		},
		/**
		 * Remove records or one record
		 * @var reocrdParams {
		 *      rowIndex:<array or index of deletign row>,
		 *      delete:<sql params for deleting>
		 * }
		 */
		async removeRecord(store, recordParams)
		{
			if(typeof recordParams.rowIndex != 'undefined' && typeof recordParams.rowIndex != 'object')
				store.state.tableContent.items.splice(recordParams.rowIndex,1);

			if(typeof recordParams.rowIndex == 'object')
			{
				let curTableCont = store.state.tableContent;
				curTableCont.items = curTableCont.items.filter((itemValue, itemIndex, arr)=>
				{
					return (recordParams.rowIndex.indexOf(itemIndex) != -1)?false:true;
				});
				this.commit('setTableContent',curTableCont);
			}

			return store.dispatch('removeTableRow', recordParams.delete);
		},
		/**
		 * deleteParams:<sql params for deleting>
		 */
		async removeTableRow(store, deleteParams)
		{
			var result = await axios({
				method : 'post',
				url    : '/el/delete/',
				data   : qs.stringify({delete:deleteParams}),
			});

			if (!result.data.success)
				message.error(Vue.prototype.$t('accessDenied'));

			return result;
		},

		/**
		 * Сохранить ширину колонок
		 */
		async saveColumnsWith(store, params)
		{
			var data   = qs.stringify(params);
			var result = await axios({
				method : 'post',
				url    : '/el/setTviewSettings/',
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
				url    : '/el/select/',
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
			{
				message.error(Vue.prototype.$t('accessDenied'));
				return false;
			}
			this.commit('setSelectedElement',{selectedElement:result.data.result.items[0], columns:result.data.result.columns_types});
		},

		/**
		 * Сохранение значения филда (его изменение)
		 * @params {
		 *     value,
		 *     settings(основной формат)
		 * }
		 */
		async saveFieldValue(store, fieldValue)
		{
			let setValues  = {};
			let primaryKey = fieldValue.settings.primaryKey;
			setValues[fieldValue.settings.fieldCode] = fieldValue;
			var data = qs.stringify({
				update:{
					table :fieldValue.settings.tableCode,
					set   :setValues,
					where :{
						operation:'and',
						fields:[
							{
								code      :primaryKey.fieldCode,
								operation :'IS',
								value     :primaryKey,
							}
						]
					}
				}
			});
			let result = await axios.post('/el/update/',data);
			if(!result.data.success)
				message.error(Vue.prototype.$t('accessDenied'));
			this.commit('setFieldValue',fieldValue);
		},

		/**
		 * Сохранение выбранного элемента
		 * @selectedElement {
		 * 	<код филда>:{value,fieldName},
		 * }
		 */
		async saveSelectedElement(store,{selectedElement,tableCode})
		{
			let primaryKeyCode = store.getters.getPrimaryKeyCode(tableCode);
			let setValues  = {};
			for(let fieldCode in selectedElement)
				setValues[fieldCode] = selectedElement[fieldCode];

			var data = qs.stringify({
				update:{
					table :tableCode,
					set   :setValues,
					where :{
						operation:'and',
						fields:[
							{
								code      : primaryKeyCode,
								operation : 'IS',
								value     : selectedElement[primaryKeyCode],
							}
						]
					}
				}
			});
			let result = await axios.post('/el/update/',data);

			if (!result.data.success)
				message.error(Vue.prototype.$t('accessDenied'));

			return result;
		}
	},
};
export default table;
