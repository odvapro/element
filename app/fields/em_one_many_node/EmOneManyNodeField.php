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

	public function prolog($settings,$table = false) {}

	/**
	 * Get value
	 * @param  $fieldValue always false
	 * @param  array  $settings
	 * @param  boolean $table
	 * @return array
	 */
	public function getValue($fieldValue,$settings,$table = false)
	{
		if(empty($settings)) return [];
		$fromNodeVal = $this->row[$settings['nodeFromFiled']];
		$nodeTable   = $settings['nodeTable'];
		$nodeField   = $settings['nodeField'];
		$nodeName    = $settings['nodeName'];

		$db       = $this->di->get('db');
		$tableResult = $db->fetchAll(
			"SELECT * FROM {$nodeTable} WHERE  {$nodeField} = {$fromNodeVal} ",
			Phalcon\Db::FETCH_ASSOC
		);

		$resutl = [];
		foreach ($tableResult as $key => $tRes)
		{
			$resutl[] = [
				'key'  => $tRes[$nodeField],
				'name' => $tRes[$nodeName],
				'url'  => $this->tableEditor->getElementUrl($nodeTable,$tRes)
			];
		}
		$addUrl = $this->tableEditor->getAddUrl($nodeTable)."?acom[{$nodeField}]={$fromNodeVal}";
		return ['results'=>$resutl,'addUrl'=>$addUrl];
	}

	public function saveValue($fieldValue,$fieldArray)
	{
		return implode(',',$fieldValue);
	}
}