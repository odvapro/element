<?php
class EmMatrixCest
{
	/**
	 * Select test
	 */
	public function oneToManySelect(ApiTester $I)
	{
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->select($I, 1, 'block_type');

		$I->seeResponseContainsJson(['success' => true]);

		$resultMatrixValue = json_decode($I->grabResponse(), true)['result']['items'][0]['matrix']['matrixValue'];

		$I->assertArrayHasKey(1, $resultMatrixValue);
		$I->assertArrayNotHasKey(2, $resultMatrixValue);
	}

	/**
	 * Save tests
	 */
	public function oneToManySave(ApiTester $I)
	{
		// Авторизуемся
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->create($I, 'block_type_nodes', [
			'block_type_id' => 1,
			'name' => 'Test Block',
		]);

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

		$this->create($I, 'pages_products', [
			'page_id' => 30,
			'product_id' => 20,
		]);

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
		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$this->select($I, 0, 'pages', [
			[
				'code' => 'products',
				'operation' => 'IS NOT EMPTY',
				'value' => [
					'field' => [
						'code' => 'id',
						'type' => 'em_primary',
					],
					'value' => '',
				],
			]
		]);
		$result = json_decode($I->grabResponse(), true)['result'];

		$I->seeResponseContainsJson(['success' => true]);
		$I->seeResponseContainsJson(['total_items' => 6]);
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
