<?php

class EmFileField extends FieldBase
{
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		$domain   = $this->di->get('config')->application->domain;
		$resArray = json_decode($this->fieldValue, true);

		if(empty($resArray)) return false;

		foreach ($resArray as &$image)
		{
			$image['localPath'] = $image['path'];
			$image['path']      = $domain.$image['path'];

			if(!isset($image['sizes']))
				continue;

			foreach ($image['sizes'] as &$imageSize)
				$imageSize = [
					'path'      => $domain.$imageSize,
					'localPath' => $imageSize,
				];
		}

		return $resArray;
	}

	/**
	 * Установить новое значение
	 * @param Json $newValue Новое значение поля
	 */
	public function setValue($newValue)
	{
		$this->fieldValue = $newValue;
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		if(!is_array($this->fieldValue))
			return ($this->fieldValue == 'false') ? '' : $this->fieldValue;

		if(empty($this->fieldValue))
			return '';


		foreach($this->fieldValue as $fileKey => &$file)
		{
			if(isset($file['delete']))
			{
				$this->deleteFile($file);
				unset($this->fieldValue[$fileKey]);
				continue;
			}
			if(!isset($file['new']))
				continue;

			$file = $this->addFile($file);

			if($file)
				continue;

			unset($this->fieldValue[$fileKey]);
		}

		$this->fieldValue = array_values($this->fieldValue);

		foreach($this->fieldValue as &$image)
		{
			$image['path'] = $image['localPath'];
			unset($image['localPath']);

			if($image['type'] == 'file')
				continue;

			foreach ($image['sizes'] as &$imageSize)
				$imageSize = $imageSize['localPath'];
		}

		return json_encode($this->fieldValue);
	}

	/**
	 * Получить настройки поля
	 * @return Array Настройки поля
	 */
	public function getSettings()
	{
		$settings             = parent::getSettings();
		$settings['rootPath'] = realpath(ROOT . '/../') . '/';

		return $settings;
	}

	/**
	 * Добавить файл
	 * @param  Array  $fileInfo информация о файле
	 * @return boolean          Успех добавления
	 */
	protected function addFile(Array $fileInfo)
	{
		if(!isset($this->settings['savePath']))
			return false;

		$documentRoot = ROOT . '/..';
		$filePath     = $documentRoot . $fileInfo['localPath'];

		if(!file_exists($filePath))
			return false;

		$file = FileHelper::getFileByPath($filePath);

		if(!$file)
			return false;

		$extension = FileHelper::getExtensionByMimeType($file->getRealType());

		if(!$extension)
			return false;

		// Формируем новое имя файла и путь по которому его сохранить
		$fileName     = hash('md5', $fileInfo['upName'] . date('Y.m.d H:i:s') . uniqid());
		$fullFileName = $fileName . $extension;
		$localPath    = '/' . trim($this->settings['savePath'], '/') . '/';
		$fullPath     = $documentRoot . $localPath . $fullFileName;
		$error        = !rename($filePath, $fullPath);

		if($error)
			return false;

		$result = [
			'upName'    => $fullFileName,
			'type'      => $fileInfo['type'],
			'localPath' => $localPath . $fullFileName,
			'path'      => $localPath . $fullFileName
		];

		if($fileInfo['type'] == 'file')
			return $result;

		// Ресайз картинок
		foreach($fileInfo['sizes'] as $size)
		{
			$sizeFilePath = $documentRoot . $size['localPath'];
			unlink($sizeFilePath);
		}

		$sizes           = $this->resizeImage($fullPath);
		$result['sizes'] = $sizes;

		return $result;
	}

	/**
	 * Сделать ресайз картинки по настройкам
	 * @param  String $imagePath Полный путь до файла
	 * @return Array             Массив размеров изображения
	 */
	private function resizeImage(String $imagePath)
	{
		$sizes        = [];
		$fileName     = basename($imagePath);
		$documentRoot = ROOT . '/..';
		$localPath    = '/' . trim($this->settings['savePath'], '/') . '/';
		$saveFolder   = $documentRoot . $localPath;

		foreach($this->getResolutions() as $size)
		{
			$path = FileHelper::resize($imagePath, $size, $saveFolder, $fileName);

			if(!$path)
				continue;

			$sizes[$size['name']] = [
				'localPath' => $localPath . basename($path)
			];
		}

		return $sizes;
	}

	/**
	 * Удалить файл
	 * @param  Array  $fileInfo информация о файле
	 * @return boolean          Успех удаления
	 */
	private function deleteFile(Array $fileInfo)
	{
		$documentRoot = ROOT . '/..';
		$filePath     = $documentRoot . $fileInfo['localPath'];

		if(!file_exists($filePath))
			return false;

		unlink($filePath);

		if(!isset($fileInfo['sizes']))
			return true;

		foreach($fileInfo['sizes'] as $size)
			if(file_exists($documentRoot . $size['localPath']))
				unlink($documentRoot . $size['localPath']);

		return true;
	}

	/**
	 * Получить разрешения для картинок
	 * @return array Массив разрешений
	 */
	public function getResolutions()
	{
		$imageSizes = ['small' => ['width' => 50, 'height' => 50, 'name' => 'small']];

		if(!empty($this->settings['resolutions']) && !is_array($this->settings['resolutions']))
			return $imageSizes;

		foreach($this->settings['resolutions'] as $resolution)
		{
			$imageSizes[$resolution['code']] = [
				'width'  => (intval($resolution['width']) <= 0) ? 'auto' : intval($resolution['width']),
				'height' => (intval($resolution['height']) <= 0) ? 'auto' : intval($resolution['height']),
				'name'   => $resolution['code']
			];
		}

		return $imageSizes;
	}
}