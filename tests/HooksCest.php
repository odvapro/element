<?php

class HooksCest
{
	public function before(ApiTester $I)
	{
		$I->copyDir('./tests/_data/hooks/', './app/hooks/');

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages'],
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		$I->sendGET('/el/select/',
		[
			'select'     => ['from' => 'pages', 'call_error' => true],
		]);

		$I->seeResponseContainsJson(['success' => false]);
		$I->deleteFile('app/hooks/PagesHooks.php');
	}

	public function after(ApiTester $I)
	{
		$I->copyDir('./tests/_data/hooks/', './app/hooks/');
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages'],
			'limit'  => 1000,
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');
		$resultJson = json_decode($I->grabResponse(), true);
		$I->assertCount(105, $resultJson['result']['items']);

		$I->sendGET('/el/select/',
		[
			'select' => ['from' => 'pages', 'not_empty_description' => true],
			'limit'  => 1000,
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');
		$resultJson = json_decode($I->grabResponse(), true);
		$I->assertCount(83, $resultJson['result']['items']);
		$I->deleteFile('app/hooks/PagesHooks.php');
	}
}
