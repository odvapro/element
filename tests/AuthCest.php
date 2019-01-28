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

	public function register(ApiTester $I)
	{
		$I->sendPOST('/auth/signup', ['login' => 'admin', 'password' => 'password']);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth/signup');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth/signup',
			[
				'login'      => 'admin',
				'password'   => 'adminpass',
				'email'      => 'ggs@fa.ru',
				'name'       => 'Мое имя',
				'repassword' => 'adminpass'
			]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth/signup',
			[
				'login'      => 'admin2',
				'password'   => '1111111',
				'email'      => 'adsad@ads.er',
				'name'       => 'Мое имя',
				'repassword' => '1111111'
			]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/auth/signup',
			[
				'login' => 'admine',
				'password' => '1111111111',
				'email' => 'adsad@ads.er',
				'name' => 'Мое имя',
				'repassword' => '1111111111'
			]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function isLogged(ApiTester $I)
	{
		$I->sendPOST('/auth/isLogged');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function logOut(ApiTester $I)
	{
		$I->sendPOST('/auth/logOut');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
	}
}