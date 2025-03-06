<?php

class EmUuidField extends FieldBase
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
		return $this->fieldValue != '' ? $this->fieldValue : $this->guidv4();
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return $this->fieldValue != '' ? $this->fieldValue : $this->guidv4();
	}

	private function guidv4($data = null)
	{
		$data = $data ?? random_bytes(16);
		assert(strlen($data) == 16);
	
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
	
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
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
