<?php

class EmViews extends ModelBase
{
	/**
	 * Преобразовать поля в json при доставании из бд
	 */
	public function afterFetch()
	{
		$this->settings = (empty($this->settings)) ? [] : json_decode($this->settings);
		$this->filter   = (empty($this->filter)) ? [] : json_decode($this->filter);
		$this->sort     = (empty($this->sort)) ? [] : json_decode($this->sort);
	}
	/**
	 * Преобразовать поля в json перед сохранением
	 */
	public function beforeSave()
	{
		$this->settings = $this->settings ? json_encode($this->settings) : '[]';
		$this->filter   = $this->filter ? json_encode($this->filter) : '[]';
		$this->sort     = $this->sort ? json_encode($this->sort) : '[]';
	}
}