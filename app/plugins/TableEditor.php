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
	 * Gets additional fields
	 * @return array
	 */
	public function getAdditionalFields($tableName)
	{
		$fields = [];
		$columns     = $this->getTableColumns($tableName);
		$overColumns = $this->getOverTableColumns($tableName);

		// поиск переопределений
		if(!count($overColumns)) return [];

		$additionalFields = [];
		foreach ($overColumns as $ovCol)
		{
			if(!empty($ovCol->settings))
				$ovCol->settings = json_decode($ovCol->settings,true);
			else
				$ovCol->settings = [];

			$hasColumn = false;
			foreach($columns as &$col)
			{
				if($ovCol->field == $col['field'])
				{
					$hasColumn = true;
					break;
				}
			}

			if($hasColumn !== false) continue;
			$additionalFields[] = $ovCol->toArray();
		}
		return $additionalFields;
	}

	/**
	 * Возврощает списко всех полей таблицы, с их полями какого они типа и тд
	 * также достаются их переименованные значения
	 * @return pdo fetch result
	 */
	private function getTableColumns($tableName)
	{
		if(empty($tableName))
			return false;
		$tableColumns = $this->db->fetchAll(
			"SHOW COLUMNS  FROM ".$tableName,
			Phalcon\Db::FETCH_ASSOC
		);

		// достаем переопределения колонок
		$emNames = EmNames::find(['conditions'=>"table = ?0 AND field != ''",'bind'=>[$tableName]]);
		$ovverides = [];
		foreach ($emNames as $emName)
			$ovverides[$emName->field] = ['ename'=>$emName->name, 'tab'=>$emName->tab];

		foreach ($tableColumns as &$col)
		{
			if(!empty($ovverides[$col['field']]))
			{
				$col['ename'] = $ovverides[$col['field']]['ename'];
				$col['tab']   = $ovverides[$col['field']]['tab'];
			}
			else
			{
				$col['tab']   = false;
				$col['ename'] = '';
			}
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
	 * @var array $overColumns как в настроках
	 */
	private function getOverTable($columns,$overColumns)
	{
		// поиск переопределений
		if(!count($overColumns)) return $columns;

		foreach ($overColumns as $ovCol)
		{
			if(!empty($ovCol->settings))
				$ovCol->settings = json_decode($ovCol->settings,true);
			else
				$ovCol->settings = [];

			$hasColumn = false;
			foreach($columns as &$col)
			{
				if($ovCol->field != $col['field']) continue;
				$hasColumn = true;
				$col = array_merge($col,[
					'type'     =>$ovCol->type,
					'settings' =>$ovCol->settings,
					'multiple' =>$ovCol->multiple,
					'required' =>$ovCol->required,
					'hidden'   =>$ovCol->hidden,
				]);
			}
		}
		return $columns;
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
		{
			$table = $this->db->fetchOne("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '{$tableCode}'",Phalcon\Db::FETCH_ASSOC);
			if($table === false) return false;
			return [
				'table' => $tableCode,
				'name'  => $tableCode,
				'show'  => 0
			];
		}
		return $tableInfo;
	}

	public function getSettingsUrl($tableCode)
	{
		$config  = $this->di->get('config');
		$baseUri = $config->application->baseUri;
		return "{$baseUri}settings/table/{$tableCode}/";
	}

	/**
	 * Gets table url
	 * Check default view
	 * @return string
	 */
	public function getUrl($tableCode,$params = [])
	{
		$config = $this->di->get('config');
		// if request uri has table code and page number
		// we try to save them
		$defaultParams = [];
		// delete base uri from beginnig of request uri
		if(!empty($_SERVER['HTTP_REFERER']))
		{
			$url = parse_url($_SERVER['HTTP_REFERER']);
			$url = $url['path'];
		}
		else
			$url = '/';
		if (substr($url, 0, strlen($config->application->baseUri)) == $config->application->baseUri)
		    $url = substr($url, strlen($config->application->baseUri));

		if(strpos($url, "table/{$tableCode}") !== false)
		{
			$url = str_replace("table/{$tableCode}/", '', $url);
			$url = explode('/',$url);
			$url = array_chunk($url, 2);
			foreach ($url as $urlPart)
				if(count($urlPart) == 2)
					$defaultParams[$urlPart[0]] = $urlPart[1];
		}

		// set default view
		if(empty($defaultParams['view']))
		{
			$tableViews = EmViews::find([
				'conditions' => 'table = ?0 AND default = 1',
				'bind'       => [$tableCode]
			]);
			if(count($tableViews))
				$defaultParams['view'] = $tableViews->getFirst()->id;
		}

		$params = array_merge($defaultParams,$params);
		$urlParams = [];
		foreach ($params as $paramCode => &$paramValue)
			if(!empty($paramValue))
				$urlParams[] = "{$paramCode}/$paramValue";

		$urlParams = implode('/',$urlParams);
		$urlParams = (!empty($urlParams))?"{$urlParams}/":'';
		return "{$config->application->baseUri}table/{$tableCode}/{$urlParams}";
	}

	/**
	 * Gets element detail page  url
	 * @param  string $tableName
	 * @param  array $elementRow
	 * @return string
	 */
	public function getElementUrl($tableName,$elementRow)
	{
		$config     = $this->di->get('config');
		$primaryKey = $this->getPrimaryKey($tableName);
		$id         = $elementRow[$primaryKey];
		$baseUri    = $config->application->baseUri;
		return "{$baseUri}table/{$tableName}/edit/{$id}";
	}

	/**
	 * Returns add url
	 * @param  string $tableName
	 * @return strign
	 */
	public function getAddUrl($tableName)
	{
		$config     = $this->di->get('config');
		$baseUri    = $config->application->baseUri;
		return "{$baseUri}table/{$tableName}/add/";
	}
}
