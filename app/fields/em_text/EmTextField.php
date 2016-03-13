<?php

class EmTextField extends FieldBase
{
	public $EditFieldPath = 'em_text/view/field';
	public function getSettings($settings, array $params)
	{
		$settingFields['visualEditor'] = (!empty($settings['visualEditor']))?$settings['visualEditor']:0;
		
		if(!empty($params['settingFields']))
			$settingFields = array_merge($params['settingFields'],$settingFields);
		$this->view->setVar('settingFields',$settingFields);
	}
}