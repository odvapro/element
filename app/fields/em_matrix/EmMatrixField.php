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
					'value'     => $this->row[$this->settings['keyField']]
				]]
			]
		];
		$node = $this->element->select($select)['items'];

		if(!count($node))
			return [];

		return ['matrixValue' => $node ];
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return NULL;
	}
}