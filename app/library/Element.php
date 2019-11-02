<?php
use Phalcon\Di;

class Element
{
	protected $eldb;
	protected $di;
	protected $fieldsTypes = [];

	/**
	 * __construct достаем и регистрируем папки с полями таблиц
	 */
	public function __construct($eldb, $di)
	{
		$this->eldb    = $eldb;
		$this->di      = $di;
		$this->emTypes = $this->getEmTypes();
	}

	/**
	 * Достать параметры филда
	 * @return array
	 */
	public function getEmTypes()
	{
		global $loader;
		$config = $this->di->get('config');
		static $emTypes = [];
		if(!empty($emTypes)) return $emTypes;

		if ($handle = opendir($config->application->fldDir))
		{
			while($fieldCode = readdir($handle))
			{
				$fieldDirPath     = $config->application->fldDir . $fieldCode;
				$fieldComponent   = $fieldCode;
				$fieldComponent   = explode('_', $fieldComponent);
				$fieldComponent   = array_map('ucfirst', $fieldComponent);
				$fieldComponent[] = 'Field';
				$fieldComponent   = implode('', $fieldComponent);

				if(strpos($fieldCode, '.') !== false || !is_dir($fieldDirPath))
					continue;

				$loader->registerDirs([$fieldDirPath], true)->register();

				if(class_exists($fieldComponent))
				{
					$fieldClass          = new $fieldComponent();
					$emTypes[$fieldCode] = $fieldClass->getSettings();
				}
			}
			closedir($handle);
		}

		return $emTypes;
	}

	/**
	 * Достать колонки таблицы c типами
	 * @param  string $tableName
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

		foreach ($emFields as $emFieldsTypes)
		{
			if(!array_key_exists($emFieldsTypes->field, $tableColumns) || !array_key_exists($emFieldsTypes->type, $this->emTypes))
				#TODO добавление доп полей которых нет в бд и тд
				continue;

			$fieldClass = new $this->emTypes[$emFieldsTypes->type]['fieldComponent']('', $emFieldsTypes->settings);

			$emFieldArray = [
				'name'         => $emFieldsTypes->name,
				'type'         => $emFieldsTypes->type,
				'type_info'    => $this->emTypes[$emFieldsTypes->type],
				'settings'     => $fieldClass->getSettings(),
				'required'     => $emFieldsTypes->getRequired(),
				'fieldJs'      => $fieldClass->getFieldJs(),
				'settingsJs'   => $fieldClass->getSettingsJs(),
				'stylesCss' => $fieldClass->getStylesCss()
			];
			$tableColumns[$emFieldsTypes->field]['em'] = $emFieldArray;
		}

		// этот цикл для установки типов полей по умолчанию
		// то есть em_string и em_primary
		foreach ($tableColumns as &$tableColumn)
		{
			if(array_key_exists('em', $tableColumn))
				continue;

			$defaultType = ($tableColumn['key'] == 'PRI')?$this->emTypes['em_primary']:$this->emTypes['em_string'];
			$fieldClass = new $defaultType['fieldComponent']();
			$emFieldArray = [
				'name'      => '',
				'type'      => $tableColumn['type'],
				'type_info' => $defaultType,
				'settings'  => $fieldClass->getSettings(),
				'required'  => false,
			];
			$tableColumn['em'] = $emFieldArray;
		}

		return $tableColumns;
	}

	/**
	 * Селект запрос, достаем значения и типы полей для отображения
	 * @param  array $selectParams
	 * @return array
	 */
	public function select($selectParams)
	{
		if (empty($selectParams) || empty($selectParams['from']))
			return false;

		$tableColumns = $this->getColumns($selectParams['from']);
		$selectResult = $this->eldb->select($selectParams);

		if ($selectResult === false)
			return false;

		/**
		 * Добавляем в селект запрос, поля для отображения
		 */
		$selectResultWithFields = array_map(function ($selectItem) use ($tableColumns, $selectParams)
		{
			$result = [];
			foreach ($selectItem as $fieldCode => $columnValue)
			{
				$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
				$settings   = $tableColumns[$fieldCode]['em']['settings'];

				if (class_exists($fieldClass))
					$field = new $fieldClass($columnValue,$settings,$selectItem);
				else
					$field = new EmStringField($columnValue,$settings,$selectItem);

				$result[$fieldCode]['value']     = $field->getValue();
				$result[$fieldCode]['fieldName'] = $tableColumns[$fieldCode]['em']['type_info']['code'];
			}
			return $result;
		}, $selectResult);

		return $selectResultWithFields;
	}

	/**
	 * Update request
	 * @return array
	 */
	public function update($updateParams)
	{
		if (empty($updateParams) || empty($updateParams['set']))
			return false;

		$tableColumns = $this->getColumns($updateParams['table']);

		$set = [];
		foreach ($updateParams['set'] as $fieldCode => $fieldValue)
		{
			$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
			$settings   = $tableColumns[$fieldCode]['em']['settings'];

			if (class_exists($fieldClass))
				$field = new $fieldClass($fieldValue,$settings,$updateParams['set']);
			else
				$field = new EmStringField($fieldValue,$settings,$updateParams['set']);

			$fieldSaveValue = $field->saveValue();
			if($fieldSaveValue === NULL)
				$set[] = "{$fieldCode} = NULL";
			else
				$set[] = "{$fieldCode} = '{$fieldSaveValue}'";
		}
		$updateParams['set'] = $set;

		return $this->eldb->update($updateParams);
	}

	/**
	 * Insert request
	 * @return array
	 */
	public function insert($insertParams)
	{
		if (empty($insertParams) || empty($insertParams['table']) || empty($insertParams['columns']) || empty($insertParams['values']))
			return false;

		if(count($insertParams['columns']) != count($insertParams['values']))
			return false;

		$tableColumns = $this->getColumns($insertParams['table']);

		$valuesSet = [];
		foreach ($insertParams['columns'] as $fieldIndex => $fieldCode)
		{
			$fieldValue = $insertParams['values'][$fieldIndex];
			$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
			$settings   = $tableColumns[$fieldCode]['em']['settings'];

			if (class_exists($fieldClass))
				$field = new $fieldClass($fieldValue,$settings,$insertParams['values']);
			else
				$field = new EmStringField($fieldValue,$settings,$insertParams['values']);

			$fieldSaveValue = $field->saveValue();
			$valuesSet[]    = $fieldSaveValue;
		}
		$insertParams['values'] = $valuesSet;

		return $this->eldb->insert($insertParams);
	}
}