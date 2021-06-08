<?php

class DBHooks
{
	public $groups = null;
	protected $hooks = null;
	protected $di;

	public function __construct($groups, $di)
	{
		$this->groups = $groups;
		$this->di = $di;
	}

	/**
	 * hook-method before request
	 *
	 * @param string $type - type of hook
	 * @param array $request - request array
	 * @return bool - no error
	 */
	public function before($type, $request)
	{
		$hooks = $this->getHooks($request);

		if (empty($hooks)) return true;
		$hookName = 'before' . ucwords($type) . 'Hook';

		foreach ($hooks as $hook) {
			if (!$hook->$hookName($request))
				return false;
		}

		return true;
	}

	/**
	 * hook-method after request
	 * @param string $type - type of hook
	 * @param array $request - request array
	 * @param mixed $data - result of request
	 * @return mixed
	 */
	public function after($type, $request, $data)
	{
		$hooks = $this->getHooks($request);

		if (empty($hooks)) return $data;
		$hookName = 'after' . ucwords($type) . 'Hook';

		foreach ($hooks as $hook) {
			$data = $hook->$hookName($request, $data);
		}

		return $data;
	}

	/**
	 * returns hooks instance
	 * @param array $request
	 * @return array of hookInstace
	 */
	public function getHooks($request)
	{
		if (empty($this->hooks))
		{
			$this->hooks = [];
			$classNames = $this->getHookClassessNames($request);

			foreach ($classNames as $className) {
				if (class_exists($className))
					$this->hooks[] = new $className($this->di);
			}
		}

		return $this->hooks;
	}

	/**
	 * returns hooks classnames from its table name
	 * @param array $request
	 * @return array of strings
	 */
	public function getHookClassessNames($request)
	{
		$names = [];
		$table = (!empty($request['table']) ? $request['table'] : $request['from']) ?? '';
		$className = str_replace(' ', '', ucwords(str_replace('_', ' ', $table))) . 'Hooks';
		if (empty($className)) return $names;

		$names[] = "Element\\Hooks\\{$className}";
		foreach ($this->groups as $group)
		{
			$groupName = preg_replace('/[^a-zA-Z_0-9]/', '', $group->name);
			$names[] = "Element\\Hooks\\{$groupName}\\{$className}";
		}

		return $names;
	}
}
