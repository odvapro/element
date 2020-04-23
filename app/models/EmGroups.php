<?php

class EmGroups extends ModelBase
{
	public function initialize()
	{
		$this->hasManyToMany('id', 'EmGroupsUsers', 'group_id', 'user_id', 'EmUsers', 'id', [
			'alias' => 'members'
		]);
	}

	public function beforeDelete()
	{
		// delete all relations with this group
		$groupRelations = EmGroupsUsers::findByGroupId($this->id);
		if(count($groupRelations))
			$groupRelations->delete();
	}

	public function toArray($columns='')
	{
		$groups = parent::toArray();
		$groups['members'] = [];
		foreach ($this->members as $member)
			$groups['members'][] = [
				'name'   => $member->name,
				'avatar' => $member->getAvatar(),
			];
		return $groups;
	}
}