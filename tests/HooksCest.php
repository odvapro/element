<?php

class HooksCest
{
	public function before(ApiTester $I)
	{
		$I->copyDir('./tests/_data/hooks/', './app/hooks/');

		$this->logIn($I, 'admin', 'adminpass');

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages'],
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages', 'call_error' => true],
		]);

		$I->seeResponseContainsJson(['success' => false]);
		$I->deleteFile('app/hooks/PagesHooks.php');
		$this->logOut($I);
	}

	public function after(ApiTester $I)
	{
		$I->copyDir('./tests/_data/hooks/', './app/hooks/');

		$this->logIn($I, 'admin', 'adminpass');

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages', 'limit' => 1000],
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');
		$resultJson = json_decode($I->grabResponse(), true);
		$I->assertCount(105, $resultJson['result']['items']);

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages', 'not_empty_description' => true, 'limit' => 1000],
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');
		$resultJson = json_decode($I->grabResponse(), true);
		$I->assertCount(83, $resultJson['result']['items']);
		$I->deleteFile('app/hooks/PagesHooks.php');
		$this->logOut($I);
	}

	public function groupHook(ApiTester $I)
	{
		$I->copyDir('./tests/_data/hooks/', './app/hooks/');
		$I->copyDir('./tests/_data/group_hooks/', './app/hooks/Api/');

		$this->logIn($I, 'admin', 'adminpass');

		$groupId = $this->addGroup($I, 'Api');
		$token = $this->createToken($I, $groupId);

		$this->addAccess($I, ['groupId'=>$groupId, 'accessStr' => 'FULL', 'tableName' => 'pages']);

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages', 'not_empty_description' => true, 'limit' => 1000],
			'token'  => $token,
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');
		$resultJson = json_decode($I->grabResponse(), true);
		$I->assertCount(39, $resultJson['result']['items']);

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages', 'call_error_by_group' => true, 'limit' => 1000],
			'token'  => $token,
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->deleteDir('app/hooks/Api');
		$I->deleteFile('app/hooks/PagesHooks.php');
	}

	private function createToken(ApiTester $I, $groupId)
	{
		$this->logIn($I, 'admin', 'adminpass');

		$I->sendPOST('/tokens/createToken/', ['group_id' => $groupId]);
		$I->seeResponseContainsJson(['success' => true]);
		$token = json_decode($I->grabResponse(), true);
		$token = $token['token']['value'];

		$this->logOut($I);

		return $token;
	}

	private function addAccess(ApiTester $I, $access)
	{
		$this->logIn($I, 'admin', 'adminpass');

		$I->sendPOST('/groups/setGroupAccess/', $access);
		$I->seeResponseContainsJson(['success' => true]);

		$this->logOut($I);
	}

	private function addGroup(ApiTester $I, $groupName, $newId = 3)
	{
		$this->logIn($I, 'admin', 'adminpass');

		$I->sendGET('/groups/add/');
		$I->seeResponseContainsJson(['success' => true]);
		$result = $I->grabResponse();
		$result = json_decode($result, true);
		$I->assertEquals($result['group']['id'],$newId);
		$I->seeInDatabase('em_groups', ['id' => $newId, 'name' => 'Untiteled']);

		$I->sendPOST('/groups/update/',['id' => $newId, 'name' => $groupName]);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('em_groups', ['id' => $newId,'name' => $groupName]);

		$this->logOut($I);
		return $newId;
	}

	private function logIn(ApiTester $I, $login, $password)
	{
		$I->sendPOST('/auth/', ['login' => $login, 'password' => $password]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	private function logOut(ApiTester $I)
	{
		$I->sendPOST('/auth/logOut');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
	}
}
