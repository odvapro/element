<?php

class EmTypes extends ModelBase
{
	public function getSettings()
	{
		if(empty($this->settings))
			return [];
		return json_decode($this->settings,true);
	}

	public function getRequired()
	{
		if($this->required === '1')
			return true;
		return false;
	}

}