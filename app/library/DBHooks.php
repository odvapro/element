<?php

class DBHooks
{
	public function before($type, $request)
	{
		$className = $this->getHookClassName($request) . 'Hooks';

		if (!class_exists($className)) return true;
		$hooks = new $className();
		$hookName = 'before' . ucwords($type) . 'Hook';

		if (!method_exists($hooks, $hookName))
			return true;

		return $hooks->$hookName($request);
	}

	public function after($type, $request, $data)
	{
		$className = $this->getHookClassName($request) . 'Hooks';

		if (!class_exists($className)) return $data;
		$hooks = new $className();
		$hookName = 'after' . ucwords($type) . 'Hook';

		if (!method_exists($hooks, $hookName))
			return $data;

		return $hooks->$hookName($request, $data);
	}

	public function getHookClassName($request)
	{
		$table = (!empty($request['table']) ? $request['table'] : $request['from']) ?? '';
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
	}
}
