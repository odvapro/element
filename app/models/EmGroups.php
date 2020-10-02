<?php

class EmGroups extends ModelBase
{
	public function initialize()
	{
		$this->hasManyToMany('id', 'EmGroupsUsers', 'group_id', 'user_id', 'EmUsers', 'id', [
			'alias' => 'members'
		]);
		$this->hasMany('id', 'EmGroupsTables', 'group_id',[
			'alias' => 'access'
		]);
	}

	/**
	 * проверяет доступ $accessValue (read, write,..) к таблице $tableName
	 * @param  string $tableName   название таблицы
	 * @param  int    $accessValue значение доступа - константа класса
	 * @return boolean             есть ли доступ к таблице у группы
	 */
	public function checkAccessToTable($tableName, $accessValue)
	{
		if (intval($this->id) === Access::ADMIN_ID)
			return true;

		foreach ($this->access->toArray() as $accessInfo)
			if ($accessInfo['table_name'] === $tableName)
				return !empty($accessInfo['access'] & $accessValue);

		return false;
	}

	public function beforeDelete()
	{
		// delete all relations with this group
		$groupRelations = EmGroupsUsers::findByGroupId($this->id);
		if(count($groupRelations))
			$groupRelations->delete();

		// delete all access relations with tables
		$groupAccesses = EmGroupsTables::findByGroupId($this->id);
		if(count($groupAccesses))
			$groupAccesses->delete();
	}

	public function toArray($columns='')
	{
		$groups = parent::toArray();
		$groups['members'] = [];
		foreach ($this->members as $member)
			$groups['members'][] = [
				'id'     => $member->id,
				'name'   => $member->name,
				'avatar' => $member->getAvatar(),
			];

		$groups['access_info'] = [];
		foreach ($this->access as $access)
			$groups['access_info'][] = [
				'id'         => $access->id,
				'table_name' => $access->table_name,
				'access'     => $access->access,
			];

		return $groups;
	}
}