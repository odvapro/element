<?php

class SqlAdapter extends PdoAdapter
{
	private $db = false;

	/**
	 * build where
	 * @param  array
	 * @return string
	 */
	private function buildWhere($whereArray)
	{
		$sqlWhere = '';

		if(empty($whereArray['fields']))
		{
			switch ($whereArray['operation']) {
				case 'IS':
					return $whereArray['code'] . ' = ' . "'" . $whereArray['value'] . "'";
					break;

				case 'IS NOT':
					return $whereArray['code'] . ' <> ' . "'" . $whereArray['value'] . "'";
					break;

				case 'CONTAINS':
					return $whereArray['code'] . ' LIKE ' . "'%" . $whereArray['value'] . "%'";
					break;

				case 'DOES NOT CONTAIN':
					return $whereArray['code'] . ' NOT LIKE ' . "'%" . $whereArray['value'] . "%'";
					break;

				case 'START WITH':
					return $whereArray['code'] . ' LIKE ' . "'" . $whereArray['value'] . "%'";
					break;

				case 'ENDS WITH':
					return $whereArray['code'] . ' LIKE ' . "'%" . $whereArray['value'] . "'";
					break;

				case 'IS EMPTY':
					return $whereArray['code'] . ' = ' . '""';
					break;

				case 'IS NOT EMPTY':
					return $whereArray['code'] . ' <> ' . '""';
					break;
			}
		}

		$fieldsSqls = [];

		foreach ($whereArray['fields'] as $field)
		{
			$fieldsSqls[] = $this->buildWhere($field);
		}
		$fieldsSqls = '(' . implode(' ' . $whereArray['operation'] . ' ', $fieldsSqls) . ')';
		return $fieldsSqls;
	}

	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * select data from table
	 * @param  array
	 * @return array
	 */
	public function select($requestParams)
	{
		$sql       = '';
		$fields    = isset($requestParams['fields']) ? $requestParams['fields'] : [];
		$fromTable = isset($requestParams['from']) ? $requestParams['from'] : [];
		$where     = isset($requestParams['where']) ? $requestParams['where'] : [];
		$order     = isset($requestParams['order']) ? $requestParams['order'] : [];

		if (empty($fromTable))
			return false;

		if (empty($fields))
			$sql = 'select * ';
		else
			$sql = 'select ' . implode(', ', $fields) . ' ';

		$sql .= "from {$fromTable} ";

		if (!empty($where))
			$sql .= 'where ' . $this->buildWhere($where);

		if (!empty($order))
			$sql .= ' order by ' . implode(', ', $order);

		try
		{
			$select = $this->db->fetchAll(
				$sql,
				Phalcon\Db::FETCH_ASSOC,
				[ 'database' => $this->db->getDescriptor()['dbname']]
			);
		} catch (Exception $e) {
			return false;
		}

		return $select;
	}

	/**
	 * update table
	 * @param  array
	 * @return array
	 */
	public function update($requestParams)
	{
		$sql   = '';
		$table = isset($requestParams['table']) ? $requestParams['table'] : [];
		$set   = isset($requestParams['set']) ? $requestParams['set'] : [];
		$where = isset($requestParams['where']) ? $requestParams['where'] : [];

		if (empty($table) || empty($set))
			return false;

		$sql .= "update {$table} set " . implode(', ', $set) . " ";

		if (!empty($where))
			$sql .= 'where ' . $this->buildWhere($where);

		try
		{
			$this->db->execute($sql);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}
	/**
	 * insert into table
	 * @param  array
	 * @return array
	 */
	public function insert($requestParams)
	{
		$sql     = '';
		$table   = isset($requestParams['table']) ? $requestParams['table'] : [];
		$columns = isset($requestParams['columns']) ? $requestParams['columns'] : [];
		$values  = isset($requestParams['values']) ? $requestParams['values'] : [];

		if (empty($table) || empty($values))
			return false;

		$sql = "INSERT INTO {$table} ";

		if (!empty($columns))
			$sql .= '(' . implode(', ', $columns) . ' ) ';

		$sql .= 'VALUES (' . implode(', ', $values) . ' )';

		try
		{
			$this->db->execute($sql);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}
	public function delete()
	{

	}

	/**
	 * Get tables list
	 * @return array
	 */
	public function getTables()
	{
		$tables = [];
		$dbTables = $this->db->fetchAll(
			"SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA=:database",
			Phalcon\Db::FETCH_ASSOC,
			[ 'database' => $this->db->getDescriptor()['dbname'] ]
		);
		foreach ($dbTables as $table)
		{
			if(strpos($table['TABLE_NAME'], 'em_') === 0) continue;
			$tables[] = [
				'code'    => $table['TABLE_NAME'],
				'name'    => false
			];
		}

		return $tables;
	}

	/**
	 * Get tables column
	 * @return array
	 */
	public function getColumns($tableName)
	{
		if (empty($tableName))
			return false;

		try
		{
			$res = $this->db->fetchAll("SHOW COLUMNS  FROM " . $tableName, Phalcon\Db::FETCH_ASSOC);
		}
		catch (Exception $e)
		{
			return false;
		}

		return $res;
	}
}