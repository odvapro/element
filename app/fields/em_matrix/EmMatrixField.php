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
			["name" =>$locales->is_not_empty, "code"=>"IS NOT EMPTY"],
			["name" =>$locales->is_empty, "code"=>"IS EMPTY"],
			["name" =>$locales->contains, "code"=>"MATRIX CONTAINS"],
			["name" =>$locales->does_not_contain,"code"=>"MATRIX DOES NOT CONTAIN"],
		];
	}

	public function getCollationSqlWhere($whereArray)
	{
		if (empty($whereArray['operation']) || empty($whereArray['code'])) return '';
		switch ($whereArray['operation'])
		{
			case 'IS NOT EMPTY':
				return "{$this->settings['finalTableCode']}.{$whereArray['code']} <> \"\"";
			case 'IS EMPTY':
				return "({$this->settings['finalTableCode']}.{$whereArray['code']} = \"\" OR {$this->settings['finalTableCode']}.{$whereArray['code']} IS NULL)";
			case 'MATRIX CONTAINS':
			case 'MATRIX DOES NOT CONTAIN':
				if(!is_array($whereArray['value']))
				{
					$whereArray['value'] = intval($whereArray['value']);
					return "{$this->settings['finalTableCode']}.id = {$whereArray['value']}";
				}
				else
				{
					$whereArray['value'] = array_map('intval', $whereArray['value']);
					$whereArray['value'] = implode(',',$whereArray['value']);
					return "{$this->settings['finalTableCode']}.id IN({$whereArray['value']})";
				}
		}
		return '';
	}

	protected function getTemplate($whereArray,$where)
	{
		$notCollation = '';
		if($whereArray['operation'] == 'MATRIX DOES NOT CONTAIN')
			$notCollation = 'NOT';

		if ($this->settings['isManyToMany'])
			return "{$this->settings['localField']} IN (SELECT {$this->settings['nodeTableCode']}.{$this->settings['nodeTableField']} FROM {$this->settings['nodeTableCode']} JOIN {$this->settings['finalTableCode']} ON {$this->settings['finalTableCode']}.{$this->settings['finalTableField']} = {$this->settings['nodeTableCode']}.{$this->settings['nodeTableFinalTableField']} WHERE {$where} )";
		return "{$this->settings['localField']} {$notCollation} IN (SELECT {$this->settings['finalTableCode']}.{$this->settings['finalTableField']} FROM {$this->settings['finalTableCode']} WHERE  {$where} )";
	}

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		if (empty($whereArray['value']))
			return '';
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

		$where = $this->getCollationSqlWhere($whereArray);
		if (empty($where)) return '';

		return $this->getTemplate($whereArray,$where);
	}
}
