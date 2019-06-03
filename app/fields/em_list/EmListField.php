<?php

class EmListField extends FieldBase
{
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$settingsList = empty($this->settings) ? false : $this->settings['list'];

		if (empty($settingsList))
			return $this->fieldValue;

		foreach ($settingsList as $listItem)
			if ($listItem['key'] == $this->fieldValue)
				return $listItem['key'];

		return $this->fieldValue;
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		$settingsList = empty($this->settings) ? false : $this->settings['list'];

		if (empty($settingsList))
			return $this->fieldValue;

		foreach ($settingsList as $listItem)
			if ($listItem['key'] == $this->fieldValue)
				return $listItem['key'];

		return $this->fieldValue;
	}
}