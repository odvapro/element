<?php

class EmMatrixField extends FieldBase
{
	static $nodeTable = [];
	static $finalTable = [];

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
		$isManyToMany = isset($this->settings['isManyToMany']) ? ($this->settings['isManyToMany'] === 'true') : false;

		if ($isManyToMany && (empty(self::$nodeTable) || empty(self::$nodeTable[$this->settings['nodeTableCode']])))
		{
			$selectResult = $this->element->select(['from' => $this->settings['nodeTableCode']]);
			self::$nodeTable[$this->settings['nodeTableCode']] = $selectResult['success'] ? $selectResult['result'] : [];
		}

		if (empty(self::$nodeTable) || empty(self::$nodeTable[$this->settings['finalTableCode']]))
		{
			$selectResult = $this->element->select(['from' => $this->settings['finalTableCode']]);
			self::$nodeTable[$this->settings['finalTableCode']] = $selectResult['success'] ? $selectResult['result'] : [];
		}

		$node = [];
		$localFieldValue = $this->row[$this->settings['localField']];

		$finalTableField = $this->settings['finalTableField'];

		if (
			$isManyToMany
			&& isset($this->settings['nodeTableCode'])
			&& isset($this->settings['nodeTableField'])
			&& isset($this->settings['nodeTableFinalTableField'])
		)
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

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		$settings = [
			'isManyToMany'             => isset($this->settings['isManyToMany']) ? ($this->settings['isManyToMany'] === 'true') && isset($this->settings['nodeTableCode']) && isset($this->settings['nodeTableField']) && isset($this->settings['nodeTableFinalTableField']) : false,
			'localField'               => isset($this->settings['localField']) ? $this->settings['localField'] : null,
			'nodeTableCode'            => isset($this->settings['nodeTableCode']) ? $this->settings['nodeTableCode'] : null,
			'nodeTableField'           => isset($this->settings['nodeTableField']) ? $this->settings['nodeTableField'] : null,
			'nodeTableFinalTableField' => isset($this->settings['nodeTableFinalTableField']) ? $this->settings['nodeTableFinalTableField'] : null,
			'finalTableCode'           => isset($this->settings['finalTableCode']) ? $this->settings['finalTableCode'] : null,
			'finalTableField'          => isset($this->settings['finalTableField']) ? $this->settings['finalTableField'] : null,
			'field'                    => isset($whereArray['value']['field']) ? quotemeta($whereArray['value']['field']) : null,
			'value'                    => isset($whereArray['value']['value']) ? quotemeta($whereArray['value']['value']) : null,
		];

		// если не хватает конфига
		if (
			empty($settings['localField'])
			|| empty($settings['finalTableCode'])
			|| empty($settings['finalTableField'])
			|| empty($settings['field'])
		) return '';

		$getTemplate = function($where) use ($settings)
		{
			if ($settings['isManyToMany'])
				return "{$settings['localField']} IN (SELECT {$settings['nodeTableCode']}.{$settings['nodeTableField']} FROM {$settings['nodeTableCode']} JOIN {$settings['finalTableCode']} ON {$settings['finalTableCode']}.{$settings['finalTableField']} = {$settings['nodeTableCode']}.{$settings['nodeTableFinalTableField']} {$where} )";
			return "{$settings['localField']} IN (SELECT {$settings['finalTableCode']}.{$settings['finalTableField']} FROM {$settings['finalTableCode']} {$where} )";
		};

		switch ($whereArray['operation']) {
			case 'IS NOT EMPTY':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} <> \"\"");
			case 'IS EMPTY':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} = \"\" OR {$settings['finalTableCode']}.{$settings['field']} IS NULL");
			case 'IS':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} = \"{$settings['value']}\"");
			case 'IS NOT':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} <> \"{$settings['value']}\" ");
			case 'CONTAINS':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} LIKE \"%{$settings['value']}%\"");
			case 'DOES NOT CONTAIN':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} LIKE \"%{$settings['value']}%\"");
			case 'IS LARGER':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} >= \"{$settings['value']}\"");
			case 'IS SMALLER':
				return $getTemplate("WHERE {$settings['finalTableCode']}.{$settings['field']} <= \"{$settings['value']}\"");
		}
		return '';
	}
}
