<?php

class EmUsers extends ModelBase
{
	public function getAvatar()
	{
		return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?d=" . urlencode( 'image.png' ) . "&s=40";
	}

	public function toArray($collumns="")
	{
		$userArr = parent::toArray();
		$userArr['avatar'] = $this->getAvatar();
		return $userArr;
	}
}