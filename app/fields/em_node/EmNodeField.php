<?php

class EmNodeField extends FieldBase
{
	static $nodeTable = [];

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$nodeTableCode = $this->settings['nodeTableCode'];
		$nodeFieldCode = $this->settings['nodeFieldCode'];
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

		$node = null;

		foreach (self::$nodeTable[$nodeTableCode]['items'] as $nodeItem) {
			if ($nodeItem[$nodeFieldCode] == $this->fieldValue)
			{
				$node = $nodeItem;
				break;
			}
		}

		if(empty($node))
			return [];

		return [
			'id'   => $node[$nodeFieldCode],
			'name' => $node[$nodeSearchCode],
			'url'  => "/table/{$nodeTableCode}/el/{$node[$nodeFieldCode]}"
		];
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(empty($this->fieldValue))
			return null;

		if(is_numeric($this->fieldValue))
			return intval($this->fieldValue);

		if(is_string($this->fieldValue))
			return null;

		if(!is_array($this->fieldValue))
			return intval($this->fieldValue);

		return intval($this->fieldValue['id']);
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
				return "{$whereArray['code']} IS NULL OR {$whereArray['code']} = \"\" ";
			break;

			case 'IS NOT EMPTY':
				return "{$whereArray['code']} IS NOT NULL AND {$whereArray['code']} <> \"\" ";
			break;
		}
		return '';
	}
}
