<?php
class IndexFController extends ControllerBase
{

	/**
	 * Выдает форму добавления файла в нужное поле таблицы
	 * если для поля есть настройки и оно вообще типа файл
	 * @var $_POST['fieldName'] string
	 * @var $_POST['tableName'] string
	 * @return JSON
	 */
	public function getFileUploadFormAction()
	{
		if(!$this->request->isAjax())
			return $this->response->setJsonContent(['result'=>'error','msg'=>'только ajax']);

		$fieldName = $this->request->getPost('fieldName');
		$tableName = $this->request->getPost('tableName');
		// достаем настроки текущего поля
		$fieldDesc = EmTypes::find([
			'conditions' => "table = ?0 AND field = ?1",
			'bind' => [$tableName,$fieldName],
		]);
		if(!count($fieldDesc))
			return $this->response->setJsonContent(['result'=>'error','msg'=>'настройки поля не найдены']);

		$fieldDesc = $fieldDesc[0];
		if($fieldDesc->type != "em_file")
			return $this->response->setJsonContent(['result'=>'error','msg'=>'настройки поля не найдены']);

		$settings = (!empty($fieldDesc->settings))?json_decode($fieldDesc->settings,true):[];
		if(!empty($settings['fileTypes']))
			$this->view->setVar('fileTypes',implode(', ', $settings['fileTypes']));
		
		$this->view->setVar('settings',$settings);
		$this->view->setVar('fieldName',$fieldName);
		$this->view->setVar('tableName',$tableName);

		$this->response->setJsonContent([
			'result' => 'success',
			'form'   => $this->view->getRender('index','getFileUploadForm')
    	]);
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
	
		if(!count($fieldDesc))
			return $this->response->setJsonContent(['result'=>'error','msg'=>'поле не имеет настроек']);
		$fieldDesc = $fieldDesc[0];
		
		if($fieldDesc->type != "em_file")
			return $this->response->setJsonContent(['result'=>'error','msg'=>'поле не является файловым']);
	
		// загрузка по URL
		// файл просто добавлется в массив $_FILES для обычной обработки
		if($this->request->getPost('type') == 'byUrl' && !empty($this->request->getPost('url')))
			$this->fields->em_file->addToFiles('file',$this->request->getPost('url'));

		$this->response->setJsonContent($this->fields->em_file->uploadFiles($fieldDesc));
	}

	/**
	 * Регенерация картинок
	 * @return JSON
	 */
	public function reGenerateAction()
	{
		$db        = $this->di->get('db');
		$fieldName = $this->request->getPost('fieldName');
		$tableName = $this->request->getPost('tableName');
		// достаем настроки текущего поля
		$fieldDesc = EmTypes::find([
			'conditions' => "table = ?0 AND field = ?1",
			'bind' => [$tableName,$fieldName],
		]);
		
		if(!count($fieldDesc))
			return $this->response->setJsonContent(['success'=>false]);
		$fieldDesc = $fieldDesc[0];
		$settings = json_decode($fieldDesc->settings,true);
		$savePath = $this->fields->em_file->getSavePath($settings,true);
		
		// достаем все элементы данной таблицы
		// загружаем в тмп уже загруженную фотку 
		// и сохраняем ее
		$tableResult = $db->fetchAll(
			"SELECT * FROM {$tableName} WHERE {$fieldName} != ''",
			Phalcon\Db::FETCH_ASSOC
		);
		foreach ($tableResult as $tRes)
		{
			$filesArr = json_decode($tRes[$fieldName],true);
			if(!is_array($filesArr)) continue;
			$_FILES = [];
			foreach ($filesArr as $key => $fileArr)
			{
				if(empty($fileArr['path'])) continue;
				$this->fields->em_file->addToFiles($key,ROOT.$fileArr['path']);
			}
			$filesArr = $this->fields->em_file->uploadFiles($fieldDesc);
			$fieldValue = [];
			foreach ($filesArr['files'] as $fileArr)
			{
				$fieldValueElement = ['tmp'=>1,'jsonFileObj'=>json_encode($fileArr)];
				$fieldValue[] = $fieldValueElement;
			}
			
			$fieldArray              = $fieldDesc->toArray();
			$fieldArray['settings']  = $settings;
			$primaryKey              = $this->tableEditor->getPrimaryKey($tableName);
			$searchField             = ['field'=>$primaryKey, 'value'=>$tRes[$primaryKey] ];
			$saveValue               = $this->fields->em_file->saveValue($fieldValue,$fieldArray,$tableName,$searchField);
			$updateField             = [];
			$updateField[$fieldName] = $saveValue;
			$this->tableEditor->update($tableName, $searchField, $updateField);
		}
		
		$this->response->setJsonContent(['success'=>true]);
	}
}
