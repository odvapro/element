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

	public function changeName(ApiTester $I)
	{
		$I->sendPOST('/settings/changeName');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/settings/changeName', [
			'tableName' => 'newTest',
			'field'     => 'id',
			'name'      => 'aaaaaa',
			'type'      => ''
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/settings/changeName', [
			'tableName' => 'newTest',
			'field'     => '',
			'name'      => 'aaaaaa',
			'type'      => ''
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/settings/changeName', [
			'tableName' => '',
			'field'     => 'id',
			'name'      => '',
			'type'      => 'e'
		]);
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function changeFieldType(ApiTester $I)
	{
		$I->sendPOST('/settings/changeFieldType');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/settings/changeFieldType', [
			'tableName'  => 'newTest',
			'columnName' => 'id',
			'fieldType'  => 'EmString'
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/settings/changeFieldType', [
			'tableName'  => 'newTest',
			'columnName' => '',
			'fieldType'  => 'EmString'
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/settings/changeFieldType', [
			'tableName' => 'newTest',
			'fieldType' => 'EmString'
		]);
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function setFieldSettings(ApiTester $I)
	{
		$I->sendPOST('/settings/setFieldSettings');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/settings/setFieldSettings', [
			'tableName'      => 'newTest',
			'columnName'     => 'name',
			'fieldType'      => 'em_string',
			'settings'       => [
				'required' => true
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/settings/setFieldSettings', [
			'tableName'      => 'testTable',
			'columnName'     => 'avat',
			'fieldType'      => 'em_file',
			'settings'       => [
				'required' => true,
				'path'       => 'public'
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/settings/setFieldSettings', [
			'tableName'      => 'testTable',
			'columnName'     => '',
			'fieldType'      => 'em_file',
			'settings'       => [
				'required' => true,
				'path'       => 'public'
			]
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/settings/setFieldSettings', [
			'tableName'      => 'testTable',
			'columnName'     => 'avat',
			'fieldType'      => 'em_file',
			'settings'       => [
				'required' => true,
				'path'       => 'public/upload'
			]
		]);
		$I->seeResponseContainsJson(['success' => false]);
	}
}