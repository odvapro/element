<?php

class EmGroups extends ModelBase
{
	public static function getAdminsGroup()
	{
		return self::findFirst(Access::ADMINS_GROUP_ID);
	}
	public function initialize()
	{
		$this->hasManyToMany('id', 'EmGroupsUsers', 'group_id', 'user_id', 'EmUsers', 'id', [
			'alias' => 'members'
		]);
		$this->hasMany('id', 'EmGroupsTables', 'group_id',[
			'alias' => 'access'
		]);
	}

	public function isAdminGroup()
	{
		return intval($this->id) === Access::ADMINS_GROUP_ID;
	}

	/**
	 * check if group has access $access to table $tableName
	 * @param string $tableName
	 * @param int $access
	 * @return bool
	 */
	public function hasAccess($tableName, $access)
	{
		foreach ($this->access as $accessInfo)
			if ($accessInfo->table_name === $tableName && (intval($access) & intval($accessInfo->access)))
				return true;

		return false;
	}

	/**
	 * проверяет доступ $accessValue (read, write,..) к таблице $tableName
	 * @param  string $tableName   название таблицы
	 * @param  int    $accessValue значение доступа - константа класса
	 * @return boolean             есть ли доступ к таблице у группы
	 */
	public function checkAccessToTable($tableName, $accessValue)
	{
		if (intval($this->id) === Access::ADMINS_GROUP_ID)
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
		$group = parent::toArray();

		$group['members'] = [];
		foreach ($this->members as $member)
			$group['members'][] = [
				'id'     => $member->id,
				'name'   => $member->name,
				'avatar' => $member->getAvatar(),
			];

		$group['access_info'] = [];
		foreach ($this->access as $access)
			$group['access_info'][] = [
				'id'         => $access->id,
				'table_name' => $access->table_name,
				'access'     => $access->access,
			];

		$group['is_admin'] = intval($this->id) === Access::ADMINS_GROUP_ID;

		return $group;
	}
}
