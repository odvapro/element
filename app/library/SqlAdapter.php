<?php

use Phalcon\Mvc\Model\Resultset;

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
		$result = ['', []];
		if (empty($whereArray) || empty($whereArray['fields'])) return $result;

		$whereResult = [];
		foreach ($whereArray['fields'] as $whereKey => $whereValue) {
			if (!isset($whereValue['code_sql'])) continue;
			$whereResult[] = preg_replace("/:value:/", '?', $whereValue['code_sql']);
			if (preg_match("/:value:/", $whereValue['code_sql']))
			{
				if ($whereValue['operation'] === 'CONTAINS' || $whereValue['operation'] === 'DOES NOT CONTAIN')
					$whereValue['value'] = '%'.$whereValue['value'].'%';
				elseif ($whereValue['operation'] === 'ENDS WITH')
					$whereValue['value'] = '%'.$whereValue['value'];
				elseif ($whereValue['operation'] === 'START WITH')
					$whereValue['value'] = $whereValue['value'].'%';

				$result[1][] = $whereValue['value'];
			}
		}
		if (!empty($whereResult)) $result[0] = implode(' '.$whereArray['operation'].' ', $whereResult);
		return $result;
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
		$params        = [];

		if (empty($fromTable))
			return false;

		if (empty($fields))
			$sql = 'SELECT * ';
		else {
			$sqlFields = implode('`, `', $fields);
			$sqlFields = '`'.$sqlFields.'`';
			$sql = "SELECT {$sqlFields} ";
		}

		$sql .= "FROM {$fromTable} ";

		if (!empty($where) && !empty($where['fields']))
		{
			$whereResult = $this->buildWhere($where);

			if (!empty($whereResult[0]))
			{
				$sql .= 'WHERE ' . $whereResult[0];
				$params = array_merge($params, $whereResult[1]);
			}
		}

		if (!empty($order)) {
			$orderValues = [];
			foreach ($order as $orderItemKey => $orderItem) {
				$orderValues[] = '?';
				$params[] = $orderItem;
			}
			$sql .= ' ORDER BY ' . implode(', ', $orderValues);
		}

		if (!empty($limit))
			$sql .= ' LIMIT '.intval($limit);

		if (!empty($offset))
			$sql .= ' OFFSET ' . intval($offset);

		try
		{
			$this->db->prepare($sql);
			$select = $this->db->fetchAll(
				$sql,
				Phalcon\Db::FETCH_ASSOC,
				$params
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
		$fromTable     = isset($requestParams['from']) ? addslashes($requestParams['from']) : '';
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];
		$params        = [];

		$sql = 'SELECT COUNT(*) as count ';
		$sql .= "FROM {$fromTable} ";

		if (!empty($where) && !empty($where['fields'])) {
			$whereResult = $this->buildWhere($where);

			if (!empty($whereResult[0]))
			{
				$sql .= 'WHERE ' . $whereResult[0];
				$params = $whereResult[1];
			}
		}

		try
		{
			$this->db->prepare($sql);
			$select = $this->db->fetchAll(
				$sql,
				Phalcon\Db::FETCH_ASSOC,
				$params
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
		$sql           = '';
		$table         = isset($requestParams['table']) ? $requestParams['table'] : [];
		$set           = isset($requestParams['set']) ? $requestParams['set'] : [];
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];
		$params        = [];

		if (empty($table) || empty($set))
			return false;

		$setStr = '';
		foreach ($set as $setField => $setItem) {
			if ($setItem === null)
				$setStr .= "`$setField` = NULL, ";
			else {
				$params[] = $setItem;
				$setStr .= "`$setField` = ?, ";
			}
		}

		$setStr = preg_replace("/,\s$/", '', $setStr);
		$sql .= "UPDATE {$table} SET $setStr ";

		if (!empty($where) && !empty($where['fields']))
		{
			$whereResult = $this->buildWhere($where);

			if (!empty($whereResult[0]))
			{
				$sql .= 'WHERE ' . $whereResult[0];
				$params = array_merge($params, $whereResult[1]);
			}
		}

		try
		{
			$this->db->prepare($sql);
			$this->db->execute(
				$sql,
				$params
			);
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

		$columns   = implode('`, `', $columns);
		$columns = "`" . $columns . "`";

		$valuesStr = [];
		foreach ($values as $valueSet) {
			$sqlValues = [];
			foreach ($valueSet as $value) {
				$sqlValues[] = "?";
			}
			$sqlValues = "(".implode(', ', $sqlValues).")";
			$valuesStr[] = $sqlValues;
		}
		$valuesStr = implode(', ', $valuesStr);

		$sql = "INSERT INTO {$table} ({$columns}) VALUES {$valuesStr}";

		try
		{
			$this->db->execute($sql, array_merge(...$values));
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

		$sqlColumns = implode('`, `', $columns);
		$sqlColumns = '`'.$sqlColumns.'`';
		$sql = "INSERT INTO {$table} ({$sqlColumns})
				SELECT {$sqlColumns}
				FROM {$table}
				WHERE id = ?";
		try
		{
			$this->db->prepare($sql);
			$this->db->execute($sql, [$id]);
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
		$sql           = '';
		$table         = isset($requestParams['table']) ? $requestParams['table'] : [];
		$where         = isset($requestParams['where']) ? $requestParams['where'] : [];
		$params        = [];

		if (empty($table))
			return false;

		$sql .= "DELETE FROM {$table} ";

		if (!empty($where) && !empty($where['fields']))
		{
			$whereResult = $this->buildWhere($where);

			if (!empty($whereResult[0]))
			{
				$sql .= 'WHERE ' . $whereResult[0];
				$params = array_merge($params, $whereResult[1]);
			}
		}

		try
		{
			$this->db->prepare($sql);
			$this->db->execute(
				$sql,
				$params
			);
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
