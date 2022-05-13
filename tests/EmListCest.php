<?php
class EmListCest
{
	/**
	 * Save tests
	 */
	public function save(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// check incorect list code
		$this->saveField($I, 'incorrect value', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'list' => null]);

		// check correct list
		$this->saveField($I, ['first'], 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'list' => 'first' ]);

		// check multiple list
		$this->saveField($I, ['first','second','third'], 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'list' => 'first,second,third' ]);

		// save empty date
		$this->saveField($I, '', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'list' => null ]);
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
		// $this->getField($I, 2);
		// $I->seeResponseContainsJson(['success' => true]);
	}


	protected function saveField(ApiTester $I, $newValue, Int $id, $fieldName='list')
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
