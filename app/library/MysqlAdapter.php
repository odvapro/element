<?php

class MysqlAdapter extends SqlAdapter
{
	private $db = false;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function select()
	{

	}
	public function update()
	{

	}
	public function insert()
	{

	}
	public function delete()
	{

	}

	/**
	 * Достать список таблиц
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

	public function getTableSchema($tableName)
	{
		if(empty($tableName))
			return false;

		$tableColumns = $this->db->fetchAll(
			"SHOW COLUMNS  FROM " . $tableName,
			Phalcon\Db::FETCH_ASSOC
		);

		return $tableColumns;
	}
}