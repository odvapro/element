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
		$primaryKey      = $this->request->getPost('primaryKey');
		$primaryKeyValue = $this->request->getPost('primaryKeyValue');
		$link            = $this->request->getPost('link');

		if(empty($fieldCode) ||
		   empty($tableCode) ||
		   empty($typeUpload) ||
		   empty($primaryKey) ||
		   empty($primaryKeyValue)
		)
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

		// Проверяем существование записи
		$select = [
			'from' => $tableCode,
			'where' => [
				'operation' => 'and',
				'fields'    =>
				[
					[
						'code'      => $primaryKey,
						'operation' => 'IS',
						'value'     => $primaryKeyValue
					],
				]
			]
		];
		$selectedItem = $this->eldb->select($select)[0];

		if (empty($selectedItem))
			return $this->jsonResult(['success' => false, 'message' => 'empty result']);

		if(!isset($selectedItem[$fieldCode]))
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

		// Сохраняем файлы
		$this->element;
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

		return $this->jsonResult(['success' => true, 'value' => $fieldClass->getValue()]);
	}
}