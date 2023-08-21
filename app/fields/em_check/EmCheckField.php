<?php
class EmCheckField extends FieldBase
{
	/**
	 * Returns checked status string
	 * @return string
	 */
	private function getCheckedString()
	{
		if(isset($this->settings['checkedString']))
			return $this->settings['checkedString'];

		return true;
	}

	/**
	 * Returns unchecked status string
	 * @return string
	 */
	private function getUnCheckedString()
	{
		if(isset($this->settings['uncheckedString']))
			return $this->settings['uncheckedString'];

		return false;
	}

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return boolval($this->fieldValue) === boolval($this->getCheckedString()) ? true : false;
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if($this->fieldValue == 'false' || empty($this->fieldValue))
			return $this->getUnCheckedString();

		return $this->getCheckedString();
	}
}
