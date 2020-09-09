<?php

class EmGroups extends ModelBase
{
	const ACCESS_READ  = 1;
	const ACCESS_WRITE = 2;
	const ACCESS_FULL  = self::ACCESS_READ | self::ACCESS_WRITE;
	const ADMIN_ID     = 1;

	/**
	 * находит все группы пользователя по его id
	 * @param  int    $userId
	 * @return array
	 */
	public static function getGroupsByUserId($userId)
	{
		$groupRelations = EmGroupsUsers::find([
			'conditions' => 'user_id = ?0',
			'bind'       => [$userId]
		]);

		if (empty($groupRelations))
			return [];

		$groupsIds = array_column($groupRelations->toArray(), 'group_id');
		$groups = self::find([
			'conditions' => 'id IN ({groupsIds:array})',
			'bind'       => ['groupsIds'=>$groupsIds]
		]);

		return $groups;
	}
	/**
	 * проверяет, относится ли пользоваетль к группе администраторов
	 * @param  int $userId
	 * @return boolean
	 */
	public static function isAdmin($userId)
	{
		$isAdmin = false;
		$groupRelations = EmGroupsUsers::find([
			'conditions' => 'user_id = ?0',
			'bind'       => [$userId]
		]);
		foreach ($groupRelations as $group)
			if (intval($group->id) === self::ADMIN_ID)
			{
				$isAdmin = true;
				break;
			}

		return $isAdmin;
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


	/**
	 * проверяет доступ $accessValue (read, write,..) к таблице $tableName
	 * @param  string $tableName   название таблицы
	 * @param  int    $accessValue значение доступа - константа класса
	 * @return boolean             есть ли доступ к таблице у группы
	 */
	public function checkTableAccess($tableName, $accessValue)
	{
		if (intval($this->id) === self::ADMIN_ID)
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