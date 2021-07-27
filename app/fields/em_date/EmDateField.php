<?php

class EmDateField extends FieldBase
{
	protected $fieldValue = '';
	protected $settings = [];
	/**
	 * Конструктор принимает значение поля
	 */
	public function __construct($fieldValue = '', array $settings = [])
	{
		$this->fieldValue = $fieldValue;
		$this->settings = $settings;
	}

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return strval(strip_tags($this->fieldValue));
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(empty($this->fieldValue) || strtotime($this->fieldValue) === false)
			return null;
		return $this->fieldValue;
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
			['name'=>$locales->is_not_empty,'code'=>'IS NOT EMPTY'],
			['name'=>$locales->is_larger,'code'=>'IS LARGER'],
			['name'=>$locales->is_smaller,'code'=>'IS SMALLER'],
		];
	}

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		$dateFormat = isset($this->settings['includeTime']) && isset($this->settings['includeTime']) === 'true' ? "Y-m-d H:i:s" : "Y-m-d";
		$whereArray['value'] = date($dateFormat, strtotime($whereArray['value']));
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
				return "{$whereArray['code']} IS NOT NULL OR {$whereArray['code']} <> \"\" ";
			break;

			case 'IS LARGER':
				return $whereArray['code'] . ' > :value:';
			break;

			case 'IS SMALLER':
				return $whereArray['code'] . ' < :value:';
			break;
		}
		return '';
	}
}
