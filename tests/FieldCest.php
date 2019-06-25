<?php

class FieldCest
{
	public function changeStatus(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_check/index/changeStatus', [
			'fieldCode'       => 'special',
			'tableCode'       => 'products',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '22',
			'status'          => false
		]);

		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_check/index/changeStatus', [
			'fieldCode'       => 'special',
			'tableCode'       => 'products',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '20',
			'status'          => true
		]);

		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_check/index/changeStatus', [
			'fieldCode'       => 'special',
			'tableCode'       => 'products',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '21',
			'status'          => ''
		]);

		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_check/index/changeStatus', [
			'fieldCode'       => 'special',
			'tableCode'       => 'products',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '21',
			'status'          => 'asdasd'
		]);

		$I->seeResponseContainsJson(['success' => true]);
	}

	public function saveSelectedItem(ApiTester $I)
	{
		$I->sendPOST('/field/em_list/index/saveSelectedItem', [
			'fieldCode'       => 'name',
			'tableCode'       => 'block_type',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '1',
			'selectedValue'   => 'asd'
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_list/index/saveSelectedItem', [
			'fieldCode'       => 'name',
			'tableCode'       => 'block_type',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '1',
			'selectedValue'   => 'asd'
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_list/index/saveSelectedItem', [
			'fieldCode'       => 'name',
			'tableCode'       => 'block_type',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '2',
			'selectedValue'   => 'ff'
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_list/index/saveSelectedItem', [
			'fieldCode'       => 'name',
			'tableCode'       => 'block_type',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '2'
		]);
		$I->seeResponseContainsJson(['success' => true]);
	}
}