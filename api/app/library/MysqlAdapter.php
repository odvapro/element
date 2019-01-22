<?php

class MysqlAdapter extends DataBaseAdapter
{
	/**
	 * Достать содержимое таблицы
	 */
	public static function getTableData($tableName)
	{
		if(empty($tableName))
			return false;

		$db = \Phalcon\Di::getDefault()->get('db');

		$data = $db->fetchAll(
			"SELECT * FROM " . $tableName,
			Phalcon\Db::FETCH_ASSOC
		);

		return  $data;
	}
	/**
	 * Достать настройки колонок таблицы
	 */
	public static function getTableSchema($tableName)
	{
		if(empty($tableName))
			return false;

		$db = \Phalcon\Di::getDefault()->get('db');

		$tableColumns = $db->fetchAll(
			"SHOW COLUMNS  FROM " . $tableName,
			Phalcon\Db::FETCH_ASSOC
		);

		// достаем переопределения колонок
		// $emNames = EmNames::find(['conditions'=>"table = ?0 AND field != ''",'bind'=>[$tableName]]);
		// $ovverides = [];
		// foreach ($emNames as $emName)
			// $ovverides[$emName->field] = ['ename'=>$emName->name, 'tab'=>$emName->tab];

		// foreach ($tableColumns as &$col)
		// {
		// 	if(!empty($ovverides[$col['field']]))
		// 	{
		// 		$col['ename'] = $ovverides[$col['field']]['ename'];
		// 		$col['tab']   = $ovverides[$col['field']]['tab'];
		// 	}
		// 	else
		// 	{
		// 		$col['tab']   = false;
		// 		$col['ename'] = '';
		// 	}
		// }
		return  $tableColumns;
	}
	/**
	 * Достать таблицы
	 */
	public static function getTables()
	{
		$db     = \Phalcon\Di::getDefault()->get('db');
		$config = \Phalcon\Di::getDefault()->get('config');

		// системные таблицы которые не нужно нигде выводить
		$systemTables = ['em_names', 'em_types', 'em_users', 'em_views', 'em_tabs'];

		// достаем все имена таблиц, исключаем системные
		// связываем их с другими
		$tables = [];
		$db_tables = $db->fetchAll(
			"SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA=:database",
			Phalcon\Db::FETCH_ASSOC,
			[
				'database' => $config->database->dbname
			]
		);

		if (empty($db_tables))
			return false;

		foreach ($db_tables as $tbl)
		{
			if(!in_array($tbl['table_name'], $systemTables))
			{
				$tables[$tbl['table_name']] =
				[
					'table_name' => $tbl['table_name']
				];
			}
		}

		// ищем названия только для таблиц (type=0)
		// $named_tables = EmNames::find(['conditions'=>"field = ''"]);
		// foreach($named_tables as $key => $table)
		// {
		// 	if(array_key_exists($table->table, $tables))
		// 	{
		// 		$tables[$table->table]['table_name'] = $table->name;
		// 		$tables[$table->table]['show']       = $table->show;
		// 	}
		// 	if($table->show == 1 && array_key_exists($table->table, $tables))
		// 		$shownTables[$table->table] = $tables[$table->table];
		// }
		return $tables;
	}
}