<?php

class TableController extends ControllerBase
{
	/**
	 * Работа с таблицами, содержимое таблицы
	 */
	public function indexAction($tableCode = false)
	{
		$table = $this->_setActiveTable($tableCode);
		$params         = $this->dispatcher->getParams();
		$tableViewIndex = array_search('view', $params);
		$tableViewId    = false;
		if($tableViewIndex !== false)
			$tableViewId = $params[$tableViewIndex+1];

		// check tables exsitance
		// make filter
		// select need lines from table
		// make line values by field types
		$tableInfo = $this->tableEditor->getTableInfo($tableCode);
		if($tableInfo === false)
			return $this->pageNotFound();
		$this->view->setVar('tableInfo',$tableInfo);

		// find tableView
		$tableViews = EmViews::find([
			'conditions' => 'table = ?0',
			'bind'       => [$tableInfo['table']]
		]);
		$this->view->setVar('tableViews',$tableViews);
		$currentTableView = false;
		if($tableViewId !== false)
			$currentTableView =  EmViews::findFirst($tableViewId);
		$this->view->setVar('currentTableView',$currentTableView);

		// default mode is sql
		$tableViewType = ($currentTableView === false)?'em_sql':$currentTableView->type;
		$tView         = $this->tableviews->getTypeObject($tableViewType);
		$tView->setView($currentTableView);
		$tView->setTableInfo($tableInfo);
		$tView->makeViewLogic();
	}

	/**
	 * Страница с формой добавления элемента
	 */
	public function addAction($tableName = false)
	{
		$activeTable = $this->_setActiveTable($tableName);

		// достаем информацию о таблице
		$curTable = $activeTable;
		$curTable['fields'] = $this->tableEditor->getTableFilelds($activeTable['real_name']);

		// определяем адреса форм редактирования полей
		foreach ($curTable['fields'] as &$field)
		{
			if(!is_null($this->fields->{$field['type']}))
			{
				$this->fields->{$field['type']}->prolog($field['settings']);
				$field['formPath'] = $this->fields->{$field['type']}->getEditFieldPath();
			}
			$field['formPath'] = (!empty($field['formPath']))?'fields/'.$field['formPath']:'table/notSystemEditField';
		}

		$this->view->setVar('tableInfo',$curTable);
		$this->view->setVar('sysTypes',$this->tableEditor->systemEmTypes);
	}

	/**
	 * Страница с формой редактирования элемента
	 * @return всегда  json так как работает только при аякс запросах
	 */
	public function editAction($tableName = false, $elementId = false)
	{
		$activeTable = $this->_setActiveTable($tableName);
		$elementId = intval($elementId);
		if($elementId < 1)
		{
			$this->pageNotFound();
			return false;
		}

		// достаем информацию о таблице
		$curTable           = $activeTable;
		$curTable['fields'] = $this->tableEditor->getTableFilelds($activeTable['real_name']);
		$curTableFields     = $curTable['fields'];
		$primaryKey         = $this->tableEditor->getPrimaryKey($activeTable['real_name']);

		// определяем адреса форм редактирования полей
		foreach ($curTable['fields'] as  &$field)
		{
			if(!is_null($this->fields->{$field['type']}))
			{
				$this->fields->{$field['type']}->prolog($field['settings']);
				$field['formPath'] = $this->fields->{$field['type']}->getEditFieldPath();
			}
			$field['formPath'] = (!empty($field['formPath']))?'fields/'.$field['formPath']:'table/notSystemEditField';
		}
		$this->view->setVar('tableInfo',$curTable);
		$this->view->setVar('sysTypes',$this->tableEditor->getFeieldTypes());
		$this->view->setVar('primaryKey',$primaryKey);

		$element = $this->tableEditor->getElement($tableName, ['field'=>$primaryKey, 'value'=>$elementId]);

		// подгон под типы полей
		foreach ($curTableFields as &$field)
		{
			if(!is_null($this->fields->{$field['type']}))
				$element[$field['field']] = $this->fields->{$field['type']}->getValue($element[$field['field']],$field['settings']);
			else
				$element[$field['field']] = htmlspecialchars($element[$field['field']]);
		}
		$this->view->setVar('element',$element);
	}

	/**
	 * Сохранение/добавление элемента
	 * @return всегда  json так как работает только при аякс запросах
	 */
	public function saveAction()
	{
		if(!$this->request->isAjax())
			return $this->jsonResult(['result'=>'error','msg'=>'only ajax']);


		// тип сохранения - обновление/добавление
		// и все нужные поля
		$editMode  = $this->request->getPost('editMode');
		$tableName = $this->request->getPost('tableName');
		$field     = $this->request->getPost('field');

		// валидация
		// для этого достаем полное описание таблицы
		$curTable    = [];
		$curTable['fields'] = $this->tableEditor->getTableFilelds($tableName);

		// проход по всем полям, проверка важное это поле или нет
		$validationErrors = [];
		$formData         = [];
		$primaryKeyName   = $this->tableEditor->getPrimaryKey($tableName);
		$primaryKeyValue = (!empty($field[$primaryKeyName]))?intval($field[$primaryKeyName]):false;
		$primaryKey = [
			'field' => $primaryKeyName,
			'value' => $primaryKeyValue
		];

		foreach ($curTable['fields'] as $key => $fieldArr)
		{
			$required = ( !empty($fieldArr['required']) && $fieldArr['required'] == 1  || $fieldArr['null'] == "NO" )?true:false;
			$required = ($fieldArr['extra'] == "auto_increment")?false:$required;
			if($required && empty($field[$fieldArr['field']]))
				$validationErrors[] = $fieldArr['field'].' required';
			else
				if(!empty($field[$fieldArr['field']]))
					if(!is_null($this->fields->{$fieldArr['type']}))
						$formData[$fieldArr['field']] = $this->fields->{$fieldArr['type']}->saveValue($field[$fieldArr['field']],$fieldArr,$tableName,$primaryKey);
					else
						$formData[$fieldArr['field']] = $field[$fieldArr['field']];
				else
					$formData[$fieldArr['field']] = null;
		}

		// добавление или обновление после проверок валидиции
		if(count($validationErrors))
			return $this->jsonResult([
				'result' => 'error',
				'msg'    => implode(';<br/>', $validationErrors)
			]);

		$res       = false;
		$sqlErrors = [];
		if($editMode == 'add')
			$res = $this->tableEditor->insert($tableName, $formData, $sqlErrors);
		else
			$res = $this->tableEditor->update($tableName, $primaryKey, $formData, $sqlErrors);

		if(!$res)
			return $this->jsonResult(['result'=>'error', 'msg'=>$sqlErrors ]);

		if($editMode == 'add')
			return $this->jsonResult(['result'=>'success','elId'=>$res]);
		else
			return $this->jsonResult([
				'result' => 'success',
				'msg'    => 'элемент сохранен',
				'type'   => 'update'
			]);
	}

	/**
	 * Удаление записи из таблицы
	 */
	public function deleteAction($tableName = false, $primaryKey = false, $elementId = false)
	{
		if(!$this->request->isAjax())
			return $this->jsonResult(['result'=>'error', 'msg'=>'только ajax']);

		if(empty($tableName) || empty($primaryKey) ||  empty($elementId) )
			return $this->jsonResult(['result'=>'error', 'msg'=>'не все нужные поля']);

		$sqlErrors = [];
		if($this->tableEditor->delete($tableName, ['field'=>$primaryKey, 'value'=>intval($elementId)], $sqlErrors))
			return $this->jsonResult(['result'=>'success']);
		else
			return $this->jsonResult(['result'=>'error', 'msg'=>$sqlErrors]);
	}

	/*HELPERS*/
	/**
	 * Активирует нужную таблицу и переопределяет переменную
	 * @return array Возврощает активную таблицу
	 */
	private function _setActiveTable($tableName = false)
	{
		// активируем нужную таблицу и переопределяем все таблицы в шаблоне
		if(!empty($tableName) && array_key_exists($tableName, $this->sidebarTables))
		{
			$this->sidebarTables[$tableName]['classes'] = 'act';
			$this->view->setVar('sidebarTables',$this->sidebarTables);
			$activeTable = array_merge($this->sidebarTables[$tableName],array('real_name'=>$tableName));
			$this->view->setVar('curTable',$activeTable);
			return $activeTable;
		}
		else
		{
			$this->pageNotFound();
			return false;
		}
	}

	public function addViewAction()
	{
		$tViewName = $this->request->getPost('tViewName');
		$tableName = $this->request->getPost('tableName');
		if(empty($tViewName) || empty($tableName))
			return $this->jsonResult(['success'=>false]);
		$newTview = new EmViews();
		$newTview->name  = $tViewName;
		$newTview->table = $tableName;
		$newTview->type  = 'em_sql';
		$newTview->save();
		return $this->jsonResult(['success'=>true,'url'=>$newTview->getUrl()]);
	}

	public function saveViewAction()
	{
		$columns = $this->request->getPost('columns');
		$filter  = $this->request->getPost('filter');
		$sort    = $this->request->getPost('sort');
		$viewid    = $this->request->getPost('viewid');
		if(empty($viewid))
			return $this->jsonResult(['success'=>false]);
		$tView = EmViews::findFirst($viewid);
		if(!$tView)
			return $this->jsonResult(['success'=>false]);

		$tView->filter  = $filter;
		$tView->sort    = $sort;
		$tView->columns = $columns;
		$tView->save();
		return $this->jsonResult(['success'=>true]);
	}
}
