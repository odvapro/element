<?php

class EmTokens extends ModelBase
{
	public function initialize()
	{
		$this->hasOne('group_id', 'EmGroups', 'id',[
			'alias' => 'group'
		]);
	}

	public function toArray($columns='')
	{
		$token = parent::toArray();
		$token['group_name'] = $this->group->name;

		return $token;
	}
}
