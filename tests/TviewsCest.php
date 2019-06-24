<?php

class TviewsCest
{
	public function saveFilters(ApiTester $I)
	{
		$I->sendGET('/tview/saveFilters/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveFilters/', [
			'tviewId' => 21,
			'filters' => [
				'operation' => 'AND',
				'fields' => [
					'value' => 2,
					'operation' => 'IS',
					'code' => 'id'
				]
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveFilters/', [
			'tviewId' => 21
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveFilters/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendGET('/tview/saveFilters/', [
			'tviewId' => 21,
			'filters' => [
				'operation' => 'AND'
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);
	}
	public function saveSort(ApiTester $I)
	{
		$I->sendGET('/tview/saveSort/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendPOST('/auth', ['login' => 'admin', 'password' => 'adminpass']);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveSort/', [
			'tviewId' => 21,
			'sort' => [
				'name DESC', 'id ASC'
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveSort/', [
			'tviewId' => 21
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveSort/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendGET('/tview/saveSort/', [
			'tviewId' => 21,
			'sort' => []
		]);
		$I->seeResponseContainsJson(['success' => true]);
	}
}