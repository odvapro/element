<?php
class EmDateCest
{
	/**
	 * Save tests
	 */
	public function save(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// check incorect date save
		$this->saveField($I, 'incorrect value', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => null]);

		// check correct date field type
		$this->saveField($I, date('Y-m-d H:i',time()), 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => date('Y-m-d',time()) ]);

		// check correct datetime field type
		$this->saveField($I, date('Y-m-d H:i',time()), 1, 'datetime');
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'datetime' => date('Y-m-d H:i:\\0\\0',time()) ]);

		// save empty date
		$this->saveField($I, '', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => null ]);
	}

	/**
	 * Save tests
	 */
	public function get(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// check incorect date save
		$this->getField($I, 2);
		// $I->seeResponseContainsJson(['success' => true]);
	}


	protected function saveField(ApiTester $I, $newValue, Int $id, $fieldName='date')
	{
		$I->sendPOST('/el/update/',
		[
			'update' =>
			[
				'table' => 'block_type',
				'set' => [$fieldName => $newValue ],
				'where' => [
					'operation' => 'and',
					'fields'    => [['code' => 'id', 'operation' => 'IS', 'value' => $id]]
				],
			]
		]);
	}

	protected function getField(ApiTester $I, Int $id)
	{
		$I->sendPOST('/el/select/',
		[
			'update' =>
			[
				'fields' => ['file'],
				'from' => 'block_type',
				'where' => [
					'operation' => 'and',
					'fields'    => [['code' => 'id', 'operation' => 'IS', 'value' => $id]]
				],
			]
		]);
	}

}
