<?php

class EmSectionField extends FieldBase
{
	static $nodeTable = [];

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$multiple                = $this->settings['multiple'];
		$sectionTableCode        = $this->settings['sectionTableCode'];
		$sectionFieldCode        = $this->settings['sectionFieldCode'];
		$sectionSearchCode       = $this->settings['sectionSearchCode'];
		$sectionParentsFieldCode = $this->settings['sectionParentsFieldCode'];

		$saveInForeign           = $this->settings['saveInForeign'];
		$foreignTableCode        = $this->settings['foreignTableCode'];
		$foreignElementFieldCode = $this->settings['foreignElementFieldCode'];
		$foreignSectionFieldCode = $this->settings['foreignSectionFieldCode'];

		if($saveInForeign)
			$this->fieldValue = $this->getForeignValue();

		if (empty(self::$nodeTable) || empty(self::$nodeTable[$sectionTableCode]))
		{
			$selectResult = $this->element->select([
				'from' => $sectionTableCode,
				'fields' => [
					$sectionFieldCode,
					$sectionSearchCode,
				]
			]);

			self::$nodeTable[$sectionTableCode] = $selectResult['success'] ? $selectResult['result'] : [];
		}

		if (empty(self::$nodeTable[$sectionTableCode]) || empty(self::$nodeTable[$sectionTableCode]['items']))
			return [];

		$node = [];
		$fieldValueArray = explode(',', $this->fieldValue ?? '');

		foreach (self::$nodeTable[$sectionTableCode]['items'] as $nodeItem)
		{
			if (in_array($nodeItem[$sectionFieldCode], $fieldValueArray))
			{
				$node[] = [
					'id'   => $nodeItem[$sectionFieldCode],
					'name' => $nodeItem[$sectionSearchCode],
					'url'  => "/table/{$sectionTableCode}/el/{$nodeItem[$sectionFieldCode]}"
				];
			}
		}

		return $node;
	}

	/**
	 * Runs on foreign savings
	 * @return string imploded array
	 */
	public function getForeignValue()
	{
		$sectionFieldCode        = $this->settings['sectionFieldCode'];
		$foreignTableCode        = $this->settings['foreignTableCode'];
		$foreignElementFieldCode = $this->settings['foreignElementFieldCode'];
		$foreignSectionFieldCode = $this->settings['foreignSectionFieldCode'];

		$where = [];
		$where[] = [
			'code'      => $foreignElementFieldCode,
			'operation' => 'IS',
			'value'     => $this->row[$sectionFieldCode]
		];
		$selectResult = $this->element->select([
			'from' => $foreignTableCode,
			'fields' => [
				$foreignElementFieldCode,
				$foreignSectionFieldCode,
			],
			'where'=> ['operation' => 'and','fields'=>$where]
		]);

		$sectionsIds = [];
		foreach ($selectResult['result']['items'] as $foreignNode)
			$sectionsIds[] = $foreignNode[$foreignSectionFieldCode];

		return implode(',', $sectionsIds);
	}

	/**
	 * Сохранить значение
	 * Format of saving data
	 * empty []
	 * one node [1]
	 * multiple nodes [1,2,3]
	 * each item should be int
	 */
	public function saveValue()
	{
		$preparedFieldValue = null;
		try {
			$preparedFieldValue = $this->_getSimpleSaveValue();
		} catch (Exception $e) {
			$preparedFieldValue = null;
		}

		$saveInForeign           = $this->settings['saveInForeign'];
		$sectionFieldCode        = $this->settings['sectionFieldCode'];
		$foreignTableCode        = $this->settings['foreignTableCode'];
		$foreignElementFieldCode = $this->settings['foreignElementFieldCode'];
		$foreignSectionFieldCode = $this->settings['foreignSectionFieldCode'];

		if(!$saveInForeign)
			return $preparedFieldValue;

		$currentSections = $this->getForeignValue();
		$currentSections = explode(',', $currentSections);
		$newPreparedFieldValue = (!empty($preparedFieldValue))?explode(',', $preparedFieldValue):[];
		$removeArray = [];
		foreach ($currentSections as $curSectionId)
		{
			$elIndex = array_search($curSectionId, $newPreparedFieldValue);
			if($elIndex !== false)
			{
				unset($newPreparedFieldValue[$elIndex]);
				continue;
			}
			$removeArray[] = $curSectionId;
		}

		// TODO
		// - плохо, переделать
		// - сдлеать одним запросом
		foreach ($removeArray as $removeSectionId)
			$this->element->delete([
				'table' => $foreignTableCode,
				'where' => [
					'operation' => 'AND',
					'fields'=>[
						['code' => $foreignSectionFieldCode, 'operation' => 'IS', 'value' => $removeSectionId ],
						['code' => $foreignElementFieldCode, 'operation' => 'IS', 'value' => $this->row[$sectionFieldCode] ]
					]
				]
			]);

		$inserValues = [];
		foreach ($newPreparedFieldValue as $sectionId)
			$inserValues[] = [$this->row[$sectionFieldCode],$sectionId];
		if(!empty($inserValues))
			$this->eldb->insert([
				'table'   => $foreignTableCode,
				'columns' => [$foreignElementFieldCode,$foreignSectionFieldCode],
				'values'  => $inserValues
			]);

		return null;
	}

	/**
	 * Get simple save value
	 * empty []
	 * one node [1]
	 * multiple nodes [1,2,3]
	 */
	public function _getSimpleSaveValue()
	{
		if(empty($this->fieldValue))
			return null;

		if(!is_numeric($this->fieldValue) && !is_array($this->fieldValue))
			throw new EmException("Incorrect field value, should be int or array of int", 1);

		$nodes = [];
		if(is_array($this->fieldValue))
		{
			$nodes = array_column($this->fieldValue, 'id');
			foreach ($nodes as $node)
				if (!is_int($node) && !is_numeric($node))
					throw new EmException("Array of node values should be array of integers", 2);

			return implode(',', $nodes);
		}

		return null;
	}

	/**
	 * Gets collations
	 * @return array
	 */
	public function getCollations()
	{
		$locales = json_decode($this->getLocales());
		return [
			['name'=>$locales->is,'code'=>'IS'],
			['name'=>$locales->is_not,'code'=>'IS NOT'],
			['name'=>$locales->is_empty,'code'=>'IS EMPTY'],
			['name'=>$locales->is_not_empty,'code'=>'IS NOT EMPTY']
		];
	}

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		switch ($whereArray['operation']) {
			case 'IS':
				return $whereArray['code'] . ' = :value:';
				break;

			case 'IS NOT':
				return $whereArray['code'] . ' <> :value:';
				break;

			case 'IS EMPTY':
				return "({$whereArray['code']} IS NULL OR {$whereArray['code']} = \"\" )";
			break;

			case 'IS NOT EMPTY':
				return "({$whereArray['code']} IS NOT NULL AND {$whereArray['code']} <> \"\" )";
			break;
		}
		return '';
	}
}
