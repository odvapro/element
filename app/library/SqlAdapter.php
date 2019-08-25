<?php

use Phalcon\Mvc\Model\Resultset;

class SqlAdapter extends PdoAdapter
{
	private $db = false;

	/**
	 * Экранировать специальные символы
	 * @param  string or array
	 * @return string or array
	 */
	private function escapeRealStr($params)
	{
		if (!is_array($params))
			return quotemeta($params);

		foreach ($params as &$item)
		{
			if (!is_array($item))
			{
				$item = quotemeta($item);
				continue;
			}

			$item = $this->escapeRealStr($item);
		}

		return $params;
	}
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

	/**
	 * конструктор
	 */
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
		$requestParams = $this->escapeRealStr($requestParams);
		$sql           = '';
		$fields        = isset($requestParams['fields']) ? $requestParams['fields'] : [];
		$fromTable     = isset($requestParams['from']) ? $requestParams['from'] : [];
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];
		$order         = isset($requestParams['order']) ? $requestParams['order'] : [];
		$limit         = isset($requestParams['limit']) ? $requestParams['limit'] : '';
		$offset        = isset($requestParams['offset']) ? $requestParams['offset'] : '';

		if (empty($fromTable))
			return false;

		if (empty($fields))
			$sql = 'SELECT * ';
		else
			$sql = 'SELECT ' . implode(', ', $fields) . ' ';

		$sql .= "FROM {$fromTable} ";

		if (!empty($where) && !empty($where['fields']))
			$sql .= 'WHERE ' . $this->buildWhere($where);

		if (!empty($order))
			$sql .= ' ORDER BY ' . implode(', ', $order);

		if (!empty($limit))
			$sql .= ' LIMIT ' . $limit;

		if (!empty($offset))
			$sql .= ' OFFSET ' . $offset;

		try
		{
			$select = $this->db->fetchAll(
				$sql,
				Phalcon\Db::FETCH_ASSOC
			);
		} catch (Exception $e) {
			Phalcon\Di::getDefault()->get('logger')->error(
				"selectError: {$e->getMessage()}"
			);
			return false;
		}

		return $select;
	}

	/**
	 * Gets count of element
	 * @param  $requestParams from
	 * @return int
	 */
	public function count($requestParams)
	{
		$requestParams = $this->escapeRealStr($requestParams);

		$sql           = '';
		$fromTable     = isset($requestParams['from']) ? $requestParams['from'] : [];
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];

		if (!empty($where) && !empty($where['fields']))
			$sql .= 'WHERE ' . $this->buildWhere($where);

		$sql = 'SELECT COUNT(*) as count ';
		$sql .= "FROM {$fromTable} ";

		if (!empty($where) && !empty($where['fields']))
			$sql .= 'WHERE ' . $this->buildWhere($where);

		try
		{
			$select = $this->db->fetchAll(
				$sql,
				Phalcon\Db::FETCH_ASSOC
			);
		} catch (Exception $e) {
			Phalcon\Di::getDefault()->get('logger')->error(
				"countError: {$e->getMessage()}"
			);
			return false;
		}

		return reset($select)['count'];
	}

	/**
	 * update table
	 * @param  array
	 * @return array
	 */
	public function update($requestParams)
	{
		$requestParams = $this->escapeRealStr($requestParams);
		$sql           = '';
		$table         = isset($requestParams['table']) ? $requestParams['table'] : [];
		$set           = isset($requestParams['set']) ? $requestParams['set'] : [];
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];

		if (empty($table) || empty($set))
			return false;

		$sql .= "UPDATE {$table} SET " . implode(', ', $set) . " ";

		if (!empty($where))
			$sql .= 'WHERE ' . $this->buildWhere($where);

		try
		{
			$this->db->execute($sql);
		} catch (Exception $e) {
			Phalcon\Di::getDefault()->get('logger')->error(
				"updateRequest: {$sql}"
			);
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
		$sql           = '';
		$table         = isset($requestParams['table']) ? $requestParams['table'] : [];
		$columns       = isset($requestParams['columns']) ? $requestParams['columns'] : [];
		$values        = isset($requestParams['values']) ? $requestParams['values'] : [];

		if (empty($table) || empty($values))
			return false;

		if (empty($columns) || empty($columns))
			return false;

		$table   = $this->escapeRealStr($table);
		$columns = $this->escapeRealStr($columns);

		$columns   = implode(', ', $columns);
		$sqlValues = [];

		foreach ($values as $insertValue)
			$sqlValues[] = "?";

		$sqlValues = implode(', ', $sqlValues);
		$sql = "INSERT INTO {$table} ({$columns}) VALUES ({$sqlValues })";

		try
		{
			$this->db->execute($sql,$values);
		} catch (Exception $e) {
			Phalcon\Di::getDefault()->get('logger')->error(
				"insertRequest: {$sql}"
			);
			return false;
		}

		return true;
	}
	/**
	 * delete from table
	 * @param  array
	 * @return array
	 */
	public function delete($requestParams)
	{
		$requestParams = $this->escapeRealStr($requestParams);
		$sql           = '';
		$table         = isset($requestParams['table']) ? $requestParams['table'] : [];
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];

		if (empty($table))
			return false;

		$sql .= "DELETE FROM {$table} ";

		if (!empty($where))
			$sql .= 'WHERE ' . $this->buildWhere($where);

		try
		{
			$this->db->execute($sql);
		} catch (Exception $e) {
			Phalcon\Di::getDefault()->get('logger')->error(
				"deleteRequest: {$sql}"
			);
			return false;
		}

		return true;
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
			if(strpos($table['TABLE_NAME'], 'em_') === 0)
				continue;

			$tables[] = [
				'code'    => $table['TABLE_NAME'],
				'name'    => $table['TABLE_NAME'],
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

		$columns = [];
		foreach ($res as &$fieldDbArray)
		{
			if (is_array($fieldDbArray))
				$fieldDbArray = array_change_key_case($fieldDbArray);

			$columns[$fieldDbArray['field']] = $fieldDbArray;
		}

		return $columns;
	}

	/**
	 * Return last inserted id
	 * @return int
	 */
	public function getLastInsertId()
	{
		return $this->db->lastInsertId();
	}
}