<?php

include ROOT . '/app/library/Image.php';

class IndexFController extends ControllerBase
{
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

		if(empty($fieldCode) || empty($tableCode) || empty($typeUpload) || empty($primaryKey) || empty($primaryKeyValue))
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

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
		$selectedItem = $this->element->select($select)[0];

		if (empty($selectedItem))
			return $this->jsonResult(['success' => false, 'message' => 'empty result']);

		if(!isset($selectedItem[$fieldCode]))
			return $this->jsonResult(['success' => false, 'message' => 'not found field']);

		$emField = EmTypes::findFirst([
			'field = ?0 and table = ?1',
			'bind' => [
				$fieldCode, $tableCode
			]
		]);

		if (empty($emField))
			return $this->jsonResult(['success' => false, 'message' => 'field not found']);

		if (empty($emField->settings['path']))
			return $this->jsonResult(['success' => false, 'message' => 'field settings not found']);

		$imageTypes = [
			'image/jpeg'                    => '.jpeg',
			'image/png'                     => '.png',
			'image/gif'                     => '.gif',
			'image/pjpeg'                   => '.jpeg',
		];

		$fileTypes = [
			'application/msword'            => '.doc',
			'text/plain'                    => '.txt',
			'application/pdf'               => '.pdf',
			'application/mspowerpoint'      => '.ppt',
			'application/xml'               => '.xml',
			'text/xml'                      => '.xml',
			'application/powerpoint'        => '.ppt',
			'application/vnd.ms-powerpoint' => '.ppt',
			'application/x-mspowerpoint'    => '.ppt',
			'application/plain'             => '.txt',
			'application/zip'               => '.zip',
			'multipart/x-zip'               => '.zip',
			'application/x-zip-compressed'  => '.zip',
			'application/x-compressed'      => '.zip',
			'application/x-compress'        => '.zip'
		];

		$imageSizes = [
			[
				'width'  => 50,
				'height' => 50,
				'name'   => 'small'
			],
			[
				'width'  => 100,
				'height' => 100,
				'name'   => 'thumb'
			]
		];

		if($typeUpload == 'link')
		{
			if (!filter_var($link, FILTER_VALIDATE_URL))
				return $this->jsonResult(['success' => false, 'message' => 'invalid url']);

			$tempName = tempnam('/tmp', 'php');
			$imgRawData = @file_get_contents($link);

			if ($imgRawData === false)
				return $this->jsonResult(['success' => false, 'message' => 'invalid url']);

			$size       = file_put_contents($tempName, $imgRawData);
			$fileParams = [
				'name'     => 'linkFile',
				'type'     => mime_content_type($tempName),
				'tmp_name' => $tempName,
				'error'    => 0,
				'size'     => strlen($imgRawData)
			];

			$files = [
				new Phalcon\Http\Request\File($fileParams)
			];
		}
		else if ($typeUpload == 'file')
		{
			if ($this->request->hasFiles() == false)
				return $this->jsonResult(['success' => false, 'message' => 'empty upload']);

			$files = $this->request->getUploadedFiles();
		}
		else
			return $this->jsonResult(['success' => false, 'message' => 'unidentified upload type']);

		foreach ($files as $indexFile => $file)
		{
			$fileType       = $file->getRealType();

			if(!empty($imageTypes[$fileType]))
			{
				$extension = $imageTypes[$fileType];
				$type      = 'image';
			}
			else if(!empty($fileTypes[$fileType]))
			{
				$extension = $fileTypes[$fileType];
				$type      = 'file';
			}
			else
				continue;

			$fileName     = hash('md5', $file->getName() . date('Y.m.d H:i:s') . $indexFile);
			$fullFileName = $fileName . $extension;
			$localPath    = rtrim($emField->settings['path'], '/') . '/';
			$fullPath     = ROOT . $localPath . $fullFileName;

			if($typeUpload == 'link')
				$error = !rename($file->getTempName(), $fullPath);
			else
				$error = !$file->moveTo($fullPath);

			if($error)
				continue;

			$fileValue = [
				'upName' => $fullFileName,
				'type'   => $type,
				'path'   => $localPath . $fullFileName
			];

			if($type == 'image')
			{
				$fileValue['sizes'] = [];
				foreach($imageSizes as $size)
				{
					$resizeImage = $this->_resizeImage(
						$fullPath,
						$size,
						"{$fileName}{$size['width']}x{$size['height']}",
						$localPath,
						$extension
					);

					if(!$resizeImage)
						continue;

					$fileValue['sizes'][$size['name']] = $resizeImage;
				}
			}

			$selectedItem[$fieldCode]['value'][] = $fileValue;
		}

		$selectedItem[$fieldCode]['value'] = json_encode($selectedItem[$fieldCode]['value']);
		$updateValue = [
			'table' => $tableCode,
			'set'   => [
				$fieldCode . " = '" . $selectedItem[$fieldCode]['value'] . "'"
			],
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
		$updateResult = $this->eldb->update($updateValue);

		if(!$updateResult)
			return $this->jsonResult(['success' => false, 'message' => 'error on save']);

		$columnsInfo = $this->element->getColumns($tableCode);


		$fieldClass = new EmFileField(
			$selectedItem[$fieldCode]['value'],
			$tableCode,
			$columnsInfo[$fieldCode]
		);
		return $this->jsonResult(['success' => true, 'value' => $fieldClass->getValue()]);
	}

	/**
	 * Удалить файл с сервера
	 * @return json
	 */
	public function deleteAction()
	{
		$fieldCode       = $this->request->getPost('fieldCode');
		$tableCode       = $this->request->getPost('tableCode');
		$primaryKey      = $this->request->getPost('primaryKey');
		$primaryKeyValue = $this->request->getPost('primaryKeyValue');
		$fileName        = $this->request->getPost('fileName');

		if(empty($fieldCode) || empty($tableCode) || empty($fileName) || empty($primaryKey) || empty($primaryKeyValue))
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

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
		$selectedItem = $this->element->select($select)[0];

		if (empty($selectedItem))
			return $this->jsonResult(['success' => false, 'message' => 'empty result']);

		if(!isset($selectedItem[$fieldCode]))
			return $this->jsonResult(['success' => false, 'message' => 'not found field']);

		$curField = $selectedItem[$fieldCode];
		$deleted  = false;

		foreach($curField['value'] as $index => $value)
		{
			if($value['upName'] !== $fileName)
				continue;
		}

		echo "<pre>";
		print_r($curField);
		exit();

		$emField = EmTypes::findFirst([
			'field = ?0 and table = ?1',
			'bind' => [
				$fieldCode, $tableCode
			]
		]);

		if (empty($emField))
			return $this->jsonResult(['success' => false, 'message' => 'field not found']);

		if (empty($emField->settings['path']))
			return $this->jsonResult(['success' => false, 'message' => 'field settings not found']);
	}
	/**
	 * Изменить размер изображения
	 */
	public function _resizeImage($imagePath, $imageSize, $newName, $savePath, $extension)
	{
		if(empty($imageSize['width']) || empty($imageSize['height']))
			return false;

		$image = new Image($imagePath);
		$image->crop($imageSize['width'], $imageSize['height']);

		$publicPath = "{$savePath}{$imageSize['name']}_{$newName}{$extension}";
		$image->save(ROOT . $publicPath);
		return "{$this->config->application->baseUri}/{$publicPath}";
	}
}