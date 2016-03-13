<?php

class EmFileField extends FieldBase
{
	
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
}