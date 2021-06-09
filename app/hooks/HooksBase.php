<?php
namespace Element\Hooks;

class HooksBase
{
	protected $di;

	public function __construct($di)
	{
		$this->di = $di;
	}

	protected function getDi()
	{
		return $this->di;
	}

	public function beforeDeleteHook($request)
	{
		return true;
	}
	public function afterDeleteHook($request, $result)
	{
		return $result;
	}


	public function beforeUpdateHook($request)
	{
		return true;
	}
	public function afterUpdateHook($request, $result)
	{
		return $result;
	}


	public function beforeInsertHook($request)
	{
		return true;
	}
	public function afterInsertHook($request, $result)
	{
		return $result;
	}


	public function beforeSelectHook($request)
	{
		return true;
	}
	public function afterSelectHook($request, $result)
	{
		return $result;
	}


	public function beforeDuplicateHook($request)
	{
		return true;
	}
	public function afterDuplicateHook($request, $result)
	{
		return $result;
	}


	public function beforeCountHook($request)
	{
		return true;
	}
	public function afterCountHook($request, $result)
	{
		return $result;
	}
}
