<?php

class SettingsCest
{
	public function getFieldTypes(ApiTester $I)
	{
		$I->sendGET('/settings/getFiledTypes');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/settings/getFiledTypes');
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.types.*.code');
		$I->seeResponseJsonMatchesJsonPath('$.types.*.name');
		$I->seeResponseJsonMatchesJsonPath('$.types.*.iconPath');
		$I->seeResponseJsonMatchesJsonPath('$.types.*.type');
	}
}