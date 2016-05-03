<?php

class EmMultyNodeField extends FieldBase
{
	public $EditFieldPath = 'em_multy_node/view/field';
	public $ValueFieldPath = 'em_multy_node/view/value';
	public $colTypes = [
		'input'             =>'Строка',
		'select'            =>'Выбор',
		'select_from_table' =>'Выбор из таблицы',
		'textarea'          =>'Текст',
	];
	public function getSettings($settings, array $params)
	{
		foreach($settings['cols'] as $key => $col)
			if(!is_numeric($key)) unset($settings['cols'][$key]);
		$settings['cols'] = (!empty($settings['cols']))?$settings['cols']:[];
		$this->view->setVar('cols',$settings['cols']);
		$this->view->setVar('colTypes',$this->colTypes);

		// определяем доп переменные для таблиц
		// весь список таблиц и их полей
		// для нужд привязки к ним в данном типе поля
		$resTables = $params['tables'];
		foreach ($resTables as $tableRealName => &$tableArr)
		{
			$curCols = $this->tableEditor->getTableColumns($tableRealName);
			$tableArr['fields'] = [];
			foreach ($curCols as $colArr)
				$tableArr['fields'][$colArr['field']] = $colArr['type'];
		}
		$this->view->setVar('tables',$resTables);
		$this->view->setVar('tablesJSON',json_encode($resTables));
		
		// обязательый параметр
		$this->view->setVar('formPath','em_multy_node/view/settingsForm');
	}

	/**
	 * Формат хранения - JSON
	 * [ [ 'colcode1'=>val,'colcode2'=>'val2' ], [ 'colcode1'=>val,'colcode2'=>'val2' ] ]
	 */
	public function getValue($fieldValue,$settings,$table = false)
	{
		if(!empty($settings['cols']) && !empty($fieldValue))
		{
			if(!$table)
			{
				$res = @json_decode($fieldValue);
				if(is_array($res))
					return $res;
				else
					return [];
			}
			else
			{
				$res = @json_decode($fieldValue);
				if(is_array($res))
				{
					$resString = '';
					foreach ($res as $colsArr)
					{
						$resString .= (is_array($colsArr))?implode(',',$colsArr):$colsArr;
					}
					return $resString;
				}
				else
					return $fieldValue;
			}
		}
		else
			return $fieldValue;
	}

	public function saveValue($fieldValue,$fieldArray)
	{
		return implode(',',$fieldValue);
	}
}