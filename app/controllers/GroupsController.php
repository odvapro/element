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