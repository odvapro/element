<?php
class TablesGroupsCest
{
	public function addAccess(ApiTester $I)
	{
		$this->authorize($I, 'admin', 'adminpass');

		$I->sendPOST('/groups/setGroupAccess/',['groupId'=>2, 'accessStr' => 'ACCESS_READ']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/groups/setGroupAccess/',['groupId'=>2, 'tableName' => 'block_type']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/groups/setGroupAccess/',['tableName' => 'block_type']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/groups/setGroupAccess/',['groupId'=>2, 'accessStr' => 'ACCESS_FULL', 'tableName' => 'block_type']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/groups/setGroupAccess/',['groupId'=>2, 'accessStr' => 'ACCESS_READ', 'tableName' => 'block_type']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/groups/setGroupAccess/',['groupId'=>2, 'accessStr' => 'ACCESS_FULL', 'tableName' => 'test_table']);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function checkReadAccess(ApiTester $I)
	{
		$this->logOut($I);
		$this->authorize($I, 'user1', 'adminpass');

		$this->select($I, 'block_type');
		$I->seeResponseContainsJson(['success' => false]);

		$this->select($I, 'test_table');
		$I->seeResponseContainsJson(['success' => true]);

		$this->select($I, 'callbacks');
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function checkWriteAccess(ApiTester $I)
	{
		$this->authorize($I, 'user1', 'adminpass');

		$I->sendPOST('/el/insert/',
		[
			'insert' => [
				'table' => 'test_table',
				'values' => [
					'name'  => '11',
					'email' => 'qwe',
					'col'   => '222222',
					'avat'  => '222211211'
				]
			]
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' => [
				'table' => 'callbacks',
				'values' => [
					'phone'  => '47324972394',
					'date' => '2018-11-05 17:27:00',
					'name'   => 'Test',
					'status'  => '1'
				]
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	protected function select(ApiTester $I, $tableName)
	{
		$I->sendGET('/el/select',
		[
			'select' => [
				'from' => $tableName
			]
		]);
	}
	protected function authorize(ApiTester $I, $login, $password)
	{
		$I->sendPOST('/auth/', ['login' => $login, 'password' => $password]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	protected function logOut(ApiTester $I)
	{
		$I->sendPOST('/auth/logOut');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
	}
}