<?php

class ExtensionsCest
{
	public function links(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGet('/extensions/getLinks/');
		$I->seeResponseContainsJson(['success' => true]);
		$resultResp = $I->grabResponse();
		$resultResp = json_decode($resultResp,true);
		$I->assertEquals(($resultResp['links'] > 1), true);
	}

	public function code(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGet('/extensions/getCode/',['extension'=>'no_ext']);
		$I->seeResponseContainsJson(['success'=>false,'msg'=>'no Extension.js file']);

		$I->sendGet('/extensions/getCode/',['extension'=>'dev_ext']);
		$I->seeResponseContainsJson(['success' => true]);
	}
}