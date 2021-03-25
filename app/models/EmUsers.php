<?php

class EmUsers extends ModelBase
{
	public $tables;
	public function initialize()
	{
		$this->hasManyToMany(
			'id',
			'EmGroupsUsers',
			'user_id', 'group_id',
			'EmGroups',
			'id',
			[
				'reusable' => true,
				'alias' => 'groups'
			]
		);
	}
	/**
	 * формирование списка групп и таблиц, к которым у пользователя есть доступ
	 */
	public function afterFetch()
	{
		$groupsIds = array_column($this->groups->toArray(), 'id');
		if (!empty($groupsIds))
			$tables = EmGroupsTables::find([
				'conditions' => 'group_id IN ({groupsIds:array})',
				'bind'       => ['groupsIds'=>$groupsIds],
				'columns'    => ['table_name', 'access'],
			])->toArray();
		else
			$tables = [];

		$this->tables = [];
		foreach ($tables as $table)
			$this->tables[$table['table_name']] = $table['access'];
	}
	public function getAvatar()
	{
		return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?d=" . urlencode( 'image.png' ) . "&s=40";
	}

	public function toArray($collumns="")
	{
		$userArr = parent::toArray();
		$userArr['avatar'] = $this->getAvatar();
		return $userArr;
	}
	public function isAdmin()
	{
		$adminGroup = array_filter($this->groups->toArray(), function($group) {
			return intval($group['id']) === Access::ADMINS_GROUP_ID;
		});

		return !empty($adminGroup);
	}
	/**
	 * проверяет, есть ли у пользователя доступ $access к таблице $tableName
	 * @param  string  $tableName
	 * @param  int     $access
	 * @return boolean
	 */
	public function hasAccess($tableName, $access)
	{
		if (!isset($this->tables[$tableName]))
			return false;

		return $this->tables[$tableName] & $access;
	}
}
