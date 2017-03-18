<?php

class TableController extends ControllerBase
{
	/**
	 * Работа с таблицами, содержимое таблицы
	 */
	public function indexAction($tableName = false, $page = 1)
	{
		$activeTable = $this->_setActiveTable($tableName);
		$page = intval($page);
		if($page < 1 || !$activeTable)
		{
			$this->pageNotFound();
			return false;
		}

		// достаем информацию о таблице
		$curTable = $activeTable;
		$curTable['fields'] = $this->tableEditor->getTableFilelds($activeTable['real_name']);
		$this->view->setVar('tableName',$tableName);

		// ширина таблицы, менятся относительно того сколько полей в ней
		$tableWidth = count($curTable['fields'])*150;
		$this->view->setVar('tableWidth',$tableWidth);

		//достаем все что есть в этой таблице
		$db       = $this->di->get('db');
		$limit    = 20;
		$from     = $limit*(intval($page)-1);
		$sqlWhere = $activeTable['real_name'];
		
		// Sorting
		$sortString = ' ';
		$sort       = $this->request->get('sort');
		if($sort)
		{
			$sortDir = $this->request->get('sortdir');
			$sortString = ' ORDER BY  '.$sort;
			if(!empty($sortDir))
				$sortString .= ' '.$sortDir; 
			else
				$sortString .= ' DESC'; 
		}

		$tableResult = $db->fetchAll(
			"SELECT * FROM ".$sqlWhere." ".$sortString." LIMIT $from,$limit",
			Phalcon\Db::FETCH_ASSOC
		);

		// подгон результатов по типам, для вывода 
		$fieldTypes = [];
		$primaryKey = false;
		$settingsArr = [];
		foreach ($curTable['fields'] as &$field)
		{
			$fieldTypes[$field['field']] = $field['type'];
			if($field['key'] == 'PRI')
				$primaryKey = $field['field'];
			
			$field['sort'] = 'desc';
			if(!empty($sort) && $sort == $field['field'] && !empty($sortDir))
				$field['sort'] = $sortDir;

			$fieldName = $field['field'];
			$settingsArr[$field['field']] = (!empty($field['settings']))?$field['settings']:[];
			
			// прописываем каждому типу поля свое отображение
			if(!is_null($this->fields->{$fieldTypes[$fieldName]}))
			{
				$this->fields->{$fieldTypes[$fieldName]}->prolog($field['settings'],true);
				$field['valueFieldPath'] =  $this->fields->{$fieldTypes[$fieldName]}->getValueFieldPath();
			}

		}
		$this->view->setVar('tableInfo',$curTable);
		$this->view->setVar('tableFieldsCount',count($curTable['fields'])+1);


		foreach($tableResult as &$tRes)
			foreach($tRes as $fieldName => &$fieldVal)
				if(!is_null($this->fields->{$fieldTypes[$fieldName]}))
					$fieldVal = $this->fields->{$fieldTypes[$fieldName]}->getValue($fieldVal,$settingsArr[$fieldName],true);

		$this->view->setVar('primaryKey',$primaryKey);
		$this->view->setVar('tableResult',$tableResult);

		// постраничная навигация
		$tableResultCount = $db->fetchOne(
			"SELECT count(*) as elementcount FROM ".$sqlWhere,
			Phalcon\Db::FETCH_ASSOC
		);
		$pagination                          = [];
		$pagination['curPage']               = $page;
		$pagination['countOfPages']          = ceil($tableResultCount['elementcount']/$limit);
		$pagination['countOfElements']       = $tableResultCount['elementcount'];
		$pagination['countOfElementsOnPage'] = $limit;
		$pagination['fromPage']              = ( ($page - 3)<1 )?1:($page - 3);
		$pagination['toPage']                = ( ($page + 7)>$pagination['countOfPages'] )?$pagination['countOfPages']:($page + 7);
		$this->view->setVar('pagination',$pagination);
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
		
		// определяем адреса форм редактирования полей
		$primaryKey = false;
		foreach ($curTable['fields'] as  &$field)
		{
			if(!is_null($this->fields->{$field['type']}))
			{
				$this->fields->{$field['type']}->prolog($field['settings']);
				$field['formPath'] = $this->fields->{$field['type']}->getEditFieldPath();
			}
			$field['formPath'] = (!empty($field['formPath']))?'fields/'.$field['formPath']:'table/notSystemEditField';

			if($field['key'] == 'PRI')
				$primaryKey = $field['field'];
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
		if($this->request->isAjax())
		{
			// тип сохранения - обновление/добавление
			// и все нужные поля
			$editMode  = $this->request->getPost('editMode');
			$tableName = $this->request->getPost('tableName');
			$field     = $this->request->getPost('field');

			// валидация
			// для этого достаем полное описание таблицы
			$columns     = $this->tableEditor->getTableColumns($tableName);
			$overColumns = $this->tableEditor->getOverTableColumns($tableName);
			$curTable    = [];
			$curTable['fields'] = $this->tableEditor->getOverTable($columns,$overColumns);

			// проход по всем полям, проверка важное это поле или нет
			$validationErrors = [];
			$formData         = [];
			$primaryKey       = [];
			foreach ($curTable['fields'] as $key => $fieldArr)
				if($fieldArr['key'] == 'PRI' && !empty($field[$fieldArr['field']]))
				{
					$primaryKey = [
						'field' => $fieldArr['field'],
						'value' => intval($field[$fieldArr['field']])
					];
					break;
				}
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

				if($fieldArr['key'] == 'PRI' && !empty($field[$fieldArr['field']]))
					$primaryKey = [
						'field' => $fieldArr['field'],
						'value' => intval($field[$fieldArr['field']])
					];
			}

			// добавление или обновление после проверок валидиции 
			if(!count($validationErrors))
			{
				$res       = false;
				$sqlErrors = [];
				if($editMode == 'add')
					$res = $this->tableEditor->insert($tableName, $formData, $sqlErrors);
				else
					$res = $this->tableEditor->update($tableName, $primaryKey, $formData, $sqlErrors);
				
				if($res)
				{
					if($editMode == 'add')
						$this->jsonResult(['result'=>'success','elId'=>$res]);
					else
						$this->jsonResult(['result'=>'success','msg'=>'элемент сохранен', 'type'=>'update']);
				}
				else
					$this->jsonResult(['result'=>'error', 'msg'=>$sqlErrors ]);
			}
			else
				$this->jsonResult(['result'=>'error', 'msg'=>implode(';<br/>', $validationErrors)]);
		}
		else
			$this->jsonResult(['result'=>'error']);
	}

	/**
	 * Удаление записи из таблицы
	 */
	public function deleteAction($tableName = false, $primaryKey = false, $elementId = false)
	{
		if($this->request->isAjax())
		{
			if(!empty($tableName) || !empty($primaryKey) ||  !empty($elementId) )
			{	
				// TODO
				// запустить для всех типов полей сохранение пустоты
				// для удаления мусора в виде файлов, которые потеряют ссылки с бд
				$sqlErrors = [];
				if($this->tableEditor->delete($tableName, ['field'=>$primaryKey, 'value'=>intval($elementId)], $sqlErrors))
					$this->jsonResult(['result'=>'success']);
				else
					$this->jsonResult(['result'=>'error', 'msg'=>$sqlErrors]);
			}
			else
				$this->jsonResult(['result'=>'error', 'msg'=>'не все нужные поля']);
		}
		else
			$this->jsonResult(['result'=>'error', 'msg'=>'только ajax']);
	}

	/**
	 * Выдает форму добавления связи
	 * если для поля есть настройки и оно вообще типа связь
	 * @var $_POST['fieldName'] string
	 * @var $_POST['tableName'] string
	 * @return JSON
	 */
	public function getNodeAddFormAction()
	{
		if($this->request->isAjax())
		{
			$fieldName = $this->request->getPost('fieldName');
			$tableName = $this->request->getPost('tableName');
			// достаем настроки текущего поля
			$fieldDesc = EmTypes::find([
				'conditions' => "table = ?0 AND field = ?1",
				'bind' => [$tableName,$fieldName],
			]);
			if(count($fieldDesc))
			{
				$fieldDesc = $fieldDesc[0];
				if($fieldDesc->type == "em_node")
				{
					$settings   = (!empty($fieldDesc->settings))?json_decode($fieldDesc->settings,true):[];
					// поле по которому привязвается другой элемент - id например
					$nodeField  = (!empty($settings['nodeField']))?$settings['nodeField']:false;
					$this->view->setVar('nodeField',$nodeField);
					// таблица из которой берутся эелементы
					$nodeTable  = (!empty($settings['nodeTable']))?$settings['nodeTable']:false;
					$this->view->setVar('nodeTable',$nodeTable);
					// поле которому ведется поиск - name например
					$nodeSearch = (!empty($settings['nodeSearch']))?$settings['nodeSearch']:false;
					$this->view->setVar('nodeSearch',$nodeSearch);

					$this->view->setVar('settings',$settings);
					$this->view->setVar('fieldName',$fieldName);
					$this->view->setVar('tableName',$tableName);

					$this->jsonResult([
	                	'result' => 'success',
	                	'form' => $this->view->getRender('table','getNodeAddForm')
	            	]);
				}
				else
					$this->jsonResult(['result'=>'error','msg'=>'настройки поля не найдены']);
			}
			else
				$this->jsonResult(['result'=>'error','msg'=>'настройки поля не найдены']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'только ajax']);
	}

	/**
	 * Поиск полей для ватокомплита, работает только при ajax запросах
	 * @var $_POST['nodeTable'] string таблица привязки
 	 * @var $_POST['nodeField'] string поле привязки - например id
 	 * @var $_POST['nodeSearch'] string поле по которому ищется привязываемый элемент - например name
	 * @return JSON
	 */
	public function autoCompleteAction()
	{
		if($this->request->isAjax())
		{
			$nodeField  = $this->request->getPost('nodeField');
			$nodeTable  = $this->request->getPost('nodeTable');
			$nodeSearch = $this->request->getPost('nodeSearch');
			$q          = $this->request->getPost('q');
			if(!empty($nodeField) && !empty($nodeTable) && !empty($q))
			{
				$db       = $this->di->get('db');
				$limit    = 7;
				$from     = 0;
				$sqlWhere = $nodeTable;
				$nodeSearchSQL = (!empty($nodeSearch))?$nodeSearch." LIKE '%".$q."%'":'';
				$tableResult = $db->fetchAll(
					"SELECT * FROM ".$sqlWhere." WHERE ".$nodeSearchSQL." LIMIT $from,$limit",
					Phalcon\Db::FETCH_ASSOC
				);
				$result = [];
				foreach ($tableResult as $key => $tRes)
				{
					$resEl         = [];
					$resEl['id']   = $tRes[$nodeField];
					$resEl['name'] = $tRes[$nodeSearch];
					$result[]      = $resEl;
				}
				$this->jsonResult(['result'=>'success','elements'=>$result]);
			}
			else
				$this->jsonResult(['result'=>'error','msg'=>'некорректные настройки']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'только ajax']);
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

}