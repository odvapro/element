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
			'tviewId' => 1,
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
			'tviewId' => 1
		]);
		$I->seeResponseContainsJson(['success' => true]);

		$I->sendGET('/tview/saveFilters/');
		$I->seeResponseContainsJson(['success' => false]);

		$I->sendGET('/tview/saveFilters/', [
			'tviewId' => 1,
			'filters' => [
				'operation' => 'AND'
			]
		]);
		$I->seeResponseContainsJson(['success' => true]);
	}
}