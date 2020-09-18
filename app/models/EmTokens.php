<?php

class EmTokens extends ModelBase
{
	public function toArray($columns='')
	{
		$token = parent::toArray();
		$token['group_name'] = EmGroups::findFirst($token['group_id'])->name;

		return $token;
	}
}