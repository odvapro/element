<?php
/**
* Абстрактный класс типа отоброжения
*/
abstract class TviewBase extends Phalcon\Mvc\User\Plugin
{
	public $tView = false;
	public function setView($tView)
	{
		$this->tView = $tView;
	}
	public $tableInfo = false;
	public function setTableInfo($tableInfo)
	{
		$this->tableInfo = $tableInfo;
	}
	public function makeViewLogic(){}
}
