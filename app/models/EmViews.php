<?php

class EmViews extends \Phalcon\Mvc\Model
{
	public function getUrl()
	{
		$baseUri = $this->di->get('config')->application->baseUri;
		return "{$baseUri}table/{$this->table}/view/{$this->id}/";
	}
}
