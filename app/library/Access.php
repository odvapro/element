<?php
use Phalcon\Di;

class Access
{
	protected $di;
	const ADMIN_ID = 1;

	public function __construct($di)
	{
		$this->di = $di;
	}

	/**
	 * проверяет доступ к таблице
	 * @param  string $tableName   название таблицы
	 * @param  int    $accessValue константа класса EmGroups
	 * @return bool
	 */
	public function checkTableAccess($tableName, $accessValue)
	{
		$userId = Di::getDefault()->get('session')->get('auth');

		$groups = $this->getGroupsByUserId($userId);

		foreach ($groups as $group)
		{
			if ( !empty($group->checkTableAccess($tableName, $accessValue)) )
				return true;
		}
		return false;
	}

	/**
	 * проверяет, является ли текущий пользователь администратором
	 * @return [type] [description]
	 */
	public function checkAuthAdmin()
	{
		if (!$this->isAdmin(Di::getDefault()->get('session')->get('auth')))
			return false;
		return true;
	}

	/**
	 * возвращает массив доступов таблицы [{access,group_id}]
	 * @param  string $tableName название таблицы
	 * @return array
	 */
	public function getAccessTable($tableName)
	{
		$accessData = EmGroupsTables::find([
			'conditions' => 'table_name = ?0',
			'bind'       => [$tableName],
			'columns'    => 'group_id, access, table_name'
		])->toArray();

		return $accessData;
	}

	public function isAdmin($userId)
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
		$groups = EmGroups::find([
			'conditions' => 'id IN ({groupsIds:array})',
			'bind'       => ['groupsIds'=>$groupsIds]
		]);

		return $groups;
	}
}