<?php
class TableEditor extends Phalcon\Mvc\User\Plugin
{
	var $db;
	public function __construct()
	{
		$this->db = $this->di->get('db');
	}

	/**
	 * Вставляет в нужную таблицу переданные значение
	 * предпологается что значения предварительно проверенны на обязательность и тд
	 * @var string $tableName имя таблицы
	 * @var array $values данные для вставки ['<fieldName>'=>fieldVal ... ]
	 * @return int inserted Id или false
	 */
	public function insert($tableName, $values = [], &$errors = [])
	{
		if(empty($tableName) || empty($values))
		{
			$errors[] = 'Ни одного поля не заполнено';
			return false;
		}
		$success = false;
		try
		{
			$success = $this->db->insert(
				$tableName,
				array_values($values),
				array_keys($values)
			);
		}
		catch (Exception $e)
		{
			$errors = $e->getMessage();
		}

		if($success)
			return $this->db->lastInsertId();
		else
			return false;
	}

	/**
	 * Обновляет запись, с новыми данными
	 * предпологается что значения предварительно проверенны на обязательность и тд
	 * @var string $tableName имя таблицы
	 * @var array $primaryKey ['field'=>,'value'=>]
	 * @var array $values данные для обновления ['<fieldName>'=>fieldVal ... ]
	 * @return bool
	 */
	public function update($tableName, $primaryKey, $values = [], &$errors = [])
	{
		if(empty($tableName) || empty($primaryKey) || empty($values))
		{
			$errors[] = 'Ни одного поля не заполнено';
			return false;
		}
		$success = false;
		try
		{
			$success = $this->db->update(
				$tableName,
				array_keys($values),
				array_values($values),
				$primaryKey['field']." = ".$primaryKey['value']
			);
		}
		catch (Exception $e)
		{
			$errors = $e->getMessage();
		}
		if($success)
			return true;
		else
			return false;
	}

	/**
	 * Удаляет элемент из базы
	 * @var string $tableName имя таблицы
	 * @var array $primaryKey ['field'=>,'value'=>]
	 */
	public function delete($tableName, $primaryKey, &$errors = [])
	{
		if(empty($tableName) || empty($primaryKey))
		{
			$errors[] = 'Ни одного поля не заполнено';
			return false;
		}

		$success = false;
		try
		{
			$success = $this->db->delete(
				$tableName,
				$primaryKey['field']." = ".$primaryKey['value']
			);
		}
		catch (Exception $e)
		{
			$errors = $e->getMessage();
		}
		if($success)
			return true;
		else
			return false;
	}

	/**
	 * Возврощает элемент по primaryKey и имени таблицы
	 * @var array $primaryKey ['field'=>,'value'=>]
	 * @return array или false
	 */
	public function getElement($tableName, $primaryKey = false)
	{
		if(!empty($primaryKey) && !empty($primaryKey['value']))
			return $this->db->fetchOne("SELECT * FROM {$tableName} WHERE {$primaryKey['field']} = {$primaryKey['value']}", Phalcon\Db::FETCH_ASSOC);
		else
			return false;
	}

	/**
	 * Определяет и возврощает ptimarykey таблицы
	 * @param  string $tableName имя таблицы
	 * @return string имя колонки или false
	 */
	public function getPrimaryKey($tableName)
	{
		$tableFields = $this->getTableFilelds($tableName);
		foreach($tableFields as $field)
			if($field['key'] == 'PRI')
				return $field['field'];
		return false;
	}

	/**
	 * Применяет изменения к таблице
	 * @return true если не было ошибок и false наоборот
	 */
	public function applyTableChanges($tableRealName,$tableChanges)
	{
		$noErrors = true;
		// смена названия таблицы
		if(!empty($tableChanges['name']) || isset($tableChanges['show']))
		{
			// поиск смененного названия таблицы (type = 0 - названия таблиц)
			$emName = EmNames::find([
				'conditions'=>"table = ?0 AND field = ''",
				'bind' => [$tableRealName]
			]);
			if(!count($emName))
			{
				$emName = new EmNames();
				$emName->table = $tableRealName;
				$emName->show = 0;
			}
			else
				$emName = $emName[0];

			if(!empty($tableChanges['name']))
				$emName->name = $tableChanges['name'];
			else
				$emName->name = $tableRealName;
			if(isset($tableChanges['show']))
				$emName->show = $tableChanges['show'];
			else
				$emName->show = 0;

			if(!$emName->save())
				$noErrors = false;
		}

		// смена полей таблицы
		if(!empty($tableChanges['fields']))
		{
			foreach($tableChanges['fields'] as $fieldName => $fieldArr)
			{
				$emType = EmTypes::find([
					'conditions' => 'table = ?0 and field = ?1 ',
					'bind' => [$tableRealName, $fieldName]
				]);
				if(!count($emType))
				{
					$emType = new EmTypes();
					$emType->table = $tableRealName;
					$emType->field = $fieldName;
				}
				else
					$emType = $emType[0];
				$emType->type = $fieldArr['type'];
				$emType->required = (!empty($fieldArr['required']))?1:0;
				if(!$emType->save())
					$noErrors = false;
			}
		}
		return $noErrors;
	}

	/**
	 * Проверка на измененность таблицы
	 * @var array $tableName имя таблицы в БД
	 * @var array $tableInfo ['name','fields'=>[...]]
	 * @return массив изменений ['name','fields'=>[...]] или false
	 */
	public function getTableChanges($tableName,$tableInfo)
	{
		// проверка на внесенные изменения
		$changes = [];
		if(isset($tableInfo['show']))
			$changes['show'] = intval($tableInfo['show']);

		foreach ($tableInfo['fields'] as $fieldName => $field)
			if($field['type'] != 'notsys')
				$changes['fields'][$fieldName] = $field;
		if(!empty($changes))
			return $changes;
		else
			return false;
	}


	/**
	 * Возврощает всю информацию о полях таблицы
	 * @param  string $tableName имя таблицы
	 * @return array описание полей
	 */
	public function getTableFilelds($tableName)
	{
		$fields = [];
		$columns     = $this->getTableColumns($tableName);
		$overColumns = $this->getOverTableColumns($tableName);
		$fields      = $this->getOverTable($columns,$overColumns);

		foreach ($fields as &$field)
			$field['typeInfo'] = $this->fields->getTypeInfo($field['type']);

		return $fields;
	}

	/**
	 * Возврощает списко всех полей таблицы, с их полями какого они типа и тд
	 * также достаются их переименованные значения
	 * @return pdo fetch result
	 */
	private function getTableColumns($tableName)
	{
		$tableColumns = $this->db->fetchAll(
			"SHOW COLUMNS  FROM ".$tableName,
			Phalcon\Db::FETCH_ASSOC
		);

		// достаем переопределения колонок
		$emNames = EmNames::find(['conditions'=>"table = ?0 AND field != ''",'bind'=>[$tableName]]);
		$ovverides = [];
		foreach ($emNames as $emName)
			$ovverides[$emName->field] = $emName->name;

		foreach ($tableColumns as &$col)
		{
			if(!empty($ovverides[$col['field']]))
				$col['ename'] = $ovverides[$col['field']];
			else
				$col['ename'] = '';
		}
		return $tableColumns;
	}

	/**
	 * Возврощает надстрокйки к полям таблицы
	 * @return phalcon find result
	 */
	private function getOverTableColumns($tableName)
	{
		return EmTypes::find([
			'conditions' => "table = ?0",
			'bind' => [$tableName],
		]);
	}

	/**
	 * Возврощает массив с измененными значенями типа поля и тд
	 * @var array $columns как в базе
	 * @var array $overColumns как в
	 */
	private function getOverTable($columns,$overColumns)
	{
		// поиск переопределений
		if(!count($overColumns)) return $columns;
		$resFields = [];
		foreach ($columns as $col)
		{
			$curOvCol = $col;
			foreach ($overColumns as $ovCol)
			{
				if($ovCol->field != $col['field']) continue;

				if(!empty($ovCol->settings))
					$ovCol->settings = json_decode($ovCol->settings,true);
				else
					$ovCol->settings = [];

				$curOvCol = array_merge($curOvCol,[
					'type'     =>$ovCol->type,
					'settings' =>$ovCol->settings,
					'multiple' =>$ovCol->multiple,
					'required' =>$ovCol->required
				]);
			}
			$resFields[] = $curOvCol;
		}
		return $resFields;
	}

	/**
	 * Возвращает тип поля и его настройки
	 * @param  string $tableName имя таблицы
	 * @param  string $fieldCode имя поля
	 * @return [
	 *         'type'=><string тип поля>,
	 *         'settings'=><array настройки поля>,
	 *         'required' => <int>
	 *         'multiple' => <int>
	 * ]
	 */
	public function getFieldInfo($tableName,$fieldCode)
	{
		$settingsObj = EmTypes::findFirst([
			'conditions' => "table = ?0 AND field = ?1",
			'bind' => [$tableName,$fieldCode],
		]);

		$settings = $settingsObj->toArray();
		if(!empty($settings['settings']))
			$settings['settings'] = @json_decode($settings['settings'],true);
		return $settings;
	}

	/**
	 * Возвращаес список типов полей
	 * -- проходится по директории с типами полей сибирвает info.json
	 * @return array ['code'=>[name=>'---']]
	 */
	public function getFeieldTypes()
	{
		$config = $this->di->get('config');
		$fieldsArr = [];
		if(is_dir($config->application->fldDir))
		{
			$dirs = scandir($config->application->fldDir);
			foreach ($dirs as $dirName)
			{
				$dirPage = $config->application->fldDir.$dirName;
				if(!in_array($dirName,['.','..'])  && is_dir($dirPage))
				{
					$curFieldType = ['name'=>$dirName];
					$infoJsonPath = $dirPage.'/info.json';
					// если есть файл info.json - парсим его
					if(file_exists($infoJsonPath))
					{
						$infoJsonCont = @json_decode(file_get_contents($infoJsonPath),true);
						$curFieldType['name'] = (!empty($infoJsonCont['name']))?$infoJsonCont['name']:$curFieldType['name'];
					}
					$fieldsArr[$dirName] = $curFieldType;
				}
			}
		}
		return $fieldsArr;
	}

	/**
	 * Gets table info
	 * @param  string $tableCode table name in database
	 * @return array
	 */
	public function getTableInfo($tableCode)
	{
		$tableInfo = $this->db->fetchOne("SELECT * FROM em_names WHERE em_names.table = '{$tableCode}' AND em_names.field = ''", Phalcon\Db::FETCH_ASSOC);
		if($tableInfo == false)
			return [
				'table' => $tableCode,
				'name'  => $tableCode,
				'show'  => 0
			];
		return $tableInfo;
	}

}
