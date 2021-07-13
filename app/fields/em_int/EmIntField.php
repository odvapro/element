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
}
