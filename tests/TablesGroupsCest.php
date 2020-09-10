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

	public function checkDisableAccess(ApiTester $I)
	{
		$this->authorize($I, 'admin', 'adminpass');

		$I->sendPOST('/groups/disableGroupsAccess/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/groups/disableGroupsAccess/',['tableName' => 'block_type']);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function checkAccessUsersAdmin(ApiTester $I)
	{
		$this->logOut($I);
		$this->checkAccessUsers($I, 'admin', 'adminpass', true);
	}

	public function checkAccessUsersUser(ApiTester $I)
	{
		$this->logOut($I);
		$this->checkAccessUsers($I, 'user1', 'adminpass', false);
	}

	protected function checkAccessUsers(ApiTester $I, $login, $password, $result)
	{
		$this->authorize($I, $login, $password);

		$I->sendPOST('/users/updateUser/', ['id'   => 2,'name' => 'test']);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/users/deleteUser/', ['id'   => 3]);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/users/addUser/', ['name' => 'test','login' => 'test','email' => 'test@test.test','password' => '1111']);
		$I->seeResponseContainsJson(['success' => $result]);
	}

	public function checkAccessGroupsAdmin(ApiTester $I)
	{
		$this->logOut($I);
		$this->checkAccessGroups($I, 'admin', 'adminpass', true);
	}

	public function checkAccessGroupsUser(ApiTester $I)
	{
		$this->logOut($I);
		$this->checkAccessGroups($I, 'user1', 'adminpass', false);
	}

	protected function checkAccessGroups(ApiTester $I, $login, $password, $result)
	{
		$this->authorize($I, $login, $password);

		$I->sendPOST('/groups/get/');
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/add/');
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/addUser/', ['id' => 3,'group' => 2]);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/removeUser/', ['id' => 1,'group' => 2]);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/remove/', ['id' => 3]);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/update/', ['id' => 2,'name' => 'test']);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/getAccessOptions/');
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/setGroupAccess/', ['accessStr' => 'ACCESS_READ', 'groupId' => 2, 'tableName' => 'test_table']);
		$I->seeResponseContainsJson(['success' => $result]);

		$I->sendPOST('/groups/disableGroupsAccess/', ['tableName' => 'test_table']);
		$I->seeResponseContainsJson(['success' => $result]);
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