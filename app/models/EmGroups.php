<?php

class EmGroups extends ModelBase
{
	public function initialize()
	{
		$this->hasManyToMany('id', 'EmGroupsUsers', 'group_id', 'user_id', 'EmUsers', 'id', [
			'alias' => 'members'
		]);
	}
	public function toArray($columns='')
	{
		$groups = parent::toArray();
		$groups['members'] = [];
		foreach ($this->members as $member)
			$groups['members'][] = $member->toArray();
		return $groups;
	}
}