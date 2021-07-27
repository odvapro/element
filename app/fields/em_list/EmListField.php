<?php

class EmListField extends FieldBase
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
