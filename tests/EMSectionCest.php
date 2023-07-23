<?php
class EmSectionCest
{
	/**
	 * Проверяем что возвращает методо автокомплита
	 */
	public function autoComplete(ApiTester $I)
	{
		// TODO
		// нужно еще доставать - есть ли у раздела дочерние элементы

		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// по умочанию долежен достать только родительские разделы
		$I->sendPOST('/field/em_section/index/autoComplete', [
			'sectionTableCode'        => 'sections',
			'sectionFieldCode'        => 'id',
			'sectionSearchCode'       => 'name',
			'sectionParentsFieldCode' => 'parent_section'
		]);

		$resResult = json_decode($I->grabResponse(), true);
		$I->assertEquals($resResult['result'][0]['id'],1);
		$I->assertEquals($resResult['result'][1]['id'],5);

		// но если передать родительский раздел - нужно достать только его дочерние элементы
		$I->sendPOST('/field/em_section/index/autoComplete', [
			'sectionTableCode'        => 'sections',
			'sectionFieldCode'        => 'id',
			'sectionSearchCode'       => 'name',
			'sectionParentsFieldCode' => 'parent_section',
			'parentId'                => '13'
		]);
		$resResult = json_decode($I->grabResponse(), true);
		$I->assertEquals($resResult['result'][0]['id'],14);
		$I->assertEquals($resResult['result'][1]['id'],15);


		// а если передать еще и поиск по имени, то должны придти только разделы с совпадениями
		$I->sendPOST('/field/em_section/index/autoComplete', [
			'sectionTableCode'        => 'sections',
			'sectionFieldCode'        => 'id',
			'sectionSearchCode'       => 'name',
			'sectionParentsFieldCode' => 'parent_section',
			'parentId'                => '13',
			'q'                       => 'Boxes'
		]);
		$resResult = json_decode($I->grabResponse(), true);
		$I->assertEquals($resResult['result'][0]['id'],15);

		// пример с пустым результатом
		$I->sendPOST('/field/em_section/index/autoComplete', [
			'sectionTableCode'        => 'sections',
			'sectionFieldCode'        => 'id',
			'sectionSearchCode'       => 'name',
			'sectionParentsFieldCode' => 'parent_section',
			'parentId'                => '13',
			'q'                       => 'empty case'
		]);
		$resResult = json_decode($I->grabResponse(), true);
		$I->assertEquals($resResult['result'],[]);
	}

	/**
	 * Save tests
	 */
	public function save(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// check incorect node save
		$this->saveField($I, 'incorrect value', 1);
		$I->seeResponseContainsJson(['success' => false]);

		// check correct node
		$this->saveField($I, [['id' => 20]], 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'node' => 20 ]);

		// save empty node
		$this->saveField($I, '', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'node' => null ]);

		// check saving multiple nodes
		$this->saveField($I, [['id' => 20],['id' => 23],['id' => 24]], 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'node' => '20,23,24' ]);

		// check incorrect node
		$this->saveField($I, [['id'=>20],['id'=>'some name']], 2);
		$I->seeResponseContainsJson(['success' => false]);
	}

	/**
	 * Save tests
	 */
	public function get(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// check multiple node
		$I->haveInDatabase('block_type', ['id'=>8,'node' => '20,23']);
		$this->getField($I, 8);
		$I->seeResponseContainsJson(['success' => true]);
		$resp = $I->grabResponse();
		$resp = json_decode($resp,true);
		$I->assertEquals($resp['result']['items'][0]['node'][0]['id'],20);
		$I->assertEquals($resp['result']['items'][0]['node'][1]['id'],23);

		// check single node
		$I->haveInDatabase('block_type', ['id'=>9,'node' => '23']);
		$this->getField($I, 9);
		$I->seeResponseContainsJson(['success' => true]);
		$resp = $I->grabResponse();
		$resp = json_decode($resp,true);
		$I->assertEquals($resp['result']['items'][0]['node'][0]['id'],23);
	}

	protected function getField(ApiTester $I, Int $id)
	{
		$I->sendPOST('/el/select/',
		[
			'select' =>
			[
				'from' => 'block_type',
				'where' => [
					'operation' => 'and',
					'fields'    => [['code' => 'id', 'operation' => 'IS', 'value' => $id]]
				],
			]
		]);
	}

	protected function saveField(ApiTester $I, $newValue, Int $id)
	{
		$I->sendPOST('/el/update/',
		[
			'update' =>
			[
				'table' => 'block_type',
				'set' => ['node' => $newValue ],
				'where' => [
					'operation' => 'and',
					'fields'    => [['code' => 'id', 'operation' => 'IS', 'value' => $id]]
				],
			]
		]);
	}

}
