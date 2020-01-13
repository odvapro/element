<?php

class EmMatrixField extends FieldBase
{
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		if(empty($this->fieldValue))
			return [];

		return explode(',', $this->fieldValue);
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(is_array($this->fieldValue))
			return implode(',',$this->fieldValue);
		else
			return '';
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