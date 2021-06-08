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
		if(empty($whereArray['fields']))
			return $whereArray['code'];

		$fieldsSqls = [];
		foreach ($whereArray['fields'] as $field)
			$fieldsSqls[] = $this->buildWhere($field);

		$fieldsSqls = array_filter($fieldsSqls);

		if (empty($fieldsSqls))
			return "";

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
		{
			$whereSql = $this->buildWhere($where);
			if (!empty($whereSql)) $sql .= 'WHERE ' . $whereSql;
		}

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
	 * Duplicate row in table
	 * @param array
	 * @return array
	 */
	public function duplicate($requestParams)
	{
		$table   = isset($requestParams['table']) ? $requestParams['table'] : null;
		$id      = isset($requestParams['where']['fields'][0]['value']) ? $requestParams['where']['fields'][0]['value'] : null;
		$columns = isset($requestParams['columns']) ? $requestParams['columns'] : null;

		if (empty($table) || empty($id) || empty($columns))
			return false;

		$sqlColumns = implode(', ', $columns);
		$sql = "INSERT INTO {$table} ({$sqlColumns})
				SELECT {$sqlColumns}
				FROM {$table}
				WHERE id = {$id}";
		try
		{
			$this->db->execute($sql, $sqlColumns);
		} catch (Exception $e) {
			Phalcon\Di::getDefault()->get('logger')->error(
				"duplicateRequest: {$sql}"
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
			"SELECT t.TABLE_NAME, gt.group_id, gt.access
			FROM information_schema.TABLES AS t
			LEFT JOIN em_groups_tables AS gt ON gt.table_name = t.TABLE_NAME
			WHERE TABLE_TYPE='BASE TABLE'
			AND TABLE_SCHEMA=:database
			ORDER BY t.TABLE_NAME",
			Phalcon\Db::FETCH_ASSOC,
			[ 'database' => $this->db->getDescriptor()['dbname'] ]
		);

		foreach ($dbTables as $table)
		{
			if(strpos($table['TABLE_NAME'], 'em_') === 0)
				continue;

			if (empty($tables[$table['TABLE_NAME']]))
				$tables[$table['TABLE_NAME']] = [
					'code'    => $table['TABLE_NAME'],
					'name'    => $table['TABLE_NAME'],
					'access'  => [[
						'group_id' => Access::ADMINS_GROUP_ID,
						'access'   => Access::FULL,
					]],
				];

			if (isset($table['group_id']) && isset($table['access']))
				$tables[$table['TABLE_NAME']]['access'][] =
				[
					'group_id' => $table['group_id'],
					'access'   => $table['access'],
				];
		}

		return array_values($tables);
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
		return $this->db->query('SELECT LAST_INSERT_ID()')->fetch()[0];
	}
}
