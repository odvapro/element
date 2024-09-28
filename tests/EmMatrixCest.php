<?php
class EmMatrixCest
{
	/**
	 * Проверка фильтров при связи один ко многиим
	 * без промежуточной таблицы
	 */
	public function oneToManyFilters(ApiTester $I)
	{
		// подготовка
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);
		$I->haveInDatabase('block_type_nodes', ['id'=>3,'block_type_id' => 2, 'name'=> 'Fisrt test']);
		$I->haveInDatabase('block_type_nodes', ['id'=>4,'block_type_id' => 2, 'name'=> 'Second test']);

		// фильтр проверки вхождения одного значения
		$select = [
			'select' => [
				'from' => 'block_type',
				'where' => [
					'operation' => 'and',
					'fields' => [
						['code' => 'matrix', 'operation' => 'MATRIX CONTAINS', 'value' => 3]
					],
				],
			],
		];
		$I->sendGET('/el/select', $select);
		$resp = $I->grabResponse();
		$resp = json_decode($resp,true);
		$I->assertEquals(count($resp['result']['items']),1);
		$I->assertEquals($resp['result']['items'][0]['id'],2);

		// фильтр проверки вхождения нескольних значений
		$select = [
			'select' => [
				'from' => 'block_type',
				'where' => [
					'operation' => 'and',
					'fields' => [
						['code' => 'matrix', 'operation' => 'MATRIX CONTAINS', 'value' => [3,4]]
					],
				],
			],
		];
		$I->sendGET('/el/select', $select);
		$resp = $I->grabResponse();
		$resp = json_decode($resp,true);
		$I->assertEquals(count($resp['result']['items']),1);
		$I->assertEquals($resp['result']['items'][0]['id'],2);

		// фильтр проверки не вхождения одного значения
		$select = [
			'select' => [
				'from' => 'block_type',
				'where' => [
					'operation' => 'and',
					'fields' => [
						['code' => 'matrix', 'operation' => 'MATRIX DOES NOT CONTAIN', 'value' => 3]
					],
				],
			],
		];
		$I->sendGET('/el/select', $select);
		$resp = $I->grabResponse();
		$resp = json_decode($resp,true);
		$ids = array_column($resp['result']['items'], 'id');
		$I->assertNotContains(2,$ids);

		// фильтр проверки не вхождения нескольких значений
		$select = [
			'select' => [
				'from' => 'block_type',
				'where' => [
					'operation' => 'and',
					'fields' => [
						['code' => 'matrix', 'operation' => 'MATRIX DOES NOT CONTAIN', 'value' => [3,2]]
					],
				],
			],
		];
		$I->sendGET('/el/select', $select);
		$resp = $I->grabResponse();
		$resp = json_decode($resp,true);
		$ids = array_column($resp['result']['items'], 'id');
		$I->assertNotContains(2,$ids);
		$I->assertNotContains(1,$ids);
	}

	/**
	 * Save tests
	 */
	public function oneToManySave(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->create($I, 'block_type_nodes', [[
			'block_type_id' => 1,
			'name' => 'Test Block',
		]]);

		$this->select($I, 1, 'block_type');

		$resultMatrixValue = json_decode($I->grabResponse(), true)['result']['items'][0]['matrix']['matrixValue'];

		$I->assertArrayHasKey(2, $resultMatrixValue);
		$I->assertArrayNotHasKey(3, $resultMatrixValue);
	}

	/**
	 * Select test
	 */
	public function manyToManySelect(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->select($I, 3, 'pages');

		$I->seeResponseContainsJson(['success' => true]);

		$resultMatrixValue = json_decode($I->grabResponse(), true)['result']['items'][0]['products']['matrixValue'];

		$I->assertArrayHasKey(2, $resultMatrixValue);
		$I->assertArrayNotHasKey(3, $resultMatrixValue);
	}

	/**
	 * Save tests
	 */
	public function manyToManySave(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->create($I, 'pages_products', [[
			'page_id' => 30,
			'product_id' => 20,
		]]);

		$this->select($I, 30, 'pages');

		$resultMatrixValue = json_decode($I->grabResponse(), true)['result']['items'][0]['products']['matrixValue'];

		$I->assertArrayHasKey(0, $resultMatrixValue);
		$I->assertArrayNotHasKey(1, $resultMatrixValue);
	}

	/**
	 * Filter test
	 */
	public function filter(ApiTester $I)
	{
		/*$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->select($I, 0, 'pages', [
			[
				'code' => 'products',
				'value' => [[
					'code' => 'id',
					'operation' => 'IS NOT EMPTY',
					'value' => '',
				]],
			]
		]);
		$result = json_decode($I->grabResponse(), true)['result'];

		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseContainsJson(['total_items' => 6]);*/
	}

	/**
	 * select
	 */
	protected function select(ApiTester $I, Int $id, String $table, Array $filter_fields = [])
	{
		$idFilter =  !empty($id) ? [[
			'code' => 'id',
			'operation' => 'IS',
			'value' => $id
		]] : [];
		$filter_fields = array_merge($filter_fields, $idFilter);

		$select = [
			'select' =>
			[
				'from' => $table,
				'where' =>
				[
					'operation' => 'and',
					'fields'    => $filter_fields,
				],
			],
		];

		$I->sendGET('/el/select', $select);

		$I->seeResponseContainsJson(['success' => true]);
	}

	/**
	 * create
	 */
	protected function create(ApiTester $I, String $table, Array $values)
	{
		$I->sendPOST('/el/insert/', [
			'insert' => [
				'table' => $table,
				'values' => $values,
			],
		]);
		$I->seeResponseContainsJson(['success' => true]);
	}
}
