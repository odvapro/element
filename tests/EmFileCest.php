<?php
class EmFileCest
{
	public function checkFolders(ApiTester $I)
	{
		$I->assertFileExists($this->getFullPath('/element/public/tmp/.gitkeep'));
		$I->assertFileExists($this->getFullPath('/element/public/upload/.gitkeep'));
	}

	public function checkPath(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_file/index/checkPath/', ['path' => 'element/public/nofolder']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/field/em_file/index/checkPath/', ['path' => 'element/public/upload']);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function checkUploadLink(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$link = 'https://www.gravatar.com/avatar/28ff2f48655820e1aaf15687dc5e6be5&s=40';

		// Отправка на сохранение в tmp
		$I->sendPOST('/field/em_file/index/upload/', [
			'tableCode'       => 'block_type',
			'fieldCode'       => 'file',
			'typeUpload'      => 'link',
			'link'            => $link
		]);
		$I->seeResponseContainsJson(['success' => true]);
		$result = $I->grabResponse();
		$result = json_decode($result, true);
		$I->assertIsArray($result['value']);
		// check file
		$headers = get_headers($result['value'][0]['path']);
		$I->assertEquals('HTTP/1.1 200 OK',$headers[0]);

		// Проверка на существование файлов
		foreach($result['value'] as $file)
		{
			$I->assertArrayHasKey('new', $file);

			$this->checkFile($I, $file);
		}

		// Отправка на сохранение
		$this->saveField($I, $result['value'], 1);

		// Запрос значения после сохранения
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['file'],
				'from' => 'block_type',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'id',
							'operation' => 'IS',
							'value' => '1'
						]
					]
				]
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$result = $I->grabResponse();
		$result = json_decode($result, true);

		$I->assertArrayHasKey('result', $result);
		$I->assertArrayHasKey('items', $result['result']);
		$I->assertIsArray($result['result']['items']);

		$result = $result['result']['items'][0];

		$I->assertArrayHasKey('file', $result);
		$I->assertArrayHasKey('value', $result['file']);

		$result = $result['file']['value'];

		// Проверка на существование файлов
		foreach($result as &$file)
		{
			$this->checkFile($I, $file);

			$file['delete'] = true;
		}

		unset($file);

		// Отправляем файл на удаление
		$this->saveField($I, $result, 1);

		// Проверяем удалились ли файлы
		foreach($result as $file)
		{
			$I->assertFileNotExists($this->getFullPath($file['localPath']));

			foreach($file['sizes'] as $size)
				$I->assertFileNotExists($this->getFullPath($size['localPath']));
		}
	}

	protected function getFullPath(String $path)
	{
		return __DIR__ . "/../.." . $path;
	}

	protected function checkFile(ApiTester $I, Array $file)
	{
		$I->assertArrayHasKey('localPath', $file);
		$I->assertArrayHasKey('sizes', $file);

		$I->assertFileExists($this->getFullPath($file['localPath']));

		foreach($file['sizes'] as $size)
		{
			$I->assertArrayHasKey('localPath', $size);

			$I->assertFileExists($this->getFullPath($size['localPath']));
		}
	}

	protected function saveField(ApiTester $I, Array $newValue, Int $id)
	{
		$I->sendPOST('/el/update/',
		[
			'update' =>
			[
				'table' => 'block_type',
				'set' => [
					'file' => $newValue
				],
				'where' => [
					'operation' => 'and',
					'fields'    =>
					[
						['code' => 'id', 'operation' => 'IS', 'value' => $id],
					]
				],
			]
		]);

		$I->seeResponseContainsJson(['success' => true]);
	}
}