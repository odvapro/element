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
						'form'   => $this->view->getRender('index','getFileUploadForm')
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
					$this->fields->em_file->addToFiles('file',$this->request->getPost('url'));

				$this->jsonResult($this->fields->em_file->uploadFiles($fieldDesc));
			}
			else
				$this->jsonResult(['result'=>'error','msg'=>'поле не является файловым']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'поле не имеет настроек']);
	}
}

