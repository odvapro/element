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
		$this->eldb = $eldb;
		$this->di = $di;
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
				$fieldDirPath = $config->application->fldDir . $fieldCode;
				$infoFilePath = $fieldDirPath . '/info.json';

				if(file_exists($infoFilePath))
				{
					$emTypes[$fieldCode]                   = json_decode(file_get_contents($infoFilePath), true);
					$emTypes[$fieldCode]['code']           = $fieldCode;

					$fieldComponent                        = $emTypes[$fieldCode]['code'];
					$fieldComponent                        = explode('_', $fieldComponent);
					$fieldComponent                        = array_map('ucfirst', $fieldComponent);
					$fieldComponent[]                      = 'Field';
					$fieldComponent                        = implode('', $fieldComponent);
					$emTypes[$fieldCode]['fieldComponent'] = $fieldComponent;
				}

				if(strpos($fieldCode, '.') === false && is_dir($fieldDirPath))
					$loader->registerDirs([$fieldDirPath], true)->register();
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
		$emTypes      = $this->getEmTypes();

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
			if(!array_key_exists($emFieldsTypes->field, $tableColumns))
				#TODO добавление доп полей которых нет в бд и тд
				continue;

			$emFieldArray = [
				'name'      => $emFieldsTypes->name,
				'type'      => $emFieldsTypes->type,
				'type_info' => $emTypes[$emFieldsTypes->type],
				'settings'  => $emFieldsTypes->settings,
				'required'  => $emFieldsTypes->getRequired()
			];
			$tableColumns[$emFieldsTypes->field]['em'] = $emFieldArray;
		}

		foreach ($tableColumns as &$tableColumn)
		{
			if(array_key_exists('em', $tableColumn))
				continue;

			$defaultType = ($tableColumn['key'] == 'PRI')?$emTypes['em_primary']:$emTypes['em_string'];
			$emFieldArray = [
				'name'      => '',
				'type'      => $tableColumn['type'],
				'type_info' => $defaultType,
				'settings'  => [],
				'required'  => false,
			];
			$tableColumn['em'] = $emFieldArray;
		}

		return $tableColumns;
	}

	/**
	 * Достает код ключа
	 * @param  string $tableName table code
	 * @return string primary key code
	 */
	public function getPrimaryKeyCode($tableName)
	{
		$columns = $this->eldb->getColumns($tableName);
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
					$field = new $fieldClass($columnValue,$settings);
				else
					$field = new EmStringField($columnValue,$settings);

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
				$field = new $fieldClass($fieldValue,$settings);
			else
				$field = new EmStringField($fieldValue,$settings);

			$fieldSaveValue = $field->saveValue();
			$set[]          = "{$fieldCode} = '{$fieldSaveValue}'";
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
				$field = new $fieldClass($fieldValue,$settings);
			else
				$field = new EmStringField($fieldValue,$settings);

			$fieldSaveValue = $field->saveValue();
			$valuesSet[]    = $fieldSaveValue;
		}
		$insertParams['values'] = $valuesSet;

		return $this->eldb->insert($insertParams);
	}
}