<?php

class EmTypes extends ModelBase
{
	/**
	 * Достать настройки поля таблицы в виде json
	 */
	public function afterFetch()
	{
		if(empty($this->settings))
			$this->settings = [];
		else
			$this->settings = json_decode($this->settings, true);
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
	/**
	 * Перед сохранением
	 * @return [type] [description]
	 */
	public function beforeSave()
	{
		if (!empty($this->settings))
			$this->settings = json_encode($this->settings);
		else
			$this->settings = NULL;

		return true;
	}
}