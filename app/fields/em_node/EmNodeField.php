<?php

class EmNodeField extends FieldBase
{
	public $EditFieldPath = 'em_node/view/field';
	public $ValueFieldPath = 'em_node/view/value';
	public function getSettings($settings, array $params)
	{
		// таблица к которой идет привязка
		$settingFields['nodeTable'] = (!empty($settings['nodeTable']))?$settings['nodeTable']:false;
		// поле по которому привязываются элементы (желательно - id)
		$settingFields['nodeField'] = (!empty($settings['nodeField']))?$settings['nodeField']:false;
		// поле по которуму ищутся элементы (например - имя)
		$settingFields['nodeSearch'] = (!empty($settings['nodeSearch']))?$settings['nodeSearch']:false;
		
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
		
		if(!empty($params['settingFields']))
			$settingFields = array_merge($params['settingFields'],$settingFields);
		$this->view->setVar('settingFields',$settingFields);
		
		// обязательый параметр
		$this->view->setVar('formPath','em_node/view/settingsForm');
	}

	public function getValue($fieldValue,$settings,$table = false)
	{
		if(empty($fieldValue)) return '';
		// для поля привязки необходимо определить таблицу привязки
		// поле по которому привязываются элеменыт
		// поле по которому ведется поис элементов 
		$nodeElements = [];
		// ===================================================================
			$db       = $this->di->get('db');
			$whereSql = $settings['nodeField'] ." IN (".$fieldValue.")";
			$tableResult = $db->fetchAll(
				"SELECT * FROM ".$settings['nodeTable']." WHERE  $whereSql ",
				Phalcon\Db::FETCH_ASSOC
			);
			foreach ($tableResult as $key => $tRes)
			{
				$nodeElement         = [];
				$nodeElement['id']   = $tRes[$settings['nodeField']];
				$nodeElement['name'] = $tRes[$settings['nodeSearch']];
				$nodeElements[]      = $nodeElement;
			}
		// ===================================================================
		return $nodeElements;
	}

	public function saveValue($fieldValue,$fieldArray)
	{
		return implode(',',$fieldValue);
	}
}