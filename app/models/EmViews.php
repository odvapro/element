<?php

class EmViews extends ModelBase
{
	/**
	 * Преобразовать поля в json при доставании из бд
	 */
	public function afterFetch()
	{
		$this->settings = json_decode($this->settings, true);
		$this->filter   = json_decode($this->filter, true);
		$this->sort     = json_decode($this->sort, true);
	}
	/**
	 * Преобразовать поля в json перед сохранением
	 */
	public function beforeSave()
	{
		$this->settings = isset($this->settings) ? json_encode($this->settings) : json_encode([]);
		$this->filter   = isset($this->filter) ? json_encode($this->filter) : json_encode([]);
		$this->sort     = isset($this->sort) ? json_encode($this->sort) : json_encode([]);
	}
}