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
		$dateValue = 'incorrect format';
		$this->saveField($I, 'incorrect value', 1);
		$I->seeResponseContainsJson(['success' => false]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => null]);

		// check correct date
		$this->saveField($I, date('Y-m-d',time()), 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => date('Y-m-d',time()) ]);

		// save empty date
		$this->saveField($I, '', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => NULL ]);
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


	protected function saveField(ApiTester $I, $newValue, Int $id)
	{
		$I->sendPOST('/el/update/',
		[
			'update' =>
			[
				'table' => 'block_type',
				'set' => ['date' => $newValue ],
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