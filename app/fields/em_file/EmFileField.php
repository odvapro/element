<?php

class EmFileField extends FieldBase
{
	public $EditFieldPath = 'em_file/views/field';
	public $ValueFieldPath = 'em_file/views/value';
	public function getSettings($settings, array $params)
	{
		$settingFields['savePath'] = (!empty($settings['savePath']))?$settings['savePath']:$this->tableEditor->getDefaultFilesSavePath();
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
		foreach ($oldFiles as $oldFile)
			if(!in_array($oldFile, $newFiles))
				$this->deleteFile($oldFile,$fieldArray['settings']);

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
			{
				foreach($settigs['imageSizes'] as $imageSize)
				{
					$image = new Image();
					$image->createFromFile($file->getTempName());
					$img_inf = $image->getInfos();
					if(!empty($imageSize['fixed']) && $imageSize['fixed'] == 1)
					{
						$k = $imageSize['width']/$img_inf['width'];
						$new_height = $img_inf['height'] * $k;

						// считаем высоту с измененной шириной
						if($new_height <= $imageSize['height'])
							$image->resize(0,$imageSize['height']);
						else
							$image->resize($imageSize['width'],0);
						$image->crop(0,0, $imageSize['width'],$imageSize['height']);
					}
					else
					{
						/*РАСЧЕТ СТОРОН*/
						// и ширина и высота больше заданных
							// уменьшаем по большей стороне на столько чтобы меньшая тоже вошла в рамки
						// только ширина больше
							// уменьшаем только ширину
						// только высота больше
							// уменьшаем только высоту
						$needWidth = $img_inf['width'];
						$needHeight = $img_inf['height'];
						$imageSize['width'] = ($imageSize['width'] == '*')?0:$imageSize['width'];
						$imageSize['height'] = ($imageSize['height'] == '*')?0:$imageSize['height'];
						if($img_inf['width'] > $imageSize['width'] && $img_inf['height'] > $imageSize['height'])
						{
							if($img_inf['width'] > $img_inf['height'])
							{
								$needHeight = $img_inf['height']*$needWidth/$img_inf['width'];
								if($needHeight > $img_inf['height'])
								{
									$needHeight = $img_inf['height'];
									$needWidth = 0;
								}
								else
									$needHeight = 0;
							}
							else
							{
								$needWidth = $img_inf['width']*$needHeight/$img_inf['height'];
								if($needWidth > $img_inf['width'])
								{
									$needWidth = $img_inf['width'];
									$needHeight = 0;
								}
								else
									$needWidth = 0;
							}
						}
						elseif($img_inf['width'] > $imageSize['width'])
							$needHeight = 0;
						elseif($img_inf['height'] > $imageSize['height'])
							$needWidth = 0;

						$image->resize($needWidth,$needHeight);
					}

					$publicPath = $tmpPath.$imageSize['name'].'_'.$newName.'.jpg';
					$image->save(ROOT.$publicPath);
					$fileArr['sizes'][$imageSize['name']] = $publicPath;
				}
			}
		}
		else
			$fileArr['type'] = 'file';
		
		if( ($fileArr['type'] == 'image' &&  $settigs['saveOriginalImage'] == 1) || $fileArr['type'] != 'image' )
		{
			if(is_uploaded_file($file->getTempName()))
				$file->moveTo(ROOT.$tmpPath.'o_'.$newName.'.'.$ext);
			else
			{
				rename($file->getTempName(), ROOT.$tmpPath.'o_'.$newName.'.'.$ext);
			}
			$fileArr['path'] = $tmpPath.'o_'.$newName.'.'.$ext;
		}
		else
			$fileArr['path'] = false;

		return $fileArr;
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

		$imgRawData = @file_get_contents($url);
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