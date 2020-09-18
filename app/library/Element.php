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
				'collations'   => $fieldClass->getCollations(),
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
				'name'       => '',
				'type'       => $tableColumn['type'],
				'type_info'  => $defaultType,
				'settings'   => $fieldClass->getSettings(),
				'collations' => $fieldClass->getCollations(),
				'required'   => false,
			];
			$tableColumn['em'] = $emFieldArray;
		}

		return $tableColumns;
	}

	/**
	 * Prepare where paremetrs of select array
	 * change where array
	 * @return modified selectParams
	 */
	private function _prepareRequestParams($selectParams)
	{
		if(empty($selectParams['where']) || empty($selectParams['where']['fields']))
			return $selectParams;

		$tableName = (!empty($selectParams['from']))?$selectParams['from']:$selectParams['table'];
		$tableColumns = $this->getColumns($tableName);

		$selectParams['where']['fields'] = array_map(function ($wherePart) use ($tableColumns)
		{
			if(empty($wherePart['code']))
				return $wherePart;
			$fieldCode = $wherePart['code'];
			if(empty($tableColumns[$fieldCode]))
				return $wherePart;

			$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
			$settings   = $tableColumns[$fieldCode]['em']['settings'];

			if (class_exists($fieldClass))
				$field = new $fieldClass('',$settings);
			else
				$field = new EmStringField('',$settings);

			$wherePart['code'] = $field->getCollationSql($wherePart);
			return $wherePart;

		}, $selectParams['where']['fields']);

		return $selectParams;
	}

	/**
	 * Selects count of elemets
	 * @param  array $selectParams
	 * @return int
	 */
	public function count($selectParams)
	{
		$selectParams = $this->_prepareRequestParams($selectParams);
		return $this->eldb->count($selectParams);
	}

	/**
	 * Delete elements by params
	 * @param  array $deleteParams
	 * @return boolean
	 */
	public function delete($deleteParams)
	{
		$deleteParams = $this->_prepareRequestParams($deleteParams);
		return $this->eldb->delete($deleteParams);
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
		$selectParams = $this->_prepareRequestParams($selectParams);
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
		$updateParams = $this->_prepareRequestParams($updateParams);

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
		if (empty($insertParams) || empty($insertParams['table']) || empty($insertParams['values']))
			return false;

		$tableColumns = $this->getColumns($insertParams['table']);

		$valuesSet = [];
		foreach ($insertParams['values'] as $fieldCode => $fieldValue)
		{
			if (!isset($tableColumns[$fieldCode]))
				return false;

			$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
			$settings   = $tableColumns[$fieldCode]['em']['settings'];

			if (class_exists($fieldClass))
				$field = new $fieldClass($fieldValue,$settings,$insertParams['values']);
			else
				$field = new EmStringField($fieldValue,$settings,$insertParams['values']);

			$fieldSaveValue = $field->saveValue();
			$valuesSet[]    = $fieldSaveValue;
		}
		$insertParams['columns'] = array_keys($insertParams['values']);
		$insertParams['values']  = $valuesSet;

		return $this->eldb->insert($insertParams);
	}
}