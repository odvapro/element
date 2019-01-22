<?php


class TableController extends ControllerBase
{
	public function getTableDataAction()
	{
		$tableName = $this->request->getPost('table_name');

		if (empty($tableName))
			return $this->jsonResult(['success' => false, 'message' => 'Нет данных']);

		$result = MysqlAdapter::getTableData($tableName);

		if (!$result)
			return $this->jsonResult(['success' => false, 'message' => 'Не найдены данные']);

		return $this->jsonResult(['success' => true, 'result' => $result]);
	}
	/**
	 * Достать все таблицы бд
	 * @return [type] [description]
	 */
	public function getTablesAction()
	{
		$result = MysqlAdapter::getTables();

		if (!$result)
			return $this->jsonResult(['success' => false, 'message' => 'Таблицы не найдены']);

		return $this->jsonResult(['success' => true, 'tables' => $result]);
	}
	/**
	 * Достать все Колонки таблицы
	 * @return [type] [description]
	 */
	public function getColumnsAction()
	{
		$tableName = $this->request->getPost('table_name');

		if (empty($tableName))
			return $this->jsonResult(['success' => false, 'message' => 'Нет данных']);

		$result = MysqlAdapter::getTableSchema($tableName);

		if (!$result)
			return $this->jsonResult(['success' => false, 'message' => 'Не найдены поля таблицы']);

		return $this->jsonResult(['success' => true, 'columns' => $result]);
	}
}
