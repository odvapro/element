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
		$files           = $this->request->getUploadedFiles();

		if (empty($fieldCode) || empty($tableCode) || empty($typeUpload) || empty($primaryKey) || empty($primaryKeyValue))
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

		$select = [
			'from' => $tableCode,
			'where' => [
				'operation' => 'and',
				'fields' =>
				[
					[
						'code' => $primaryKey,
						'operation' => 'IS',
						'value' => $primaryKeyValue
					],
				]
			]
		];
		$selectedItem = $this->element->select($select)[0];

		if (empty($selectedItem))
			return $this->jsonResult(['success' => false, 'message' => 'empty result']);

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

		if ($typeUpload == 'link')
		{
			if (!filter_var($link, FILTER_VALIDATE_URL))
				return $this->jsonResult(['success' => false, 'message' => 'invalid url']);

			$tempName = tempnam('/tmp', 'php');
			$imgRawData = @file_get_contents($link);

			if ($imgRawData === false)
				return $this->jsonResult(['success' => false, 'message' => 'invalid url']);

			file_put_contents($tempName, $imgRawData);
			$fileSize = strlen($imgRawData);
			$fileType =  mime_content_type($tempName);

			$exten = $imageTypes[$fileType];
			$fileName = hash('md5', $tempName . date('Y.m.d H:i:s')) . $exten;
			$shortName = hash('md5', $tempName . date('Y.m.d H:i:s'));
			$pathFile = ROOT . $emField->settings['path'] . $fileName;
			$shortPath = $emField->settings['path'] . $fileName;

			if (file_exists($pathFile))
				unlink($pathFile);

			$fp = fopen($pathFile, "w+");
			fwrite($fp, $imgRawData);
			fclose($fp);

			if (!empty($imageTypes[$fileType]))
			{
				$smallImage = $this->_resizeImage($pathFile, [ 'width' => 50, 'height' => 50, 'name' => 'small' ], $shortName . '50', $emField->settings['path'], $exten);
				$thumbImage = $this->_resizeImage($pathFile, [ 'width' => 100, 'height' => 100, 'name' => 'thumb' ], $shortName . '100', $emField->settings['path'], $exten);

				$images =
				[
					"upName"    => $fileName . '.' . $exten,
					"type"      => "image",
					"path"      => "{$this->config->application->domain}{$this->config->application->baseUri}/{$shortPath}/",
					"sizes"     =>
					[
						"small" => $smallImage,
						"thumb" => $thumbImage,
					]
				];

				$selectedItem[$fieldCode]['value'][] = $images;
			}
			else if (!empty($fileTypes[$fileType]))
			{
				$files =
				[
					"upName"    => $fileName . '.' . $exten,
					"type"      => "file",
					"path"      => $pathFile,
				];

				$selectedItem[$fieldCode]['value'][] = $files;
			}

			$updateValue = [
				'table' => $tableCode,
				'set' => [
					$fieldCode . " = '" . json_encode($selectedItem[$fieldCode]['value']) . "'"
				],
				'where' => [
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => $primaryKey,
							'operation' => 'IS',
							'value' => $primaryKeyValue
						],
					]
				]
			];

			$updateResult = $this->eldb->update($updateValue);
			return $this->jsonResult(['success' => true, 'value' => $selectedItem[$fieldCode]['value']]);
		}
		else if ($typeUpload == 'file')
		{
			if ($this->request->hasFiles() == false)
				return $this->jsonResult(['success' => false, 'message' => 'empty upload']);

			foreach ($files as $indexFile => $file)
			{
				$fileSourceName = $file->getName();
				$fileType = $file->getRealType();

				$filePath = ROOT . $emField->settings['path'] . '/';
				$localPath = $emField->settings['path'] . '/';
				$fileName = hash('md5', $fileSourceName . date('Y.m.d H:i:s') . $indexFile);
				$file->moveTo($filePath . $fileName . '.' . $file->getExtension());

				$fullPath = $filePath . $fileName . '.' . $file->getExtension();

				if (!empty($imageTypes[$fileType]))
				{
					$curFile = $this->config->application->domain . $this->config->application->baseUri . '/' . $localPath . $fileName . $imageTypes[$fileType];
					$smallImage = $this->_resizeImage($fullPath, [ 'width' => 50, 'height' => 50, 'name' => 'small' ], $fileName . '50', $localPath, $imageTypes[$fileType]);
					$thumbImage = $this->_resizeImage($fullPath, [ 'width' => 100, 'height' => 100, 'name' => 'thumb' ], $fileName . '100', $localPath, $imageTypes[$fileType]);

					$selectedItem[$fieldCode]['value'][] =
					[
						"upName"    => $fileName . $imageTypes[$fileType],
						"type"      => "image",
						"path"      => $curFile,
						"sizes"     =>
						[
							"small" => $smallImage,
							"thumb" => $thumbImage
						]
					];
				}
				else if (!empty($fileTypes[$fileType]))
				{
					$curFile = $this->config->application->domain . $this->config->application->baseUri . '/' . $localPath . $fileName . $fileTypes[$fileType];
					$selectedItem[$fieldCode]['value'][] =
					[
						"upName"    => $fileName . $fileTypes[$fileType],
						"type"      => "file",
						"path"      => $curFile
					];
				}
			}

			$updateValue = [
				'table' => $tableCode,
				'set' => [
					$fieldCode . " = '" . json_encode($selectedItem[$fieldCode]['value']) . "'"
				],
				'where' => [
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => $primaryKey,
							'operation' => 'IS',
							'value' => $primaryKeyValue
						],
					]
				]
			];

			$updateResult = $this->eldb->update($updateValue);
			return $this->jsonResult(['success' => true, 'value' => $selectedItem[$fieldCode]['value']]);
		}
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
		return "{$this->config->application->domain}{$this->config->application->baseUri}/{$publicPath}";
	}
}