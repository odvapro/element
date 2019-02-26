<?php

class EmNodeField extends FieldBase
{
	protected $fieldValue = '';
	protected $table      = '';
	protected $columns    = [];
	protected $settings   = [];
	/**
	 * Конструктор принимает значение поля
	 */
	public function __construct($fieldValue = '', $tableCode = '', $columns = [])
	{
		$this->fieldValue = $fieldValue;
		$this->table      = $tableCode;
		$this->columns    = $columns;

		$this->settings   = $columns['em']['settings'];
	}
	/**
	 * Добавить настройки для поля
	 */
	public function setSettings()
	{

	}
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$eldb = $this->di->get('db');
		$config  = $this->di->get('config');
		$baseUri = $config->application->baseUri;
		$nodeElement = [];

		$whereSql = $this->settings['bindField'] . " IN (" . $this->fieldValue . ")";

		$tableResult = $eldb->fetchAll(
			"SELECT * FROM " . $this->settings['bindTable'] . " WHERE  $whereSql ",
			Phalcon\Db::FETCH_ASSOC
		);
		foreach ($tableResult as $tableValue)
		{
			$nodeElement         = [];
			$nodeElement['id']   = $tableValue[$this->settings['bindField']];
			$nodeElement['name'] = $tableValue[$this->settings['searchField']];
			$nodeElement['url']  = "{$baseUri}table/{$this->settings['bindTable']}/edit/{$tableValue[$this->settings['bindField']]}";
		}

		return $nodeElement;
	}
	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{

	}
}