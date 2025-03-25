<?php

namespace Element\Controllers;



class Controller
{
	protected ContainerBuilder $di;

	public function __construct(ContainerBuilder $di)
	{
		$this->di = $di;
	}
}