<?php

class EmFileField extends FieldBase
{
	public $EditFieldPath = 'em_file/views/field';
	public $ValueFieldPath = 'em_file/views/value';
	public function getSettings($settings, array $params)
	{
		$settingFields['savePath'] = (!empty($settings['savePath']))?$settings['savePath']:$this->getDefaultFilesSavePath();
		$settingFields['fileTypes'] = (!empty($settings['fileTypes']))?$settings['fileTypes']:[];
		
		// определяем доп переменную для типов файлов
		$this->view->setVar('fileTypes',['jpeg','png','gif','bmp','pdf','doc']);
		
		if(!empty($params['settingFields']))
			$settingFields = array_merge($params['settingFields'],$settingFields);
		$this->view->setVar('settingFields',$settingFields);
		
		// обязательый параметр
		$this->view->setVar('formPath','em_file/views/settingsForm');
	}

	public function getValue($fieldValue,$settings,$table = false)
	{
		if(empty($fieldValue)){ return ''; }
		if($table)
			return json_decode($fieldValue,true);
		else
		{
			$resFilesArray = [];
			$filesArr = json_decode($fieldValue,true);
			if($filesArr)
			{
				foreach ($filesArr as $key => $fileArr)
				{
					$resFilesArray[] = array_merge($fileArr,[
						'jsonFileObj' => htmlspecialchars(json_encode($fileArr),ENT_QUOTES),
						'index'       => $key
					]);
				}
			}
			elseif(file_exists(ROOT.$fieldValue))
			{
				$fileArr = ['upName'=>'untittled','type'=>'file','path' => $fieldValue];
				$resFilesArray[] = array_merge($fileArr,[
					'jsonFileObj' => htmlspecialchars(json_encode($fileArr),ENT_QUOTES),
					'index'       => 0
				]);
			}
			return $resFilesArray;
		}
	}

	public function saveValue($fieldValue,$fieldArray,$tableName = false, $primaryKey = false)
	{
		$newFiles = [];
		foreach ($fieldValue as $file)
		{
			if(!empty($file['tmp']))
				// новый файл
				// переносим его в основную папку
				$newFiles[] = $this->saveFile($file,$fieldArray['settings']);
			else
				// файл уже был загружен
				// оставляем как есть
				$newFiles[] = json_decode($file['jsonFileObj'],true);
		}

		// достаем текущее состояние файлов
		// определяем разницу и удаляем лишние файлы
		$elementArr = $this->tableEditor->getElement($tableName,$primaryKey);
		$oldFiles   = json_decode($elementArr[$fieldArray['field']],true);
		if(is_array($oldFiles))
		{
			foreach ($oldFiles as $oldFile)
				if(!in_array($oldFile, $newFiles))
					$this->deleteFile($oldFile,$fieldArray['settings']);
		}

		return json_encode($newFiles);
	}

	public function prolog($settings,$table = false)
	{
		$this->assets->addJs('fields/em_file/src/js/init.js');
	}

	////////////////////////////////////////////////////////////
	/// Функции вне абстрактоного класса
	////////////////////////////////////////////////////////////
	
	/**
	 * Переносит файл из tmp 
	 * @param  json $fileObj
	 * @param  array $settings
	 * @return array
	 */
	public function saveFile($fileObj,$settings)
	{
		$fileArr      = json_decode($fileObj['jsonFileObj'],true);
		$savePath     = $this->getSavePath($settings);
		$fullFilePath = ROOT.$fileArr['path'];
		$newName      = false;
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
			if(!empty($settings['imageSizes']))
			{
				foreach ($settings['imageSizes'] as $imageSize)
				{
					if(empty($fileArr['sizes'][$imageSize['name']])) continue;
					$fullFilePath = ROOT.$fileArr['sizes'][$imageSize['name']];
					$path_parts   = pathinfo($fullFilePath);
					$newName      = $savePath.$path_parts['basename'];
					if(file_exists($fullFilePath))
					{
						rename($fullFilePath, ROOT.$newName);
						$sizes[$imageSize['name']] = $newName;							
					}
				}
			}
			$fileArr['sizes'] = $sizes;
		}

		return $fileArr;
	}

	/**
	 * Физиеское удалеие файла
	 * @param  array $fileObj 
	 * @param  array $settings
	 */
	public function deleteFile($fileObj,$settings)
	{
		@unlink(ROOT.$fileObj['path']);
		if(!empty($fileObj['sizes']))
			foreach($fileObj['sizes'] as $fileSizePath)
				@unlink(ROOT.$fileSizePath);
		return true;
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
		if(!empty($settigs['savePath']))
			$settigs['savePath'] = '/'.ltrim($settigs['savePath'],'/');
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
		return '/'.ltrim($config->application->baseUri.$config->application->defaultFilesUploadPath,'/');
	}

	/**
	 * Загружает файл во временную папку
	 * если этот файл картинка, то создает два три размера маленький средний и оригинал
	 * 50X50 600*600  
	 * @var object file  Phalcon\Http\Request\File  
	 * @var array settings из базы, настройки поля
	 * @return array [upName,path,type,<sizes(small,medium)>]
	 */
	private function _uploadFileToTemp($file, $settigs)
	{
		$tmpPath = $this->getSavePath($settigs,true);

		// новое имя для файла
		$newName     = uniqid('el');
		$fileName    = $file->getName();
		$fileNameArr = explode('.',$fileName);
		$ext         = end($fileNameArr);

		// если файл картина то обрабатывается по другому
		$fileArr           = [];
		$fileArr['upName'] = $fileName;
		$fileArr['type']   = 'file';
		if(@getimagesize($file->getTempName()))
		{
			$fileArr['type'] = 'image';
			// это картинка
			// обрботка по настойкам копий картинок
			$fileArr['sizes'] = [];
			if(!empty($settigs['imageSizes']))
				foreach($settigs['imageSizes'] as $imageSize)
				{
					$publicPath = $this->_resizeImage($file->getTempName(),$imageSize,$newName,$tmpPath);
					if($publicPath !== false)
						$fileArr['sizes'][$imageSize['name']] = $publicPath;
				}
		}
		else
			$fileArr['type'] = 'file';
		
		if(is_uploaded_file($file->getTempName()))
			$file->moveTo(ROOT.$tmpPath.'o_'.$newName.'.'.$ext);
		else
			rename($file->getTempName(), ROOT.$tmpPath.'o_'.$newName.'.'.$ext);
		
		$fileArr['path'] = "{$tmpPath}o_{$newName}.{$ext}";

		return $fileArr;
	}

	/**
	 * Ресайз картинки 
	 * @param  string $imagePath путь исходной картинки
	 * @param  array $imageSize массив [width,height,name]
	 * @param  string $newName   новое имя файла
	 * @param  string $savePath  путь сохранения файла
	 * @return string / boolean
	 */
	public function _resizeImage($imagePath,$imageSize,$newName,$savePath)
	{
		if(empty($imageSize['width']) || empty($imageSize['height']))
			return false;
		$image = new Image($imagePath);
		$image->crop($imageSize['width'], $imageSize['height']);

		$publicPath = "{$savePath}{$imageSize['name']}_{$newName}.jpg";
		$image->save(ROOT.$publicPath);
		return $publicPath;
	}

	/**
	 * Добавлает файл по URL в массив $_FILES
	 * @var $key  ключ по которому записывается файл  
	 * @var $url  url из которого берется файл
	 */
	public function addToFiles($key, $url)
	{
		$tempName = tempnam('/tmp', 'php');
		$originalName = basename(parse_url($url, PHP_URL_PATH));

		$imgRawData = file_get_contents($url);
		file_put_contents($tempName, $imgRawData);
		$_FILES[$key] = array(
			'name'     => $originalName,
			'type'     => mime_content_type($tempName),
			'tmp_name' => $tempName,
			'error'    => 0,
			'size'     => strlen($imgRawData),
		);
	}

	/**
	 * Проверяет переданный фал на соответсвия разрешенным типам
	 * @var object file  Phalcon\Http\Request\File  
	 * @var array fileTypes типы файлов
	 * @return bool 
	 */
	private function _checkFileTypes($file, $fileTypes = [])
	{
		$valid = false;
		if(!empty($fileTypes))
			foreach($fileTypes as $fileType)
			{
				if(strpos($file->getType(), $fileType) !== false)
				{
					$valid = true;
					break;
				}
			}
		else
			// если не указано ни одного типа, загрюжается все файлы
			$valid = true;
		return $valid; 
	}

	/**
	 * Загружает файлы которые были посланы обычным способом через поле типа файл
	 * @var  fieldDesc описание типа поля в которое записывается файл
	 * @return array
	 */
	public function uploadFiles($fieldDesc)
	{
		if($this->request->hasFiles() == true)
		{
			$settings = (!empty($fieldDesc->settings))?json_decode($fieldDesc->settings,true):[];
			$fileTypes = [];
			if(!empty($settings['fileTypes']))
				$fileTypes = $settings['fileTypes'];
        	
        	// подготовка массива файлов/файла для записи в БД
        	$globValid = true;
        	$fileForDb = [];
        	foreach ($this->request->getUploadedFiles() as $file)
			{
			    // проверка поля на соответсвие типам
			    if($this->_checkFileTypes($file,$fileTypes))
				    $fileForDb[] = $this->_uploadFileToTemp($file,$settings);
			    else
			    	$globValid = false;

				// если поле не множественное , то разрешается загрузить только один файл
			    if($fieldDesc->multiple == 0) break;
			}

			if($globValid)
			{
				// результат массив загруженных файлов 
				// во временную папку 
				if(!empty($fileForDb))
					return ['result'=>'success','files'=>$fileForDb];
			}
			else
				return ['result'=>'error','msg'=>'такого типа файлы запрещены'];
		}
		else
			return ['result'=>'error','msg'=>'нет файлов'];
	}
}