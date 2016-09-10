<?php

class IndexController extends ControllerBase
{

	public function indexAction()
	{
		
	}

	public function testAction()
	{
		/*$emName = EmNames::find([
			'conditions'=>'table = ?1 AND type = 0',
			'bind' => [1 => 'test_table']
		]);
		// $emName = $emName[0];
		echo $emName[0]->table;
		// echo $emName->table;*/
		print_r($this->fields);
		exit();
	}

}

