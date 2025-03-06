<?php

use Phalcon\Mvc\Model\Resultset;

class PgsqlAdapter extends PdoAdapter
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

				if (is_array($whereValue['value']))
				{
					$values = array_column($whereValue['value'], 'value');

					if(!$values)
						$result[1] = array_merge($result[1], $whereValue['value']);
					else
						$result[1] = array_merge($result[1], array_column($whereValue['value'], 'value'));
				}
				else
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
			$sql .= ' ORDER BY ' . implode(', ', $order);
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
				Phalcon\Db\Enum::FETCH_ASSOC,
				$params
			);
		} catch (Exception $e) {
			Phalcon\Di\Di::getDefault()->get('logger')->error(
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
				Phalcon\Db\Enum::FETCH_ASSOC,
				$params
			);
		} catch (Exception $e) {
			Phalcon\Di\Di::getDefault()->get('logger')->error(
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
				$setStr .= "$setField = NULL, ";
			else {
				$params[] = $setItem;
				$setStr .= "$setField = ?, ";
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
			Phalcon\Di\Di::getDefault()->get('logger')->error(
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

		// echo '<pre>' . htmlentities(print_r($values, true)) . '</pre>';exit();
		if (empty($table) || empty($values))
			return false;

		if (empty($columns) || empty($columns))
			return false;

		$columns   = implode(', ', $columns);

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

		$sql = "INSERT INTO {$table} ({$columns}) VALUES {$valuesStr} RETURNING id";

		try
		{
			$result = $this->db->query($sql, array_merge(...$values));
			$result->setFetchMode(PDO::FETCH_ASSOC);
			
			$lastId = $result->fetch()['id'];

		} catch (Exception $e) {
			Phalcon\Di\Di::getDefault()->get('logger')->error(
				"insertRequest: {$sql}"
			);
			return false;
		}

		return $lastId;
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
			Phalcon\Di\Di::getDefault()->get('logger')->error(
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
			Phalcon\Di\Di::getDefault()->get('logger')->error(
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
			"SELECT table_name FROM information_schema.tables WHERE table_schema = :schema",
			Phalcon\Db\Enum::FETCH_ASSOC,
			["schema" => 'public']
		);

		foreach ($dbTables as $table)
		{
			if(strpos($table['table_name'], 'em_') === 0)
				continue;

			if (empty($tables[$table['table_name']]))
				$tables[$table['table_name']] = [
					'code'    => $table['table_name'],
					'name'    => $table['table_name'],
					'access'  => [[
						'group_id' => Access::ADMINS_GROUP_ID,
						'access'   => Access::FULL,
					]],
				];

			if (isset($table['group_id']) && isset($table['access']))
				$tables[$table['table_name']]['access'][] =
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
			$sql = "SELECT 
				a.attname AS column_name,
				pg_catalog.format_type(a.atttypid, a.atttypmod) AS column_type,
				CASE WHEN i.indisprimary THEN true ELSE false END AS is_pkey,
				CASE WHEN a.attnotnull THEN false ELSE true END AS is_nullable
			FROM pg_attribute a
			JOIN pg_class c ON a.attrelid = c.oid
			JOIN pg_namespace n ON c.relnamespace = n.oid
			LEFT JOIN pg_index i ON i.indrelid = c.oid AND a.attnum = ANY(i.indkey) AND i.indisprimary
			WHERE c.relname = :table
			AND n.nspname = 'public'
			AND a.attnum > 0
			AND NOT a.attisdropped
			ORDER BY a.attnum";

			$res = $this->db->fetchAll($sql, Phalcon\Db\Enum::FETCH_ASSOC, [
				"table"  => $tableName
			]);
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

			$columns[$fieldDbArray['column_name']] = [
				'field'  => $fieldDbArray['column_name'],
				'key'  => $fieldDbArray['is_pkey'] == 1 ? 'PRI' : 'MUL',
				'type' => $fieldDbArray['column_type'],
				'null' => $fieldDbArray['is_nullable'] == 1 ? 'YES' : 'NO',
			];
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