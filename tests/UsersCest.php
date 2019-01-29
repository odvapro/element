<?php

class UsersCest
{
	public function getUsers(ApiTester $I)
	{
		$I->sendGET('/users/getUsers');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/users/getUsers');
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.users.*.id');
	}
	public function addUser(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/users/addUser',
		[
			'login' => 'user',
			'password' => 'pass',
			'repassword' => 'pass',
			'name' => 'ddd',
			'email' => 'kisieva@gmail.com'
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/users/addUser',
		[
			'login' => 'user',
			'password' => 'pass',
			'repassword' => 'pasdads',
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/users/addUser',
		[
			'login' => 'user',
			'password' => 'pass',
			'repassword' => 'pasdads',
			'name' => 'ddd',
			'email' => 'kisieva@gmail.com'
		]);
		$I->seeResponseContainsJson(['success' => false]);
	}
	public function getUser(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/users/getUser');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendGET('/users/getUser', ['id' => 1]);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.user.id');
	}

	public function deleteUser(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/users/deleteUser');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/users/deleteUser', ['id' => 1]);
		$I->seeResponseContainsJson(['success' => true]);
	}

	public function updateUser(ApiTester $I)
	{
		$I->sendPOST('/users/updateUser', ['id' => 1]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/users/updateUser', ['id' => 1]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/users/updateUser');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/users/updateUser',
		[
			'id' => 2,
			'login' => 'dsfdsfsdf',
			'email' => 'kisiev@mail.ru',
			'password' => 'red',
			'repassword' => 'red'
		]);

		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/users/updateUser',
		[
			'id' => 3,
			'login' => 'dsfdsfsdf',
			'email' => 'kisiev@mail.ru',
			'password' => 'red',
			'repassword' => 'redfasf'
		]);

		$I->seeResponseContainsJson(['success' => false]);
	}

	// public function getGroups(ApiTester $I)
	// {

	// 	$I->sendGET('/users/getGroups');
	// 	$I->seeResponseContainsJson(['success' => false]);

	// 	$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
	// 	$I->seeResponseContainsJson(['success' => true]);

	// 	$I->sendGET('/users/getGroups');
	// 	$I->seeResponseContainsJson(['success' => true]);
	// 	$I->seeResponseJsonMatchesJsonPath('$.groups.*.id');
	// }

	// public function getGroup(ApiTester $I)
	// {
	// 	$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
	// 	$I->seeResponseContainsJson(['success' => true]);

	// 	$I->sendGET('/users/getGroup');
	// 	$I->seeResponseContainsJson(['success' => false]);

	// 	$I->sendGET('/users/getGroup', ['id' => 1]);
	// 	$I->seeResponseContainsJson(['success' => true]);
	// }
}