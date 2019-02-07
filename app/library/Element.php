<?php
use Phalcon\Di;

class Element
{
	protected $eldb;
	protected $di;

	public function __construct($eldb, $di)
	{
		$this->eldb = $eldb;
		$this->di = $di;

		global $loader;

		$config = $this->di->get('config');

		// проходимся по папкам типов полей
		// регистрируем эти папки
		// выносим в переменную содержимое info.json
		if ($handle = opendir($config->application->fldDir))
		{
			while($fieldName = readdir($handle))
			{
				$field = [];
				$fieldDirPath = $config->application->fldDir . $fieldName;
				if(strpos($fieldName, '.') === false && is_dir($fieldDirPath))
				{
					$loader->registerDirs([$fieldDirPath], true)->register();

					// подготовка имени класса (если есть нижнее подчеркивание)
					$className = explode('_', $fieldName);

					foreach ($className as  &$classNamePart)
						$classNamePart = ucfirst($classNamePart);
					// $className[] = 'Field';
					$className   = implode('', $className);

					$fieldInfo = $className;
				}
			}
			closedir($handle);
		}
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

		// $fields = Di::getDefault()->get('fields');
		$selectResultWithFields = array_map(function ($selectItem) use ($fieldsParam)
		{
			$result = [];

			foreach ($selectItem as $key => $columnValue)
			{
				$fieldClass = explode('_', $fieldsParam[$key]['em_type']);
				$fieldClass = array_map('ucfirst', $fieldClass);
				$fieldClass = implode('', $fieldClass);
				$field = new $fieldClass($columnValue);

				$result[$key]['type']     = $fieldsParam[$key]['em_type'];
				$result[$key]['class']    = $fieldClass;
				$result[$key]['settings'] = $fieldsParam[$key]['em_settings'];
				$result[$key]['value']    = $field->getValue();
			}
			return $result;
		}, $selectResult);

		return $selectResultWithFields;
	}
}