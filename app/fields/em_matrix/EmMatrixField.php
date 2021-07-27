<?php

class EmMatrixField extends FieldBase
{
	static $nodeTable = [];
	protected $settings = [];
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		if (
			!isset($this->settings['localField'])
			|| !isset($this->settings['finalTableCode'])
			|| !isset($this->settings['finalTableField'])
		) return ['matrixValue'=>[]];
		$this->settings['isManyToMany'] = isset($this->settings['isManyToMany']) && isset($this->settings['nodeTableCode']) && isset($this->settings['nodeTableField']) && isset($this->settings['nodeTableFinalTableField']) ? ($this->settings['isManyToMany'] === 'true') : false;
		$this->getMatrixTable();
		$nodeTable = [];
		$localFieldValue = $this->row[$this->settings['localField']];

		$finalTableField = $this->settings['finalTableField'];

		if ($this->settings['isManyToMany'])
		{
			$nodeTableField = $this->settings['nodeTableField'];
			$nodeTableFinalTableField = $this->settings['nodeTableFinalTableField'];
			if (empty(self::$nodeTable[$this->settings['nodeTableCode']]) || empty(self::$nodeTable[$this->settings['nodeTableCode']]['items']))
				return ['matrixValue'=>[]];

			$nodeSearchedFields = array_column(
				array_filter(self::$nodeTable[$this->settings['nodeTableCode']]['items'],
					function($element) use ($localFieldValue, $nodeTableField)
					{
						return $localFieldValue == $element[$nodeTableField];
					}) ?? [],
				$nodeTableFinalTableField);

			$node = array_filter(self::$nodeTable[$this->settings['finalTableCode']]['items'],
			function($element) use ($nodeSearchedFields, $finalTableField)
			{
				return in_array($element[$finalTableField], $nodeSearchedFields);
			});
		}
		else
		{
			if (empty(self::$nodeTable[$this->settings['finalTableCode']]) || empty(self::$nodeTable[$this->settings['finalTableCode']]['items']))
				return ['matrixValue'=>[]];

			$node = array_filter(self::$nodeTable[$this->settings['finalTableCode']]['items'],
			function($element) use ($finalTableField, $localFieldValue)
			{
				return $element[$finalTableField] == $localFieldValue;
			});
		}

		if(empty($node))
			return ['matrixValue'=>[]];

		return ['matrixValue' => array_values($node)];
	}

	public function getMatrixTable()
	{
		if ($this->settings['isManyToMany'] && empty(self::$nodeTable[$this->settings['nodeTableCode']]))
		{
			$selectResult = $this->element->select([
				'from' => $this->settings['nodeTableCode'],
				'fields' => $this->getColumnsForFetch($this->settings['nodeTableCode']),
			]);
			self::$nodeTable[$this->settings['nodeTableCode']] = $selectResult['success'] ? $selectResult['result'] : [];
		}

		if (empty(self::$nodeTable[$this->settings['finalTableCode']]))
		{
			$selectResult = $this->element->select([
				'from' => $this->settings['finalTableCode'],
				'fields' => $this->getColumnsForFetch($this->settings['finalTableCode']),
			]);
			self::$nodeTable[$this->settings['finalTableCode']] = $selectResult['success'] ? $selectResult['result'] : [];
		}
	}

	public function getColumnsForFetch($tableCode)
	{
		$columns = array_filter($this->element->getColumns($tableCode), function($column)
		{
			return $column['em']['type'] !== 'em_matrix';
		});
		return array_keys($columns);
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
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
			["name" =>$locales->is_not_empty,    "code"=>"IS NOT EMPTY"],
			["name" =>$locales->is_empty,        "code"=>"IS EMPTY"],
			["name" =>$locales->is,              "code"=>"IS"],
			["name" =>$locales->is_not,          "code"=>"IS NOT"],
			["name" =>$locales->contains,        "code"=>"CONTAINS"],
			["name" =>$locales->does_not_contain,"code"=>"DOES NOT CONTAIN"],
			["name" =>$locales->is_larger,       "code"=>"IS LARGER"],
			["name" =>$locales->is_smaller,      "code"=>"IS SMALLER"],
		];
	}

	public function getCollationSqlWhere($whereArray)
	{
		if (empty($whereArray['operation']) || empty($whereArray['code'])) return '';
		switch ($whereArray['operation']) {
			case 'IS NOT EMPTY':
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} <> \"\"";
			case 'IS EMPTY':
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} = \"\" OR {$this->settings['finalTableCode']}.{$whereArray['code']} IS NULL";
			case 'IS':
				$whereArray['value'] = quotemeta($whereArray['value']);
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} = :value:";
			case 'IS NOT':
				$whereArray['value'] = quotemeta($whereArray['value']);
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} <> :value: ";
			case 'CONTAINS':
				$whereArray['value'] = quotemeta($whereArray['value']);
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} LIKE :value:";
			case 'DOES NOT CONTAIN':
				$whereArray['value'] = '%'.$whereArray['value'].'%';
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} NOT LIKE :value:";
			case 'IS LARGER':
				$whereArray['value'] = intval($whereArray['value']);
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} >= :value:";
			case 'IS SMALLER':
				$whereArray['value'] = intval($whereArray['value']);
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} <= :value:";
		}
		return '';
	}

	protected function getTemplate($where)
	{
		if ($this->settings['isManyToMany'])
			return "{$this->settings['localField']} IN (SELECT {$this->settings['nodeTableCode']}.{$this->settings['nodeTableField']} FROM {$this->settings['nodeTableCode']} JOIN {$this->settings['finalTableCode']} ON {$this->settings['finalTableCode']}.{$this->settings['finalTableField']} = {$this->settings['nodeTableCode']}.{$this->settings['nodeTableFinalTableField']} WHERE {$where} )";
		return "{$this->settings['localField']} IN (SELECT {$this->settings['finalTableCode']}.{$this->settings['finalTableField']} FROM {$this->settings['finalTableCode']} WHERE  {$where} )";
	}

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		if (empty($whereArray['value']) || !is_array(array_values($whereArray['value'])[0])) return '';
		$this->settings = [
			'isManyToMany'             => isset($this->settings['isManyToMany']) ? ($this->settings['isManyToMany'] === 'true') && isset($this->settings['nodeTableCode']) && isset($this->settings['nodeTableField']) && isset($this->settings['nodeTableFinalTableField']) : false,
			'localField'               => isset($this->settings['localField']) ? $this->settings['localField'] : null,
			'nodeTableCode'            => isset($this->settings['nodeTableCode']) ? $this->settings['nodeTableCode'] : null,
			'nodeTableField'           => isset($this->settings['nodeTableField']) ? $this->settings['nodeTableField'] : null,
			'nodeTableFinalTableField' => isset($this->settings['nodeTableFinalTableField']) ? $this->settings['nodeTableFinalTableField'] : null,
			'finalTableCode'           => isset($this->settings['finalTableCode']) ? $this->settings['finalTableCode'] : null,
			'finalTableField'          => isset($this->settings['finalTableField']) ? $this->settings['finalTableField'] : null,
		];

		// если не хватает конфига
		if (
			empty($this->settings['localField'])
			|| empty($this->settings['finalTableCode'])
			|| empty($this->settings['finalTableField'])
		) return '';

		$where = '';
		foreach ($whereArray['value'] as $field) {
			$collation = $this->getCollationSqlWhere($field);
			if (!empty($where)) $collation = " {$whereArray['operation']} $collation";
			$where .= $collation;
		}
		if (empty($where)) return '';

		return $this->getTemplate($where);
	}
}
