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
		$columns            = $this->tableEditor->getTableColumns($activeTable['real_name']);
		$overColumns        = $this->tableEditor->getOverTableColumns($activeTable['real_name']);
		$curTable           = array_merge($activeTable,['fields'=>[]]);
		$curTable['fields'] = $this->tableEditor->getOverTable($columns,$overColumns);
		$this->view->setVar('tableInfo',$curTable);
		$this->view->setVar('tableName',$tableName);

		// ширина таблицы, менятся относительно того сколько полей в ней
		$tableWidth = count($curTable['fields'])*150;
		$this->view->setVar('tableWidth',$tableWidth);

		//достаем все что есть в этой таблице
		$db       = $this->di->get('db');
		$limit    = 20;
		$from     = $limit*(intval($page)-1);
		$sqlWhere = $activeTable['real_name'];
		$tableResult = $db->fetchAll(
			"SELECT * FROM ".$sqlWhere." LIMIT $from,$limit",
			Phalcon\Db::FETCH_ASSOC
		);

		// подгон результатов по типам, для вывода 
		$fieldTypes = [];
		$primaryKey = false;
		foreach ($curTable['fields'] as $field)
		{
			$fieldTypes[$field['field']] = $field['type'];
			if($field['key'] == 'PRI')
				$primaryKey = $field['field'];
		}
		foreach($tableResult as &$tRes)
			foreach($tRes as $fieldName => &$fieldVal)
				if(!is_null($this->fields->{$fieldTypes[$fieldName]}))
					$fieldVal = $this->fields->{$fieldTypes[$fieldName]}->getValue($fieldVal,[],true);

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
		$columns = $this->tableEditor->getTableColumns($activeTable['real_name']);
		$overColumns = $this->tableEditor->getOverTableColumns($activeTable['real_name']);
		$curTable = array_merge($activeTable,['fields'=>[]]);
		$curTable['fields'] = $this->tableEditor->getOverTable($columns,$overColumns);
		
		// определяем адреса форм редактирования полей
		foreach ($curTable['fields'] as &$field)
		{
			if(!is_null($this->fields->{$field['type']}))
				$field['formPath'] = $this->fields->{$field['type']}->getEditFieldPath();
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
		$columns            = $this->tableEditor->getTableColumns($activeTable['real_name']);
		$overColumns        = $this->tableEditor->getOverTableColumns($activeTable['real_name']);
		$curTable           = array_merge($activeTable,['fields'=>[]]);
		$curTable['fields'] = $this->tableEditor->getOverTable($columns,$overColumns);
		$curTableFields     = $curTable['fields'];
		
		// определяем адреса форм редактирования полей
		$primaryKey = false;
		foreach ($curTable['fields'] as  &$field)
		{
			if(!is_null($this->fields->{$field['type']}))
				$field['formPath'] = $this->fields->{$field['type']}->getEditFieldPath();
			$field['formPath'] = (!empty($field['formPath']))?'fields/'.$field['formPath']:'table/notSystemEditField';

			if($field['key'] == 'PRI')
				$primaryKey = $field['field'];
		}
		$this->view->setVar('tableInfo',$curTable);
		$this->view->setVar('sysTypes',$this->tableEditor->systemEmTypes);
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
			// + обробатывает данные файлов, переносит их из временных папок или удаляет
			$validationErrors = [];
			$formData = [];
			$primaryKey = [];
			foreach ($curTable['fields'] as $key => $fieldArr)
			{
				$required = ( !empty($fieldArr['required']) && $fieldArr['required'] == 1  || $fieldArr['null'] == "NO" )?true:false;
				$required = ($fieldArr['extra'] == "auto_increment")?false:$required;
				if($required && empty($field[$fieldArr['field']])) 
					$validationErrors[] = $fieldArr['field'].' required';
				else
					if(!empty( $field[$fieldArr['field']]))
						if(!is_null($this->fields->{$fieldArr['type']}))
							$formData[$fieldArr['field']] = $this->fields->{$fieldArr['type']}->saveValue($field[$fieldArr['field']],$fieldArr);
						else
							$formData[$fieldArr['field']] = $field[$fieldArr['field']];
					else
						$formData[$fieldArr['field']] = null;

				if($fieldArr['key'] == 'PRI' && !empty($field[$fieldArr['field']]))
					$primaryKey = ['field'=>$fieldArr['field'], 'value'=>intval($field[$fieldArr['field']])];
			}

			// проверка на удаление файлов
			// при удалении из базы, файл нужно удалить физически
			// для этого сравниваем текущее значение поля файл, с обновляемым
			// тем самым выявляя удаленные файлы, и затем удаляем их
			if(!empty($primaryKey) && !count($validationErrors))
			{
				$elementArr = $this->tableEditor->getElement($tableName,$primaryKey);
				$this->tableEditor->deleteOldFiles($elementArr,$formData,$curTable['fields']);
			}

			// добавление или обновление после проверок валидиции 
			if(!count($validationErrors))
			{
				$res = false;
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
				// -----------------------------------
				$columns     = $this->tableEditor->getTableColumns($tableName);
				$overColumns = $this->tableEditor->getOverTableColumns($tableName);
				$curTable    = [];
				$curTable['fields'] = $this->tableEditor->getOverTable($columns,$overColumns);

				$elementArr = $this->tableEditor->getElement($tableName,['field'=>$primaryKey, 'value'=>intval($elementId)]);
				$this->tableEditor->deleteOldFiles($elementArr,[],$curTable['fields']);
				// -----------------------------------
				
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
	 * Выдает форму добавления файла в нужное поле таблицы
	 * если для поля есть настройки и оно вообще типа файл
	 * @var $_POST['fieldName'] string
	 * @var $_POST['tableName'] string
	 * @return JSON
	 */
	public function getFileUploadFormAction()
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
				if($fieldDesc->type == "em_file")
				{
					$settings = (!empty($fieldDesc->settings))?json_decode($fieldDesc->settings,true):[];
					if(!empty($settings['fileTypes']))
						$this->view->setVar('fileTypes',implode(', ', $settings['fileTypes']));
					$this->view->setVar('settings',$settings);
					$this->view->setVar('fieldName',$fieldName);
					$this->view->setVar('tableName',$tableName);

					$this->jsonResult([
	                	'result' => 'success',
	                	'form' => $this->view->getRender('table','getFileUploadForm')
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

	// загружает файл на сервер
	// предварительно проверив поле на соответсвие
	public function uploadFilesAction($tableName = false, $fieldName = false)
	{
		// достаем настроки текущего поля
		$fieldDesc = EmTypes::find([
			'conditions' => "table = ?0 AND field = ?1",
			'bind' => [$tableName,$fieldName],
		]);
		if(count($fieldDesc))
		{
			$fieldDesc = $fieldDesc[0];
			if($fieldDesc->type == "em_file")
			{
				// загрузка по URL
				// файл просто добавлется в массив $_FILES для обычной обработки
				if($this->request->getPost('type') == 'byUrl' && !empty($this->request->getPost('url')))
					$this->_addToFiles('file',$this->request->getPost('url'));

				$this->_uploadFiles($fieldDesc);
			}
			else
				$this->jsonResult(['result'=>'error','msg'=>'поле не является файловым']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'поле не имеет настроек']);
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
	
	/**
	 * Загружает файлы которые были посланы обычным способом через поле типа файл
	 * @var  fieldDesc описание типа поля в которое записывается файл
	 */
	private function _uploadFiles($fieldDesc)
	{
		if($this->request->hasFiles() == true)
		{
			$settings = (!empty($fieldDesc->settings))?json_decode($fieldDesc->settings,true):[];
			$fileTypes = [];
			if(!empty($settings['fileTypes']))
				$fileTypes = $settings['fileTypes'];
        	
        	// подготовка массива файлов/файла для записи в БД
        	$globValid = true;
        	$fileForDb = [];
        	foreach ($this->request->getUploadedFiles() as $file)
			{
			    // проверка поля на соответсвие типам
			    $valid = $this->tableEditor->checkFileTypes($file,$fileTypes);
			    if($valid)
				    $fileForDb[] = $this->tableEditor->uploadFileToTemp($file,$settings);
			    else
			    	$globValid = false;

				// если поле не множественное , то разрешается загрузить только один файл
			    if($fieldDesc->multiple == 0) break;
			}

			if($globValid)
			{
				// результат массив загруженных файлов 
				// во временную папку 
				if(!empty($fileForDb))
					$this->jsonResult(['result'=>'success','files'=>$fileForDb]);
			}
			else
				$this->jsonResult(['result'=>'error','msg'=>'такого типа файлы запрещены']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'нет файлов']);
	}

	/**
	 * Добавлает файл по URL в массив $_FILES
	 * @var $key  ключ по которому записывается файл  
	 * @var $url  url из которого берется файл
	 */
	function _addToFiles($key, $url)
	{
	    $tempName = tempnam('/tmp', 'php');
	    $originalName = basename(parse_url($url, PHP_URL_PATH));

	    $imgRawData = @file_get_contents($url);
	    file_put_contents($tempName, $imgRawData);
	    $_FILES[$key] = array(
	        'name' => $originalName,
	        'type' => mime_content_type($tempName),
	        'tmp_name' => $tempName,
	        'error' => 0,
	        'size' => strlen($imgRawData),
	    );
	}

}