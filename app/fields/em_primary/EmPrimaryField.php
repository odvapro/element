<?php

class EmPrimaryField extends FieldBase
{
	protected $fieldValue = '';

	/**
	 * Конструктор принимает значение поля
	 */
	public function __construct($fieldValue = '')
	{
		$this->fieldValue = $fieldValue;
	}

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return intval($this->fieldValue);
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return intval($this->fieldValue);
	}

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
				return "({$whereArray['code']} IS NOT NULL OR {$whereArray['code']} <> \"\" )";
			break;

			case 'IS LARGER':
				return $whereArray['code'] . ' > :value:';
			break;

			case 'IS SMALLER':
				return $whereArray['code'] . ' < :value:';
			break;
			case 'IN':
				return $whereArray['code'] . ' IN ('.implode(
					',', array_fill(0, count($whereArray['value']), ':value:')
				).')';
			break;
		}
		return '';
	}
}
