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

		$groups = empty($di->get('user')) ? [$di->get('group')] : $di->get('user')->groups;
		$this->dbHooks = new DBHooks($groups, $this->di);
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
			if(empty($wherePart['code']) || empty($tableColumns[$wherePart['code']]))
				return $wherePart;

			$columnEm = $tableColumns[$wherePart['code']]['em'];

			$fieldClass = $columnEm['type_info']['fieldComponent'];
			$settings   = $columnEm['settings'];

			if (class_exists($fieldClass))
				$field = new $fieldClass('',$settings);
			else
				$field = new EmStringField('',$settings);

			$wherePart['code_sql'] = $field->getCollationSql($wherePart);
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
		try {
			$beforeHookResult = $this->dbHooks->before('count', $selectParams);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		$selectParams = $this->_prepareRequestParams($selectParams);

		$result = $this->eldb->count($selectParams);
		try {
			$afterHookResult = $this->dbHooks->after('count', $selectParams, $result);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		return ['success' => true, 'result' => $afterHookResult];
	}

	/**
	 * Delete elements by params
	 * @param  array $deleteParams
	 * @return boolean
	 */
	public function delete($deleteParams)
	{
		try {
			$beforeHookResult = $this->dbHooks->before('delete', $deleteParams);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		$deleteParams = $this->_prepareRequestParams($deleteParams);

		$result = $this->eldb->delete($deleteParams);

		try {
			$afterHookResult = $this->dbHooks->after('delete', $deleteParams, $result);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		return ['success' => true, 'result' => $afterHookResult];
	}

	/**
	 * Селект запрос, достаем значения и типы полей для отображения
	 * @param  array $selectParams
	 * @return array
	 */
	public function select($selectParams)
	{
		try {
			$beforeHookResult = $this->dbHooks->before('select', $selectParams);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		if (empty($selectParams) || empty($selectParams['from'])) return ['success' => false, 'message' => 'empty_request', 'code' => 1];

		$tableColumns = $this->getColumns($selectParams['from']);
		$selectParams = $this->_prepareRequestParams($selectParams);
		$selectResult = $this->eldb->select($selectParams);

		if ($selectResult === false)
			return ['success' => false, 'message' => 'select_error', 'code' => 6];

		$resultItems = [];
		$resultColumns = [];
		foreach ($tableColumns as $column)
			$resultColumns[$column['field']] = $column['em']['type_info']['code'];

		/**
		 * Добавляем в селект запрос, поля для отображения
		 */
		$resultItems = array_map(function ($selectItem) use ($tableColumns, $selectParams)
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

				$result[$fieldCode] = $field->getValue();
			}
			return $result;
		}, $selectResult);

		$result = ['items' => $resultItems, 'columns_types' => $resultColumns];

		try {
			$afterHookResult = $this->dbHooks->after('select', $selectParams, $result);
		} catch (EmException $e) {
			return ['success' => false, 'code' => $e->getMessage(), 'code' => $e->getCode()];
		}

		return ['success' => true, 'result' => $afterHookResult];
	}

	/**
	 * Update request
	 * @return array
	 */
	public function update($updateParams)
	{
		try {
			$beforeHookResult = $this->dbHooks->before('update', $updateParams);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		if (empty($updateParams) || empty($updateParams['set'])) return ['success' => false, 'message' => 'update_error', 'code' => 7];

		$tableColumns = $this->getColumns($updateParams['table']);
		$updateParams = $this->_prepareRequestParams($updateParams);

		foreach ($updateParams['set'] as $fieldCode => &$fieldValue)
		{
			$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
			$settings   = $tableColumns[$fieldCode]['em']['settings'];

			if (class_exists($fieldClass))
				$field = new $fieldClass($fieldValue,$settings,$updateParams['set']);
			else
				$field = new EmStringField($fieldValue,$settings,$updateParams['set']);

			$fieldValue = $field->saveValue();
		}

		$result = $this->eldb->update($updateParams);

		try {
			$afterHookResult = $this->dbHooks->after('update', $updateParams, $result);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		return ['success' => true, 'result' => $afterHookResult];
	}

	/**
	 * Insert request
	 * @return array
	 */
	public function insert($insertParams)
	{
		try {
			$beforeHookResult = $this->dbHooks->before('insert', $insertParams);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		if (empty($insertParams) || empty($insertParams['table']) || empty($insertParams['values']) || !is_array($insertParams['values'][0]))
			return ['success' => false, 'message' => 'empty_request', 'code' => 1];

		$tableColumns = $this->getColumns($insertParams['table']);

		$dbInsertParams = [
			'table' => $insertParams['table'],
			'columns' => [],
			'values' => [],
		];

		foreach ($insertParams['values'] as $insertValue)
		{
			$valuesSet = [];
			foreach ($insertValue as $fieldCode => $fieldValue)
			{
				if (!isset($tableColumns[$fieldCode]))
					continue;
				$fieldClass = $tableColumns[$fieldCode]['em']['type_info']['fieldComponent'];
				$settings   = $tableColumns[$fieldCode]['em']['settings'];

				if (class_exists($fieldClass))
					$field = new $fieldClass($fieldValue,$settings,$insertValue);
				else
					$field = new EmStringField($fieldValue,$settings,$insertValue);

				$fieldSaveValue = $field->saveValue();
				$valuesSet[]    = $fieldSaveValue;
			}
			$dbInsertParams['values'][] = $valuesSet;
			if (empty($dbInsertParams['columns']))
				$dbInsertParams['columns'] = array_keys($insertValue);
		}

		$result = $this->eldb->insert($dbInsertParams);

		try {
			$afterHookResult = $this->dbHooks->after('insert', $insertParams, $result);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		if (!$result)
			return ['success' => false, 'message' => 'insert_error', 'code' => 8];

		return ['success' => true, 'result' => $afterHookResult];
	}

	/**
	 * Duplicated row by id
	 * @return array
	 */
	public function duplicate($duplicateParams)
	{
		try {
			$beforeHookResult = $this->dbHooks->before('duplicate', $duplicateParams);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		if (empty($duplicateParams) || empty($duplicateParams['from']) || empty($duplicateParams['where'])) return ['success' => false, 'message' => 'duplicate_error'];

		$duplicateParams['table']   = $duplicateParams['from'];
		$duplicateParams['columns'] = [];
		$columns                    = $this->getColumns($duplicateParams['table']);
		unset($duplicateParams['from']);

		if (empty($columns)) return ['success' => false, 'message' => 'wrong_columns', 'code' => 9];

		foreach ($columns as $columnName => $column)
			if ($column['em']['settings']['code'] !== 'em_primary') $duplicateParams['columns'][] = $columnName;

		$result = $this->eldb->duplicate($duplicateParams);

		try {
			$afterHookResult = $this->dbHooks->after('duplicate', $duplicateParams, $result);
		} catch (EmException $e) {
			return ['success' => false, 'message' => $e->getMessage(), 'code' => $e->getCode()];
		}

		return ['success' => true, 'result' => $afterHookResult];
	}
}
