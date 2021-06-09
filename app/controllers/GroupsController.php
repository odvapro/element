<?php

class GroupsController extends ControllerBase
{
	/**
	 * get all groups
	 * @return array
	 */
	public function getAction()
	{
		$groups = [];
		foreach (EmGroups::find() as $group)
			$groups[] = $group->toArray();

		return $this->jsonResult(['success' => true, 'groups' => $groups]);
	}

	/**
	 * adding new group
	 * @return array
	 */
	public function addAction()
	{
		$group = new EmGroups();
		$group->name = 'Untiteled';
		$group->save();
		return $this->jsonResult(['success' => true, 'group' => $group]);
	}

	/**
	 * adding new user to group
	 * @param int id user id
	 * @param int group group id
	 * @return array
	 */
	public function addUserAction()
	{
		$userId = $this->request->getPost('id');
		$groupId = $this->request->getPost('group');
		if(empty($groupId) || empty($userId))
			return $this->jsonResult(['success' => false,'msg'=>'no group id or user id']);

		$records = EmGroupsUsers::find([
			'conditions'=>"group_id = ?0 AND user_id = ?1",
			'bind'=>[$groupId,$userId]
		]);
		if(count($records))
			return $this->jsonResult(['success' => false,'msg'=>'User already in group']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success' => false,'msg'=>'No such group']);

		$record = new EmGroupsUsers();
		$record->group_id = $groupId;
		$record->user_id = $userId;
		if(!$record->save())
			return $this->jsonResult(['success' => false,'msg'=>'Something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	/**
	 * removing user from group
	 * @param int id user id
	 * @param int group group id
	 * @return array
	 */
	public function removeUserAction()
	{
		$userId = $this->request->getPost('id');
		$groupId = $this->request->getPost('group');
		if(empty($groupId) || empty($userId))
			return $this->jsonResult(['success' => false,'msg'=>'no group id or user id']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success' => false,'msg'=>'No such group']);

		$records = EmGroupsUsers::find([
			'conditions'=>"group_id = ?0 AND user_id = ?1",
			'bind'=>[$groupId,$userId]
		]);
		if(!count($records))
			return $this->jsonResult(['success' => false,'msg'=>'User alreadey out of group']);

		if(!$records->delete())
			return $this->jsonResult(['success' => false,'msg'=>'Something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	/**
	 * removing group
	 * @param int id group id
	 * @return array
	 */
	public function removeAction()
	{
		$groupId = intval($this->request->getPost('id'));
		if ($groupId === Access::ADMINS_GROUP_ID)
			return $this->jsonResult(['success' => false, 'msg' => 'unable to delete admin group']);

		if(empty($groupId))
			return $this->jsonResult(['success' => false,'msg'=>'no group id']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success' => false,'msg'=>'group not found']);

		if(!$group->delete())
			return $this->jsonResult(['success' => false,'msg'=>'something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	/**
	 * update group name
	 * @param int id group id
	 * @param string name new name
	 * @return array
	 */
	public function updateAction()
	{
		$groupId = intval($this->request->getPost('id'));
		$name    = $this->request->getPost('name');

		if ($groupId === Access::ADMINS_GROUP_ID)
			return $this->jsonResult(['success' => false, 'msg' => 'unable to rename admin group']);

		if(empty($groupId) || empty($name))
			return $this->jsonResult(['success' => false,'msg'=>'no group id or name']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success' => false,'msg'=>'group dont find']);

		$group->name = $name;
		if(!$group->update())
			return $this->jsonResult(['success' => false,'msg'=>'something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	/**
	 * gives data about access rights in the form [{title, value}, ..]
	 * @return json
	 */
	public function getAccessOptionsAction()
	{
		$lang = 'en';
		if (!empty($this->user))
			$lang = $this->user->language;

		$translates = file_get_contents(__DIR__ . "/locale/{$lang}.json");
		$translates = json_decode($translates, true);

		$result =
		[
			[
				'title'    => $translates['read'],
				'value'    => Access::READ,
				'strValue' => 'READ',
			],
			[
				'title'    => $translates['full'],
				'value'    => Access::FULL,
				'strValue' => 'FULL',
			],
		];

		return $this->jsonResult(['success'=>true, 'options' => $result]);
	}
	/**
	 * specifies access to a table
	 * @param  string $accessStr access is passed by the string-name of the constant
	 * @param  int    $groupId
	 * @param  string $tableName
	 * @return json
	 */
	public function setGroupAccessAction()
	{
		$accessStr = $this->request->getPost('accessStr');
		$groupId   = intval($this->request->getPost('groupId'));
		$tableName = $this->request->getPost('tableName');

		if (empty($accessStr)
			|| empty(constant("Access::$accessStr"))
			|| empty($groupId)
			|| empty($tableName)
			|| $groupId === Access::ADMINS_GROUP_ID)
			return $this->jsonResult(['success' => false]);

		$relation = EmGroupsTables::findFirst([
			'conditions' => 'group_id = ?0 AND table_name =?1',
			'bind'       => [$groupId, $tableName]
		]);

		if (empty($relation))
			$relation = new EmGroupsTables();

		$relation->group_id   = $groupId;
		$relation->table_name = $tableName;
		$relation->access     = constant("Access::$accessStr");
		$relation->save();

		return $this->jsonResult(['success' => true]);
	}
	/**
	 * disables access to the table for all groups
	 * @param  string $tableName
	 * @return json
	 */
	public function disableGroupsAccessAction()
	{
		$tableName = $this->request->getPost('tableName');

		if (empty($tableName))
			return $this->jsonResult(['success' => false, 'message' => 'empty table name']);

		$groupsTables = EmGroupsTables::find([
			'conditions' => 'table_name = ?0',
			'bind'       => [$tableName]
		]);

		$success = true;
		foreach ($groupsTables as $groupTable)
			$groupTable->delete();

		return $this->jsonResult(['success' => true]);
	}
}
