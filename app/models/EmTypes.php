<?php

class EmTypes extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	public $table;

	/**
	 *
	 * @var string
	 */
	public $field;

	/**
	 *
	 * @var string
	 */
	public $type;

	/**
	 *
	 * @var integer
	 */
	public $required;

	/**
	 *
	 * @var integer
	 */
	public $multiple;

	/**
	 *
	 * @var string
	 */
	public $settings;

	public function getTabId()
	{
		$emNames = EmNames::find([
			'conditions' => "table=?0 AND field = ?1",
			'bind'       =>[$this->table,$this->field]
		]);
		if(!count($emNames)) return false;
		$emNames = $emNames[0];
		return $emNames->tab;
	}

}
