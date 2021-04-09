<?php

class EmMatrixField extends FieldBase
{
	static $nodeTable = [];

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		if (empty(self::$nodeTable) || empty(self::$nodeTable[$this->settings['nodeTableCode']]))
			self::$nodeTable[$this->settings['nodeTableCode']] = $this->element->select(['from' => $this->settings['nodeTableCode']]);

		if (empty(self::$nodeTable[$this->settings['nodeTableCode']]) || empty(self::$nodeTable[$this->settings['nodeTableCode']]['items']))
			return [];

		$node = [];
		foreach (self::$nodeTable[$this->settings['nodeTableCode']]['items'] as $nodeItem) {
			if ($nodeItem[$this->settings['nodeField']] == $this->row[$this->settings['keyField']])
				$node[] = $nodeItem;
		}

		if(empty($node))
			return [];

		return ['matrixValue' => $node];
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return null;
	}
}
