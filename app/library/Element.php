<?php

class Element
{
	protected $eldb;

	public function __construct($eldb)
	{
		$this->eldb = $eldb;
	}

	/**
	 * Достать колонки таблицы c типами
	 * @param  [string] $tableName
	 * @return Array
	 */
	public function getColumns($tableName)
	{
		if (empty($tableName))
			return false;

		$tableColumns = $this->eldb->getColumns($tableName);

		if (empty($tableColumns))
			return false;

		// определить тип филда - переопределенный
		// если нет переопределения - ставим тип em_string

		$emFields = EmTypes::find([
			'conditions' => 'table = ?0',
			'bind'       => [$tableName]
		]);
		foreach ($emFields as $emFieldInfoObject)
		{
			if(!array_key_exists($emFieldInfoObject->field, $tableColumns))
				#TODO добавление доп полей которых нет в бд и тд
				continue;

			$emFieldArray = [
				'em_type'     => $emFieldInfoObject->type,
				'em_settings' => $emFieldInfoObject->getSettings(),
				'em_required' => $emFieldInfoObject->getRequired()
			];
			$tableColumns[$emFieldInfoObject->field] = array_merge($tableColumns[$emFieldInfoObject->field],$emFieldArray);
		}

		foreach ($tableColumns as &$tableColumn)
		{
			if(array_key_exists('em_type', $tableColumn))
				continue;
			$emFieldArray = [
				'em_type'     => "em_string",
				'em_settings' => [],
				'em_required' => false
			];
			$tableColumn = array_merge($tableColumn, $emFieldArray);
		}

		return $tableColumns;
	}

	public function select($selectParams)
	{
		if (empty($selectParams))
			return false;

		if (empty($selectParams['from']))
			return false;

		$fieldsParam = $this->getColumns($selectParams['from']);
		$selectResult = $this->eldb->select($selectParams);

		if ($selectResult === false)
			return false;

		$selectResultWithFields = array_map(function ($selectItem) use ($fieldsParam)
		{
			$result = [];

			foreach ($selectItem as $key => $columnValue)
			{
				$result[$key]['type']     = $fieldsParam[$key]['em_type'];
				$result[$key]['settings'] = $fieldsParam[$key]['em_settings'];
				$result[$key]['value']    = $columnValue;
			}
			return $result;
		}, $selectResult);

		return $selectResultWithFields;
	}
}