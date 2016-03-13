<?php

class EmNodeField extends FieldBase
{
	
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
}