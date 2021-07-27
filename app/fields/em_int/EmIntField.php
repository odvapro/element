<?php

class EmIntField extends FieldBase
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
		if (isset($this->fieldValue) && $this->fieldValue !== '')
		{
			if (isset($this->settings['max'])
				&& isset($this->settings['max']['enabled'])
				&& +$this->settings['max']['enabled']
				&& isset($this->settings['max']['value'])
				&& +$this->fieldValue > +$this->settings['max']['value']
			)
				throw new EmException("{$this->settings['code']}: Maximum value is {$this->settings['max']['value']}", 13);

			if (isset($this->settings['min'])
				&& isset($this->settings['min']['enabled'])
				&& +$this->settings['min']['enabled']
				&& isset($this->settings['min']['value'])
				&& +$this->fieldValue < +$this->settings['min']['value']
			)
				throw new EmException("{$this->settings['code']}: Minimum value is {$this->settings['min']['value']}", 14);
		}

		return intval($this->fieldValue);
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
			['name'=>$locales->contains,'code'=>'CONTAINS'],
			['name'=>$locales->does_not_contain,'code'=>'DOES NOT CONTAIN'],
			['name'=>$locales->start_with,'code'=>'START WITH'],
			['name'=>$locales->ends_with,'code'=>'ENDS WITH'],
		];
	}

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		$whereArray['value'] = intval($whereArray['value']);
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

			case 'CONTAINS':
				return $whereArray['code'] . ' LIKE :value:';
				break;

			case 'DOES NOT CONTAIN':
				return $whereArray['code'] . ' NOT LIKE :value:';
				break;

			case 'START WITH':
				return $whereArray['code'] . ' LIKE :value:';
				break;

			case 'ENDS WITH':
				return $whereArray['code'] . ' LIKE :value:';
				break;
		}
		return '';
	}
}
