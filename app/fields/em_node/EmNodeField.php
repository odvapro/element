<?php

class EmNodeField extends FieldBase
{
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$select  = [
			'from'  => $this->settings['nodeTableCode'],
			'where' => [
				'operation' => 'and',
				'fields'    => [[
					'code'      => $this->settings['nodeFieldCode'],
					'operation' => 'IS',
					'value'     => $this->fieldValue
				]]
			]
		];
		$node = $this->element->select($select);

		if(!count($node) )
			return [];

		$node = $node[0];
		return [
			'id'   => $node[$this->settings['nodeFieldCode']]['value'],
			'name' => $node[$this->settings['nodeSearchCode']]['value'],
			'url'  => "/table/{$this->settings['nodeTableCode']}/el/{$node[$this->settings['nodeFieldCode']]['value']}"
		];
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(empty($this->fieldValue))
			return NULL;

		if(is_numeric($this->fieldValue))
			return intval($this->fieldValue);

		if(is_string($this->fieldValue))
			return NULL;

		if(!is_array($this->fieldValue))
			return intval($this->fieldValue);

		return $this->fieldValue['id'];
	}

	/**
	 * Gets collations
	 * @return array
	 */
	public function getCollations()
	{
		return [
			['name'=>'Is','code'=>'IS'],
			['name'=>'Is Not','code'=>'IS NOT'],
			['name'=>'Is Empty','code'=>'IS EMPTY'],
			['name'=>'Is Not Empty','code'=>'IS NOT EMPTY']
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
				return $whereArray['code'] . ' = ' . "'" . $whereArray['value'] . "'";
				break;

			case 'IS NOT':
				return $whereArray['code'] . ' <> ' . "'" . $whereArray['value'] . "'";
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