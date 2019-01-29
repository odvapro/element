<?php

class AuthCest
{
	public function auth(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'admin']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/auth/');
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function isLogged(ApiTester $I)
	{
		$I->sendPOST('/auth/isLogged');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/auth/isLogged');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function logOut(ApiTester $I)
	{
		$I->sendPOST('/auth/logOut');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
	}
}