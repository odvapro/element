<?php
class EmNodeCest
{
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
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'node' => null]);

		// check correct node
		$this->saveField($I, 20, 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'node' => 20 ]);

		// check correct node
		$this->saveField($I, ['id'=>20,'name'=>'some name'], 2);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 2, 'node' => 20 ]);

		// save empty node
		$this->saveField($I, '', 1);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('block_type', ['id' => 1, 'date' => NULL ]);
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