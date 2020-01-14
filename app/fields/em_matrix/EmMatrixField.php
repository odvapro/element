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
}