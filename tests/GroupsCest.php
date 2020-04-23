<?php
class GroupsCest
{
	public function getGroups(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/groups/get/');
		$I->seeResponseContainsJson(['success' => true]);
		$result = $I->grabResponse();
		$result = json_decode($result, true);
		$I->assertEquals($result['groups'][0]['name'],'Administrators');
	}

	public function addGroup(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/groups/add/');
		$I->seeResponseContainsJson(['success' => true]);
		$result = $I->grabResponse();
		$result = json_decode($result, true);
		$I->assertEquals($result['group']['id'],3);
		$I->seeInDatabase('em_groups', ['id' => 3, 'name' => 'Untiteled']);
	}

	public function removeGroup(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/groups/remove/',['id'=>99]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/groups/remove/',['id'=>2]);
		$I->seeResponseContainsJson(['success' => true]);
		$I->dontSeeInDatabase('em_groups', ['id' => 2]);
		$I->dontSeeInDatabase('em_groups_users', ['group_id' => 2]);
	}

	public function updateGroup(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/groups/update/',['id' => 2, 'name' => 'Managers']);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('em_groups', ['id' => 2,'name'=>'Managers']);
	}
}