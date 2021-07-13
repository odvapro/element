<?php
class EmCheckField extends FieldBase
{
	/**
	 * Returns checked status string
	 * @return string
	 */
	private function getChechedString()
	{
		if(isset($this->settings['checkedString']))
			return $this->settings['checkedString'];
		return '1';
	}

	/**
	 * Returns unchecked status string
	 * @return string
	 */
	private function getUnChechedString()
	{
		if(isset($this->settings['uncheckedString']))
			return $this->settings['uncheckedString'];
		return '0';
	}

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return $this->fieldValue === $this->getChechedString() ? true : false;
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if($this->fieldValue == 'false' || empty($this->fieldValue))
			return $this->getUnChechedString();
		return $this->getChechedString();
	}
}
