<?php
class TableWorkerCest
{
	public function getTables(ApiTester $I)
	{
		$I->sendGET('/el/getTables');
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();

		$I->seeResponseContainsJson(['success' => true, 'tables'=>
			[
				[
					'code' => 'testTable',
					'name' => false
				]
			]
		]);
	}

	public function getColumns(ApiTester $I)
	{
		$I->sendGET('/el/getColumns');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false, 'msg'=>'No table']);

		$I->sendGET('/el/getColumns',[
			'table'=>'errorTable'
		]);
		$I->seeResponseContainsJson(['success' => false, 'msg'=>'No such table']);

		$I->sendGET('/el/getColumns',[
			'table'=>'testTable'
		]);
		$I->seeResponseContainsJson(['success' => true, 'columns'=>
			[
				[
					'code'    => 'id',
					'name'    => false,
					'primary' => true,
				],
				[
					'code'    => 'name',
					'name'    => false,
					'primary' => false
				]
			]
		]);
	}
}