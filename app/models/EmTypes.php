<?php

class EmTypes extends ModelBase
{
	/**
	 * Достать настройки поля таблицы в виде json
	 */
	public function getSettings()
	{
		if(empty($this->settings))
			return [];
		return json_decode($this->settings, true);
	}
	/**
	 * Обязательное поле
	 * @return bool
	 */
	public function getRequired()
	{
		if($this->required === '1')
			return true;
		return false;
	}

}