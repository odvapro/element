<?php

class EmUsers extends ModelBase
{
	public function toArray($collumns="")
	{
		$userArr = parent::toArray();
		$userArr['avatar'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?d=" . urlencode( 'image.png' ) . "&s=40";
		return $userArr;
	}
}