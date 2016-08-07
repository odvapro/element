<?php

class EmListField extends FieldBase
{
	public $EditFieldPath = 'em_list/view/field';
	public $ValueFieldPath = 'em_list/view/value';

	public function getSettings($settings, array $params)
	{
		$this->assets->addJs('fields/em_list/src/js/init.js');
		if(!empty($settings['cols']))
			foreach($settings['cols'] as $key => $col)
				if(!is_numeric($key)) unset($settings['cols'][$key]);
		$settings['cols'] = (!empty($settings['cols']))?$settings['cols']:[];
		$this->view->setVar('cols',$settings['cols']);
	
		// обязательый параметр
		$this->view->setVar('formPath','em_list/view/settingsForm');
	}

	public function prolog($settings,$table = false)
	{
		if($table == false)
			$this->assets->addJs('fields/em_list/src/js/init.js');
		$list = (!empty($settings['cols']))?$settings['cols']:[];
		$this->view->setVar('list',$list);
	}

	/**
	 * Формат хранения - поле код
	 */
	public function getValue($fieldValue,$settings,$table = false)
	{
		return $fieldValue;
	}

	public function saveValue($fieldValue,$fieldArray)
	{
		return $fieldValue;
	}
}