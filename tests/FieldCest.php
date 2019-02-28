<?php

class FieldCest
{
	public function upload(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/field/em_file/index/upload', [
			'fieldCode'       => 'images',
			'tableCode'       => 'products',
			'typeUpload'      => 'link',
			'primaryKey'      => 'id',
			'primaryKeyValue' => '20',
			'link'            => 'https://st2.depositphotos.com/2627021/6665/i/450/depositphotos_66650665-stock-photo-romantic-and-scenic-panorama-with.jpg'
		]);

		$I->seeResponseContainsJson(['success' => true]);
		// $I->seeResponseJsonMatchesJsonPath('$.files.*.path');
		// $I->seeResponseJsonMatchesJsonPath('$.files.*.thumb');
	}
}