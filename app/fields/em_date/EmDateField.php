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
			return NULL;
		return $this->fieldValue;
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
			['name'=>'Is Not Empty','code'=>'IS NOT EMPTY'],
			['name'=>'Is Larger','code'=>'IS LARGER'],
			['name'=>'Is Smaller','code'=>'IS SMALLER'],

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
				return "{$whereArray['code']} IS NOT NULL OR {$whereArray['code']} <> \"\" ";
			break;

			case 'IS LARGER':
				return $whereArray['code'] . ' > ' . "'" . $whereArray['value'] . "'";
			break;

			case 'IS SMALLER':
				return $whereArray['code'] . ' < ' . "'" . $whereArray['value'] . "'";
			break;
		}
		return '';
	}
}