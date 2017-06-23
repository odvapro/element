<?php

class EmMultyNodeField extends FieldBase
{
	public $EditFieldPath = 'em_multy_node/views/field';
	public $ValueFieldPath = 'em_multy_node/views/value';
	public $colTypes = [
		'input'             =>'Строка',
		'select'            =>'Выбор',
		'select_from_table' =>'Выбор из таблицы',
		'textarea'          =>'Текст',
	];

	public function getSettings($settings, array $params)
	{
		$this->assets->addJs('fields/em_multy_node/src/js/init.js');
		if(!empty($settings['cols']))
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
			$curCols = $this->tableEditor->getTableFilelds($tableRealName);
			$tableArr['fields'] = [];
			foreach ($curCols as $colArr)
				$tableArr['fields'][$colArr['field']] = $colArr['type'];
		}
		$this->view->setVar('tables',$resTables);
		$this->view->setVar('tablesJSON',json_encode($resTables));
		
		// обязательый параметр
		$this->view->setVar('formPath','em_multy_node/views/settingsForm');
	}

	public function prolog($settings,$table = false)
	{
		// если есть поле с табличной привязкой нужно достать значения таблицы
		if(!empty($settings['cols']))
		{
			$tablesToView = [];
			foreach ($settings['cols'] as $col)
			{
				if($col['type'] == 'select_from_table')
				{
					$curTablesJson = [];
					$db = $this->di->get('db');
					$sqlWhere = $col['table'];
					try
					{
						$tableResult = $db->fetchAll("SELECT * FROM ".$sqlWhere, Phalcon\Db::FETCH_ASSOC );
					} catch (Exception $e)
					{
						#todo some logging
					}

					// берем id и name
					// --- TODO  любое сочетание
					if(!empty($tableResult))
					{
						$fRow = reset($tableResult);
						if(!empty($fRow['id']) && !empty($fRow['name']))
						foreach ($tableResult as $tRow)
							$curTablesJson[$tRow['id']] = ['id'=>$tRow['id'],'name'=>$tRow['name']];
					}
					$tablesToView[$col['table']] = $curTablesJson;
				}
			}
			$tablesToViewOld = $this->view->getVar('multynode_tables');
				$tablesToView = (!empty($tablesToViewOld))?array_merge($tablesToView,$tablesToViewOld):$tablesToView;
			$this->view->setVar('multynode_tables',$tablesToView);
		}

		static $included = false;
		if($included === false)
		{
			if($table == false)
				$this->assets->addJs('fields/em_multy_node/src/js/init.js');
			else
				$this->assets->addJs('fields/em_multy_node/src/js/tableInit.js');
			$included = true;
		}
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
				$res = @json_decode($fieldValue,true);
				// подгон результата под settings cols
				$cols = [];
				if(!empty($settings['cols']))
					foreach ($settings['cols'] as $col)
						if(!empty($col['name']))
							$cols[$col['name']] = false;
				$res = array_map(function($arr) use($cols)
				{
					return array_merge($cols,$arr);
				}, $res);

				if(is_array($res))
					return $res;
				else
					return [];
			}
			else
			{
				$res = @json_decode($fieldValue,true);
				if(is_array($res))
				{
					return $res;
				}
				else
					return $fieldValue;
			}
		}
		else
			return (!empty($fieldValue))?$fieldValue:[];
	}

	public function saveValue($fieldValue,$fieldArray)
	{
		return json_encode($fieldValue);
	}
}