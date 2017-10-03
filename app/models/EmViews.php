<?php

class EmViews extends \Phalcon\Mvc\Model
{
	public function getUrl()
	{
		return "/table/{$this->table}/view/{$this->id}/";
	}
}
