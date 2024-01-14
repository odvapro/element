<?php
class TableWorkerCest
{
	public function dbConfig(ApiTester $I)
	{
		$I->assertFileExists(__DIR__ . "/../app/config/config.php");

		$I->sendPOST('/install.php', []);
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function getTables(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/getTables');
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.code');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.name');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.filter');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.sort');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.settings');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.id');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.table');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.name');
		$I->seeResponseJsonMatchesJsonPath('$.tables.*.tviews.*.default');

		// check first table architecture
		$result = $I->grabResponse();
		$result = json_decode($result, true);
		$I->assertEquals($result['tables'][0]['code'],'block_type');
		$I->assertisArray($result['tables'][0]['columns']);
		$I->assertisArray($result['tables'][0]['columns']['date']['em']);
		$I->assertisArray($result['tables'][0]['columns']['date']['em']['collations']);
	}

	public function select(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		// тест
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['n? +ame', 'id', 'col'],
				'from' => 'test_table',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[ 'code' => 'jil', 'operation' => 'IS', 'value' => 'aa' ],
						[ 'code' => 'jil2', 'operation' => 'IS NOT', 'value' => 'ff' ],
						[
							'operation' => 'or',
							'fields' =>
							[
								[ 'code' => 'jil2', 'operation' => 'CONTAINS', 'value' => 'fa' ],
								[ 'code' => 'jil2', 'operation' => 'START WITH', 'value' => 'fa' ]
							]
						]
					]
				],
				'order' => ['name DESC']
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);


		// тест
		$I->sendGET('/el/select',
		[
			'select' => ['from' => 'test_table']
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['name', 'id', 'col'],
				'from' => 'test_table'
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$result = $I->grabResponse();
		$result = json_decode($result, true);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['name', 'id'],
				'from' => 'test_table',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[ 'code' => 'name', 'operation' => 'IS', 'value' => 'eee' ],
						[ 'code' => 'email', 'operation' => 'CONTAINS', 'value' => '3' ],
						[
							'operation' => 'or',
							'fields' =>
							[
								[ 'code' => 'avat', 'operation' => 'IS EMPTY' ]
							]
						]
					]
				],
				'order' => ['name DESC']
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['name', 'id'],
				'from' => 'test_table',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[ 'code' => 'name', 'operation' => 'IS', 'value' => 'eee' ],
						[ 'code' => 'email', 'operation' => 'DOES NOT CONTAIN', 'value' => '3' ],
						[
							'operation' => 'or',
							'fields' =>
							[
								[ 'code' => 'avat', 'operation' => 'IS', 'value' => '3' ]
							]
						]
					]
				],
				'order' => ['name DESC']
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['name', 'id'],
				'from' => 'test_table',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'col',
							'operation' => 'IS',
							'value' => '4'
						]
					]
				]
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест
		$I->sendGET('/el/select');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		// тест
		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'fields' => ['name', 'id', 'col'],
				'from' => 'test_table',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'col',
							'operation' => 'IS',
							'value' => '4'
						]
					]
				]
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест
		$I->sendGET('/el/select',
		[
			'select' => ['from' => 'test_table']
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');

		// тест на лимит
		$I->sendGET('/el/select',
		[
			'select' => ['from' => 'products', 'limit'=>10],
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseJsonMatchesJsonPath('$.result.items');
		$resultResp = $I->grabResponse();
		$resultResp = json_decode($resultResp,true);
		$I->assertEquals(($resultResp['result']['total_pages'] > 1), true);
	}

	public function update(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/update/',
		[
			'update' =>
			[
				'table' => 'test_table',
				'set' => [
					'email' => 3,
					'col'   => 5
				],
				'where' => [
					'operation' => 'and',
					'fields' =>
					[
						['code' => 'name', 'operation' => 'IS', 'value' => 'eee'],
						['code' => 'email', 'operation' => 'DOES NOT CONTAIN', 'value' => '3'],
						[
							'operation' => 'or',
							'fields' => [['code' => 'avat', 'operation' => 'IS', 'value' => '3'] ]
						]
					]
				],
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/update/', [
			'update' => [
				'table' => 'test_table',
				'set' => [
					'email' => "rr'rrr",
					'col'   => '222222'
				],
				'where' => [
					'operation' => 'and',
					'fields' => [
						['code' => 'id', 'operation' => 'IS', 'value' => 2 ]
					]
				]
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);
		$I->seeInDatabase('test_table', ['id' => 2, 'email' => "rr'rrr", 'col'=>'222222']);

		$I->sendPOST('/el/update/',
		[
			'update' =>
			[
				'table' => 'test_table',
				'set' => ["name"=> 'ggапфффыввфывg' ],
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						['code' => 'name', 'operation' => 'CONTAINS', 'value' => 'ggg'],
						[
							'operation' => 'or',
							'fields' => [['code' => 'avat', 'operation' => 'IS', 'value' => '3'] ]
						]
					]
				]
			]
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/update/', [
			'update' => ['table' => 'test_table']
		]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/update/', ['update' => [] ]);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/update/');
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function insert(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [[
					'name'  => '11',
					'email' => 'qwe',
					'col'   => '222222',
					'avat'  => "222211'211",
				]],
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [
					[
						'name'  => '11',
						'email' => 'qwe',
						'col'   => '222222',
						'avat'  => '222211211',
					],
					[
						'name'  => '22',
						'email' => 'qwe@a.a',
						'col'   => '43243',
						'avat'  => '655310',
					],
				],
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [
					'name' => '11',
					'avat' => 'qwe',
					'222222',
					'222211211',
				],
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [[
					'name' => '11',
					'avat' => 'qwe',
					'222222',
					'222211211',
				]],
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [[
					'name' => '11',
					'avat' => 'qwe',
					'222222',
					'222211211'
				]],
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [['33', 'qwe', '222222', '222211211']]
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' =>
			[
				'table' => 'test_table',
				'values' => [['44']]
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' => ['table' => 'test_table']
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/',
		[
			'insert' => []
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/insert/');

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function delete(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/delete/',
		[
			'delete' =>
			[
				'table' => 'test_table',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'name',
							'operation' => 'CONTAINS',
							'value' => 'ggg'
						]
					]
				]
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/delete/',
		[
			'delete' =>
			[
				'table' => 'test_table',
				'where' =>
				[
					'operation' => 'or',
					'fields' =>
					[
						['code' => 'name', 'operation' => 'CONTAINS', 'value' => 'ggg'],
						['code' => 'email', 'operation' => 'IS', 'value' => '1']
					]
				]
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/delete/',
		[
			'delete' =>
			[
				'table' => 'products',
				'where' =>
				[
					'operation' => 'or',
					'fields' =>
					[
				        ['code' => 'id', 'operation' => 'IS', 'value' => 20, ],
				        ['code' => 'id', 'operation' => 'IS', 'value' => 21, ],
				        ['code' => 'id', 'operation' => 'IS', 'value' => 22, ]
					]
				]
			]
		]);
		$I->dontSeeInDatabase('products', ['id' => 20]);
		$I->dontSeeInDatabase('products', ['id' => 21]);
		$I->dontSeeInDatabase('products', ['id' => 22]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/delete/', ['delete' => ['table' => 'test_table'] ]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/delete/', ['delete' => [] ]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/delete/');
		$I->seeResponseContainsJson(['success' => false]);
	}

	public function duplicate(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'from' => 'callbacks',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'id',
							'operation' => 'IS',
							'value' => '33'
						]
					]
				]
			]
		]);
		$I->seeResponseContainsJson(["success" => true]);
		$result = json_decode($I->grabResponse(), 1);

		if (!empty(count($result['result']['items'])))
			throw new EmException("Element already exist", 1);



		$I->sendPOST('/el/duplicate/',
		[
			'duplicate' =>
			[
				'from' => 'non-exist',
			]
		]);
		$I->seeResponseContainsJson(['success' => false]);


		$I->sendPOST('/el/duplicate/',
		[
			'duplicate' => []
		]);
		$I->seeResponseContainsJson(['success' => false]);


		$I->sendPOST('/el/duplicate/',
		[
			'duplicate' =>
			[
				'from' => 'callbacks',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'id',
							'operation' => 'IS',
							'value' => '3200'
						]
					]
				]
			]
		]);
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/el/duplicate/',
		[
			'duplicate' =>
			[
				'from' => 'callbacks',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'id',
							'operation' => 'IS',
							'value' => '32'
						]
					]
				]
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/el/select',
		[
			'select' =>
			[
				'from' => 'callbacks',
				'where' =>
				[
					'operation' => 'and',
					'fields' =>
					[
						[
							'code' => 'id',
							'operation' => 'IS',
							'value' => '33'
						]
					]
				]
			]
		]);
		$I->seeResponseContainsJson(["success" => true]);
		$result = json_decode($I->grabResponse(), 1);

		if (empty(count($result['result']['items'])))
			throw new EmException("Element wasn't created", 1);

	}

	public function setTviewSettings(ApiTester $I)
	{
		$I->sendPOST('/auth/', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/setTviewSettings/',
		[
			'tviewId' => '21',
			'params' =>
			[
				'columns' => ['id' => [ 'width' => 300 ], 'name' => [ 'width' => 600 ] ]
			]
		]);

		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendPOST('/el/setTviewSettings/', []);
		$I->seeResponseCodeIs(200);
		$I->seeResponseContainsJson(['success' => false]);
	}
}
