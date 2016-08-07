<?php

class EmFileField extends FieldBase
{
	public $EditFieldPath = 'em_file/view/field';
	public $ValueFieldPath = 'em_file/view/value';
	public function getSettings($settings, array $params)
	{
		$settingFields['savePath'] = (!empty($settings['savePath']))?$settings['savePath']:$this->tableEditor->getDefaultFilesSavePath();
		$settingFields['fileTypes'] = (!empty($settings['fileTypes']))?$settings['fileTypes']:[];
		
		// определяем доп переменную для типов файлов
		$this->view->setVar('fileTypes',['jpeg','png','gif','bmp','pdf','doc']);
		
		if(!empty($params['settingFields']))
			$settingFields = array_merge($params['settingFields'],$settingFields);
		$this->view->setVar('settingFields',$settingFields);
		
		// обязательый параметр
		$this->view->setVar('formPath','em_file/view/settingsForm');
	}

	public function getValue($fieldValue,$settings,$table = false)
	{
		if(empty($fieldValue)){ return ''; }
		if($table)
			return json_decode($fieldValue,true);
		else
		{
			$resFilesArray = [];
			$filesArr = json_decode($fieldValue,true);
			if($filesArr)
			{
				foreach ($filesArr as $key => $fileArr)
				{
					$resFilesArray[] = array_merge($fileArr,[
						'jsonFileObj' => htmlspecialchars(json_encode($fileArr),ENT_QUOTES),
						'index' => $key
					]);
				}
			}
			elseif(file_exists(ROOT.$fieldValue))
			{
				$fileArr = ['upName'=>'untittled','type'=>'file','path' => $fieldValue];
				$resFilesArray[] = array_merge($fileArr,[
					'jsonFileObj' => htmlspecialchars(json_encode($fileArr),ENT_QUOTES),
					'index' => 0
				]);
			}
			return $resFilesArray;
		}
	}

	public function saveValue($fieldValue,$fieldArray)
	{
		return $this->tableEditor->handleUploadedFiles($fieldValue, $fieldArray);
	}
}