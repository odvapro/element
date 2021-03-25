<?php

class TokensCest
{
	public function createToken(ApiTester $I)
	{
		$this->authorize($I, 'admin', 'adminpass');

		$I->sendPOST('/tokens/createToken/', []);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/createToken/', ['group_id' => '']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/createToken/', ['group_id' => -1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/createToken/', ['group_id' => 1]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function removeToken(ApiTester $I)
	{
		$this->authorize($I, 'admin', 'adminpass');

		$I->sendPOST('/tokens/removeToken/', []);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/removeToken/', ['token_id' => '']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/removeToken/', ['token_id' => -1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/removeToken/', ['token_id' => 1]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function changeToken(ApiTester $I)
	{
		$this->authorize($I, 'admin', 'adminpass');

		$I->sendPOST('/tokens/changeToken/', []);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/changeToken/', ['token_id' => '', 'group_id' => '']);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/changeToken/', ['group_id' => -1, 'token_id' => -1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/changeToken/', ['group_id' => 2, 'token_id' => -1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/changeToken/', ['group_id' => 2, 'token_id' => 1]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function getTokens(ApiTester $I)
	{
		$this->authorize($I, 'admin', 'adminpass');

		$I->sendGET('/tokens/getTokens/');
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function checkUserAccess(ApiTester $I)
	{
		$this->authorize($I, 'user1', 'adminpass');

		$I->sendPOST('/tokens/createToken/', ['group_id' => 1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/removeToken/', ['token_id' => 1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendGET('/tokens/getTokens/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/tokens/changeToken/', ['group_id' => 2, 'token_id' => 1]);
		$I->seeResponseContainsJson(['success' => false]);
	}

	protected function authorize(ApiTester $I, $login, $password)
	{
		$I->sendPOST('/auth/', ['login' => $login, 'password' => $password]);
		$I->seeResponseContainsJson(['success' => true]);
	}
}