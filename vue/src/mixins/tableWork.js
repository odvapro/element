export default
{
	methods:
	{
		/**
		 * Достать таблицу по коду
		 */
		getTableByCode(code, tables)
		{
			for (let table of tables)
				if (table.code == code)
					return table;
		}
	}
}