<?php
class TableEditor extends Phalcon\Mvc\User\Plugin
{
	/**
	 * Системые типы полей (к ним можно преобразовать поля)
	 */
	public $systemEmTypes = 
	[
		'em_int',
		'em_string',
		'em_text',
		'em_date',
		'em_datetime',
		'em_file',
		'em_node',
		'em_bool'
	];

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
		$db = $this->di->get('db');
		
		$success = false;
		try
		{
			$success = $db->insert(
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
			return $db->lastInsertId();
		else
			return false;
	}

	/**
	 * Обновляет запись, с новыми данными
	 * предпологается что значения предварительно проверенны на обязательность и тд
	 * @var string $tableName имя таблицы
	 * @var string $primaryKey имя таблицы
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
		$db = $this->di->get('db');
		
		$success = false;
		try
		{
			$success = $db->update(
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
	 */
	public function delete($tableName, $primaryKey, &$errors)
	{
		if(empty($tableName) || empty($primaryKey))
		{
			$errors[] = 'Ни одного поля не заполнено';
			return false;
		}

		$db = $this->di->get('db');
		$success = false;
		try
		{
			//TODO удаление файлов если они имеются

			$success = $db->delete(
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
	 * @return array или false
	 */
	public function getElement($tableName, $primaryKey = false)
	{
		if(!empty($primaryKey))
		{
			$db = $this->di->get('db');
			return $db->fetchOne("SELECT * FROM ".$tableName." WHERE ".$primaryKey['field']." = ".$primaryKey['value'], Phalcon\Db::FETCH_ASSOC);
		}
		else
			return false;
	}

	/**
	 * Удаление старых файлов перед обновлением или удалением элемента
	 */
	public function deleteOldFiles($elementArr, $formData, $fieldsDesc )
	{
		if(!empty($elementArr) && !empty($fieldsDesc))
		{
			// проверям поля только типа файл
			foreach ($fieldsDesc as $fieldDesc)
			{
				if($fieldDesc['type'] == 'em_file' && !empty($elementArr[$fieldDesc['field']]))
				{
					$oldFiles = json_decode($elementArr[$fieldDesc['field']],true);
					$newFiles = (!empty($formData[$fieldDesc['field']]))?json_decode($formData[$fieldDesc['field']],true):[];
					
					// определение разныцы между массиввми файлов
					$diff = [];
					foreach ($oldFiles as $oldFile)
					{
						$notDeleted = false;
						foreach ($newFiles as $newFile)
						{
							if($newFile == $oldFile)
							{
								$notDeleted = true;
								break;
							}
						}
						if(!$notDeleted)
							$diff[] = $oldFile;
					}

					// удаление вывленной разницы
					foreach ($diff as $file)
					{
						if($file['type'] == 'image')
						{
							@unlink(ROOT.$file['sizes']['small']);
							@unlink(ROOT.$file['sizes']['medium']);
						}
						@unlink(ROOT.$file['path']);
					}
				}
			}
		}
		else
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
		if(!empty($tableChanges['name']))
		{
			// поиск смененного названия таблицы (type = 0 - названия таблиц)
			$emName = EmNames::find([
				'conditions'=>'table = ?0 AND type = 0',
				'bind' => [$tableRealName]
			]);
			if(!count($emName))
			{
				$emName = new EmNames();
				$emName->table = $tableRealName;
				$emName->type = 0;
			}
			else
				$emName = $emName[0];
			$emName->name = $tableChanges['name'];
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
				$emType->multiple = (!empty($fieldArr['multiple']))?1:0;
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
		if($tableName != $tableInfo['name'])
			$changes['name'] = $tableInfo['name'];
	
		foreach ($tableInfo['fields'] as $fieldName => $field)
			if($field['type'] != 'notsys')
				$changes['fields'][$fieldName] = $field;

		if(!empty($changes))
			return $changes;
		else
			return false;
	}

	/**
	 * Возврощает списко всех полей таблицы, с их полями какого они типа и тд
	 * @return pdo fetch result
	 */
	public function getTableColumns($tableName)
	{
		$db = $this->di->get('db');
		$tableColumns = $db->fetchAll(
			"SHOW COLUMNS  FROM ".$tableName,
			Phalcon\Db::FETCH_ASSOC
		);
		return $tableColumns;
	}

	/**
	 * Возврощает надстрокйки к полям таблицы
	 * @return phalcon find result
	 */
	public function getOverTableColumns($tableName)
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
	public function getOverTable($columns,$overColumns)
	{
		$resFields = [];
		foreach ($columns as $col)
		{
			$curOvCol = $col;
			// поиск переопределений
			if(count($overColumns))
			{
				foreach ($overColumns as $ovCol)
				{
					if($ovCol->field == $col['field'])
					{
						if(!empty($ovCol->settings))
							$ovCol->settings = json_decode($ovCol->settings,true);
						
						$curOvCol = array_merge($curOvCol,[
							'type'     =>$ovCol->type,
							'settings' =>$ovCol->settings,
							'multiple' =>$ovCol->multiple,
							'required' =>$ovCol->required
						]);
					}
				}
			}
			$resFields[] = $curOvCol;
		}
		return $resFields;
	}

	/**
	 * Проверяет переданный фал на соответсвия разрешенным типам
	 * @var object file  Phalcon\Http\Request\File  
	 * @var array fileTypes типы файлов
	 * @return bool 
	 */
	public function checkFileTypes($file, $fileTypes = [])
	{
		$valid = false;
		if(!empty($fileTypes))
		{
			foreach($fileTypes as $fileType)
			{
				if(strpos($file->getType(), $fileType) !== false)
				{
					$valid = true;
					break;
				}
			}
		}
		else
			// если не указано ни одного типа, загрюжается все файлы
			$valid = true;
		return $valid; 
	}

	/**
	 * Загружает файл во временную папку
	 * если этот файл картинка, то создает два три размера маленький средний и оригинал
	 * 50X50 600*600  
	 * @var object file  Phalcon\Http\Request\File  
	 * @var array settings из базы, настройки поля
	 * @return array [upName,path,type,<sizes(small,medium)>]
	 */
	public function uploadFileToTemp($file, $settigs)
	{
		$tmpPath = $this->getSavePath($settigs,true);

		// новое имя для файла
		$newName     = uniqid('el');
		$fileName    = $file->getName();
		$fileNameArr = explode('.',$fileName);
		$ext         = end($fileNameArr);

		// если файл картина то обрабатывается по другому
		$fileArr = [];
		$fileArr['upName'] = $fileName;
		if(@getimagesize($file->getTempName()))
		{
			$fileArr['type'] = 'image';
			// это картинка
			$image = new Image();
			$image->createFromFile($file->getTempName());
			$img_inf = $image->getInfos();
			if($img_inf['width'] > $img_inf['height'])
			{
				$image->resize(0,50);
				$image->crop(0,0, 50,50);
				$image->save(ROOT.$tmpPath.'s_'.$newName.'.jpg');

				$image->resize(0,600);
				$image->crop(0,0, 600,600);
				$image->save(ROOT.$tmpPath.'m_'.$newName.'.jpg');
			}
			else
			{
				$image->resize(50,0);
				$image->crop(0,0, 50,50);
				$image->save(ROOT.$tmpPath.'s_'.$newName.'.jpg');

				$image->resize(600,0);
				$image->crop(0,0, 600,600);
				$image->save(ROOT.$tmpPath.'m_'.$newName.'.jpg');
			}
			$fileArr['sizes'] = 
			[
				'small' => $tmpPath.'s_'.$newName.'.jpg',
				'medium' => $tmpPath.'m_'.$newName.'.jpg'
			];
		}
		else
			$fileArr['type'] = 'file';
		$file->moveTo(ROOT.$tmpPath.'o_'.$newName.'.'.$ext);
		$fileArr['path'] = $tmpPath.'o_'.$newName.'.'.$ext;

		return $fileArr;
	}

	/**
	 * Обробатывает загруженные файлы для записи в БД
	 * проверяет удаленные файлы, и удаляет их физически
	 * @var array $uploadedFiles
	 * @return string json объект файлов для записи в  
	 */
	public function handleUploadedFiles($uploadedFiles, $fieldArr)
	{
		$resFiles = [];
		$settings = (!empty($fieldArr['settings']))?$fieldArr['settings']:[];
		foreach ($uploadedFiles as $key => $file)
		{
			// загружен во временную папку
			if(!empty($file['tmp']))
			{
				$fileArr         = json_decode($file['jsonFileObj'],true);
				$savePath        = $this->getSavePath($settings);

				$fullFilePath    = ROOT.$fileArr['path'];
				$newName = false;
				if(is_file($fullFilePath))
				{
					$path_parts = pathinfo($fullFilePath);
					$newName = $savePath.$path_parts['basename'];
					rename($fullFilePath, ROOT.$newName);
				}
				$fileArr['path'] = $newName;

				// если это картинка, то нужно перенести еще и доп размеры
				if($fileArr['type'] == 'image')
				{
					$sizes = [];
					
					// small
					$fullFilePath = ROOT.$fileArr['sizes']['small'];
					$path_parts = pathinfo($fullFilePath);
					$newName = $savePath.$path_parts['basename'];
					rename($fullFilePath, ROOT.$newName);
					$sizes['small'] = $newName;

					// medium
					$fullFilePath = ROOT.$fileArr['sizes']['medium'];
					$path_parts = pathinfo($fullFilePath);
					$newName = $savePath.$path_parts['basename'];
					rename($fullFilePath, ROOT.$newName);
					$sizes['medium'] = $newName;

					$fileArr['sizes'] = $sizes;
				}
				$resFiles[] = $fileArr;
			}
			else
				$resFiles[] = json_decode($file['jsonFileObj'],true);
		}

		// достает текущее значение поля
		// проверяет разницу
		// если она есть, удаляет удаленные файлы 

		return json_encode($resFiles);
	}
	
	/**
	 * путь сохранения относительно  корня и настроек
	 * если такой папки нет то она создается
	 * @var array $settings настройки поля 
	 * @var bool $settings вернуть путь к временной папке или к боевой 
	 * @return  string
	 */
	public function getSavePath($settigs, $tmp = false)
	{
		$settigs['savePath'] = ltrim($settigs['savePath'],'/');
		if(!empty($settigs['savePath']) && is_dir(ROOT.$settigs['savePath']))
			$savePath = $settigs['savePath'];
		else
			$savePath = $this->getDefaultFilesSavePath();

		if($tmp)
		{
			// в пути для сохранения создаем папку для временных файлов
			$savePath = $savePath.'tmp/';
			if(!is_dir(ROOT.$savePath))
				@mkdir(ROOT.$savePath,0755,true);
		}
		else
		{
			// добавляем код дня, чтобы все не скалыдивалось в одну папку
			$savePath = $savePath.date('Ymd').'/';
			if(!is_dir(ROOT.$savePath))
				@mkdir(ROOT.$savePath,0755,true);
		}
		return $savePath;
	}

	/**
	 * @return string путь по умолчанию для сохранения файлов, относительно DOCUMENT_ROOT 
	 */
	public function getDefaultFilesSavePath()
	{
		$config = $this->di->get('config');
		return ltrim($config->application->baseUri.$config->application->defaultFilesUploadPath,'/');
	}

}

?>