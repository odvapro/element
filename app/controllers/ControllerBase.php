<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	public $tables;

	public function initialize()
	{
		$config = $this->di->get('config');

		// определение всех таблиц системы для вывода
		$this->tables = $this->_getTables();
		$this->view->setVar('tables',$this->tables);

		// некоторые важнве переменные
		$this->view->setVar('baseUri',$config->application->baseUri);
		$this->view->setVar('controllerName',$this->router->getControllerName());

	}

	/**
	 * Возврощает все таблицы с измененными имениями
	 */
	private function _getTables()
	{
		$db     = $this->di->get('db');
		$config = $this->di->get('config');


		// системные таблицы которые не нужно нигде выводить
		$systemTables = array('em_names','em_types','em_users');
		
		// достаем все имена таблиц, исключаем системные
		// связываем их с другими
		$tables = [];
		$db_tables = $db->fetchAll(
			"SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA=:database",
			Phalcon\Db::FETCH_ASSOC,
			array('database'=>$config->database->dbname)
		);
		foreach ($db_tables as $tbl)
		{
			if(!in_array($tbl['table_name'], $systemTables))
			{
				$tables[$tbl['table_name']] = array
				(
					'table_name' => $tbl['table_name']
				);
			}
		}

		// ищем названия только для таблиц (type=0)
		$named_tables = EmNames::find(['conditions'=>'type = 0']);
		foreach($named_tables as $key => $table)
		{
			if(array_key_exists($table->table, $tables))
			{
				$tables[$table->table]['table_name'] = $table->name;
			}
		}
		return $tables;
	}

	public function pageNotFound()
	{
		if(!$this->request->isAjax())
		{
			$this->response->redirect('/notfound/');
			$this->view->disable();
		}
		else
		{
			$this->jsonResult(['result'=>'error','msg'=>'not found']);
		}
	}

	public function jsonResult($data)
	{
		echo json_encode($data);
		$this->view->disable();
		return;
	}
}
