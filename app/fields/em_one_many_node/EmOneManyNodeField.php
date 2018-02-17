<?php

class EmOneManyNodeField extends FieldBase
{
	public $EditFieldPath = 'em_one_many_node/views/field';
	public $ValueFieldPath = 'em_one_many_node/views/value';
	public function getSettings($settings, array $params)
	{
		// the node from key
		$settingFields['nodeFromFiled'] = (!empty($settings['nodeFromFiled']))?$settings['nodeFromFiled']:false;
		// таблица к которой идет привязка
		$settingFields['nodeTable'] = (!empty($settings['nodeTable']))?$settings['nodeTable']:false;
		// поле по которому привязываются элементы (желательно - id)
		$settingFields['nodeField'] = (!empty($settings['nodeField']))?$settings['nodeField']:false;
		// поле по которуму ищутся элементы (например - имя)
		$settingFields['nodeName'] = (!empty($settings['nodeName']))?$settings['nodeName']:false;

		// определяем доп переменные для таблиц
		// весь список таблиц и их полей
		// для нужд привязки к ним в данном типе поля
		$resTables = $params['tables'];
		foreach ($resTables as $tableRealName => &$tableArr)
		{
			$curCols = $this->tableEditor->getTableFilelds($tableRealName);
			$tableArr['fields'] = [];
			foreach ($curCols as $colArr)
				$tableArr['fields'][$colArr['field']] = $colArr['type'];
		}
		$this->view->setVar('tables',$resTables);
		$this->view->setVar('tablesJSON',json_encode($resTables));

		$curTableCols = $this->tableEditor->getTableFilelds($params['tableName']);
		$this->view->setVar('curTableCols',$curTableCols);

		if(!empty($params['settingFields']))
			$settingFields = array_merge($params['settingFields'],$settingFields);
		$this->view->setVar('settingFields',$settingFields);

		// обязательый параметр
		$this->view->setVar('formPath','em_one_many_node/views/settingsForm');
	}

	public function prolog($settings,$table = false)
	{
		$this->assets->addJs('fields/em_one_many_node/src/init.js');
	}

	public function getValue($fieldValue,$settings,$table = false)
	{
		if(empty($fieldValue)) return '';
		// для поля привязки необходимо определить таблицу привязки
		// поле по которому привязываются элеменыт
		// поле по которому ведется поис элементов
		$nodeElements = [];
		$config  = $this->di->get('config');
		$baseUri = $config->application->baseUri;
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
				$nodeElement['url']  = "{$baseUri}table/{$settings['nodeTable']}/edit/{$tRes[$settings['nodeField']]}";
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