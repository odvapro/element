<?php


class ElController extends ControllerBase
{
	public function getTablesAction()
	{
		$tables = $this->eldb->getTables();
		$this->jsonResult([
			'success' => true,
			'tables'  => $tables
		]);
	}
}
