<?php

class GroupsController extends ControllerBase
{
	public function getAction()
	{
		$groups = EmGroups::find();
		return $this->jsonResult(['success' => true, 'groups' => $groups]);
	}

	public function addAction()
	{
		$group = new EmGroups();
		$group->name = 'Untiteled';
		$group->save();
		return $this->jsonResult(['success' => true, 'group' => $group]);
	}

	public function addUserAction()
	{
		$userId = $this->request->getPost('id');
		$groupId = $this->request->getPost('group');
		if(empty($groupId) || empty($userId))
			return $this->jsonResult(['success'=>false,'msg'=>'no group id or user id']);

		$records = EmGroupsUsers::find([
			'conditions'=>"group_id = ?0 AND user_id = ?1",
			'bind'=>[$groupId,$userId]
		]);
		if(count($records))
			return $this->jsonResult(['success'=>false,'msg'=>'User alreadey in group']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success'=>false,'msg'=>'No such group']);

		$record = new EmGroupsUsers();
		$record->group_id = $groupId;
		$record->user_id = $userId;
		if(!$record->save())
			return $this->jsonResult(['success'=>false,'msg'=>'Something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	public function removeUserAction()
	{
		$userId = $this->request->getPost('id');
		$groupId = $this->request->getPost('group');
		if(empty($groupId) || empty($userId))
			return $this->jsonResult(['success'=>false,'msg'=>'no group id or user id']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success'=>false,'msg'=>'No such group']);

		$records = EmGroupsUsers::find([
			'conditions'=>"group_id = ?0 AND user_id = ?1",
			'bind'=>[$groupId,$userId]
		]);
		if(!count($records))
			return $this->jsonResult(['success'=>false,'msg'=>'User alreadey out of group']);


		if(!$records->delete())
			return $this->jsonResult(['success'=>false,'msg'=>'Something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	public function removeAction()
	{
		$groupId = $this->request->getPost('id');
		if(empty($groupId))
			return $this->jsonResult(['success'=>false,'msg'=>'no group id']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success'=>false,'msg'=>'group dont find']);

		if(!$group->delete())
			return $this->jsonResult(['success'=>false,'msg'=>'something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

	public function updateAction()
	{
		$groupId = $this->request->getPost('id');
		$name    = $this->request->getPost('name');
		if(empty($groupId) || empty($name))
			return $this->jsonResult(['success'=>false,'msg'=>'no group id or name']);

		$group = EmGroups::findFirstById($groupId);
		if(!$group)
			return $this->jsonResult(['success'=>false,'msg'=>'group dont find']);

		$group->name = $name;
		if(!$group->update())
			return $this->jsonResult(['success'=>false,'msg'=>'something goes wrong']);

		return $this->jsonResult(['success'=>true]);
	}

}