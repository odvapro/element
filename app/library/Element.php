<?php
use Phalcon\Di;

abstract class Element
{
	private static function getColumnsType($tableName, $tableColumns)
	{
		if (empty($tableName))
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

		return $tableColumn;
	}
	public static function getColumns($tableName)
	{
		$eldb = Di::getDefault()->get('eldb');

		if (empty($tableName))
			return false;

		$tableColumns = $eldb->getColumns($tableName);

		if (empty($tableColumns))
			return false;

		$tableColumns = Element::getColumnsType($tableName, $tableColumns);

		return $tableColumns;
	}
}