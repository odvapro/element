<?php

class EmNodeField extends FieldBase
{
	static $nodeTable = [];

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$nodeTableCode  = $this->settings['nodeTableCode'];
		$nodeFieldCode  = $this->settings['nodeFieldCode'];
		$nodeSearchCode = $this->settings['nodeSearchCode'];
		if (empty(self::$nodeTable) || empty(self::$nodeTable[$nodeTableCode]))
		{
			$selectResult = $this->element->select([
				'from' => $nodeTableCode,
				'fields' => [
					$nodeFieldCode,
					$nodeSearchCode,
				]
			]);

			self::$nodeTable[$nodeTableCode] = $selectResult['success'] ? $selectResult['result'] : [];
		}

		if (empty(self::$nodeTable[$nodeTableCode]) || empty(self::$nodeTable[$nodeTableCode]['items']))
			return [];

		$node = [];
		$fieldValueArray = explode(',', $this->fieldValue ?? '');

		foreach (self::$nodeTable[$nodeTableCode]['items'] as $nodeItem)
		{
			if (in_array($nodeItem[$nodeFieldCode], $fieldValueArray))
			{
				$node[] = [
					'id'   => $nodeItem[$nodeFieldCode],
					'name' => $nodeItem[$nodeSearchCode],
					'url'  => "/table/{$nodeTableCode}/el/{$nodeItem[$nodeFieldCode]}"
				];
			}
		}

		return $node;
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
