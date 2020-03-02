<?php

class GroupsController extends ControllerBase
{
	public function getGroupsAction()
	{
		$groups = EmGroups::find();
		return $this->jsonResult(['success' => true, 'groups' => $groups]);
	}
}