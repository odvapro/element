<?php

class EmMatrixField extends FieldBase
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
					'code'      => $this->settings['nodeField'],
					'operation' => 'IS',
					'value'     => $this->fieldValue
				]]
			]
		];
		$node = $this->element->select($select);

		if(!count($node) )
			return [];

		return [
			'matrixValue' => $node
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
}