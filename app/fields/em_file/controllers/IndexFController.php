<?php

include ROOT . '/app/library/Image.php';

class IndexFController extends ControllerBase
{
	/**
	 * check path for save images
	 * @return json
	 */
	public function checkPathAction()
	{
		$path = $this->request->getPost('path');

		if(empty($path))
			return $this->jsonResult(['success' => false, 'message' => 'wrong params']);

		$fullPath = ROOT . '/../' . $path;

		if(!is_dir($fullPath))
			return $this->jsonResult(['success' => false, 'message' => 'directory not found']);

		if(!is_writable($fullPath))
			return $this->jsonResult(['success' => false, 'message' => 'directory can\'t be written']);

		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Загрузить файл на сервер
	 * @return json
	 */
	public function uploadAction()
	{
		$fieldCode       = $this->request->getPost('fieldCode');
		$tableCode       = $this->request->getPost('tableCode');
		$typeUpload      = $this->request->getPost('typeUpload');
		$link            = $this->request->getPost('link');
		$prepareForSave  = $this->request->getPost('prepareForSave');

		if(empty($fieldCode) ||
		   empty($tableCode) ||
		   empty($typeUpload)
		)
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

		$columns = $this->element->getColumns($tableCode);

		if(!array_key_exists($fieldCode, $columns))
			return $this->jsonResult(['success' => false, 'message' => 'not found field']);

		// Получаем настройки поля
		$emField = EmTypes::findFirst([
			'field = ?0 and table = ?1',
			'bind' => [
				$fieldCode, $tableCode
			]
		]);

		if (empty($emField))
			return $this->jsonResult(['success' => false, 'message' => 'field not found']);

		// Подготавливаем файлы для записи
		if($typeUpload == 'link')
		{
			if (!filter_var($link, FILTER_VALIDATE_URL))
				return $this->jsonResult(['success' => false, 'message' => 'invalid url']);

			$file = FileHelper::getFileByUrl($link);

			if(!$file)
				return $this->jsonResult(['success' => false, 'message' => 'invalid file']);

			$files = [$file];
		}
		else if($typeUpload == 'file')
		{
			if ($this->request->hasFiles() == false)
				return $this->jsonResult(['success' => false, 'message' => 'empty upload']);

			$files = $this->request->getUploadedFiles();

			foreach($files as $file)
			{
				if(!FileHelper::getTypeByMimeType($file->getRealType()))
					return $this->jsonResult(['success' => false, 'message' => 'invalid file type']);
			}
		}
		else
			return $this->jsonResult(['success' => false, 'message' => 'unidentified upload type']);

		// Сохраняем файлы во временную директорию
		$fieldClass = new EmFileField('', $emField->settings);
		$fieldValue = [];

		foreach($files as $file)
		{
			$fileType = FileHelper::getTypeByMimeType($file->getRealType());
			$tempPath = FileHelper::saveToTemp($file);

			if(!$tempPath)
				return $this->jsonResult(['success' => false, 'message' => 'error on save']);

			$fileName = basename($tempPath);
			$fileInfo = [
				'new'    => true,
				'upName' => $fileName,
				'type'   => $fileType,
				'path'   => '/element' . str_replace(ROOT, '', $tempPath),
			];

			if($fileType !== 'image')
			{
				$fieldValue[] = $fileInfo;
				continue;
			}

			$smallSizePath     = FileHelper::resize($tempPath, $fieldClass->getResolutions()['small'], FileHelper::$tempDir, $fileName);
			$fileInfo['sizes'] = [
				'small' => '/element' . str_replace(ROOT, '', $smallSizePath)
			];
			$fieldValue[] = $fileInfo;
		}

		$fieldClass->setValue(json_encode($fieldValue));

		if(!$prepareForSave)
			return $this->jsonResult(['success' => true, 'value' => $fieldClass->getValue()]);

		// Полностью сохраняем файлы
		$fieldValue = $fieldClass->getValue();
		$fieldClass->setValue($fieldValue);
		$fieldValue = $fieldClass->saveValue();
		$fieldClass->setValue($fieldValue);

		return $this->jsonResult(['success' => true, 'value' => $fieldClass->getValue()]);
	}
}
