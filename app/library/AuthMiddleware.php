<?php
use \Phalcon\Mvc\Dispatcher as PhDispatcher;

class AuthMiddleware extends Phalcon\Mvc\User\Plugin
{
	/**
	 * @var Phalcon\Acl\Adapter\Memory
	 */
	protected $_acl;
	/**
	 * __construct принимает di
	 */
	public function __construct($dependencyInjector)
	{
		$this->_dependencyInjector = $dependencyInjector;
	}
	/**
	 * Ограничить доступ для определенных контроллеров
	 */
	public function getAcl()
	{
		if(!$this->_acl)
		{
			$acl = new Phalcon\Acl\Adapter\Memory();
			$acl->setDefaultAction(Phalcon\Acl::DENY);
			$acl->setNoArgumentsDefaultAction(Phalcon\Acl::DENY);

			$acl = $this->registerRolesAndResources($acl);
			$acl = $this->setResourcesAccess($acl);

			$this->_acl = $acl;
		}
		return $this->_acl;
	}

	public function setResourcesAccess($acl)
	{
		$roles = $this->getRoles();
		$resources = $this->getResources();

		//Grant access to public areas to both users and guests
		foreach($roles as $role)
			foreach($resources['public'] as $resource)
				$acl->allow($role->getName(), $resource, '*');

		//Grant acess to private area to role Users
		foreach($resources['private'] as $resource => $actions)
		{
			foreach($actions as $actionName => $action)
			{
				$acl->allow('Admins', $resource, $actionName);

				if ( !empty($action) )
					$acl->allow('Users', $resource, $actionName, $action);
				else
					$acl->allow('Users', $resource, $actionName);
			}
		}

		foreach($resources['admin'] as $resourceCode => $resource)
		{
			foreach($resource as $action)
				$acl->allow('Admins', $resourceCode, $action);
		}

		return $acl;
	}

	public function registerRolesAndResources($acl)
	{
		//Register roles
		$roles = $this->getRoles();
		foreach($roles as $role)
			$acl->addRole($role);

		$resources = $this->getResources();

		// add public resources
		foreach ($resources['public'] as $resource)
			$acl->addResource(new Phalcon\Acl\Resource($resource), '*');

		// add private resources
		foreach ($resources['private'] as $resourceName => $resource)
			$acl->addResource(new Phalcon\Acl\Resource($resourceName), array_keys($resource));

		// add admin resources
		foreach ($resources['admin'] as $resourceName => $resource)
			$acl->addResource(new Phalcon\Acl\Resource($resourceName), $resource);

		return $acl;
	}

	public function getResources()
	{
		$resources = [];
		$resources['private'] = [
			'el' => [
				'delete' => function($user, $group) {
					$tableName = $this->request->getPost('delete')['table'];

					if (!empty($user))
						return $user->hasAccess($tableName, Access::WRITE);

					return $group->hasAccess($tableName, Access::WRITE);
				},
				'duplicate' => function($user, $group) {
					$tableName = $this->request->getPost('duplicate')['from'];

					if (!empty($user))
						return $user->hasAccess($tableName, Access::WRITE);

					return $group->hasAccess($tableName, Access::WRITE);
				},
				'insert' => function($user, $group) {
					$tableName = $this->request->getPost('insert')['table'];

					if (!empty($user))
						return $user->hasAccess($tableName, Access::WRITE);

					return $group->hasAccess($tableName, Access::WRITE);
				},
				'update' => function($user, $group) {
					$tableName = $this->request->getPost('update')['table'];

					if (!empty($user))
						return $user->hasAccess($tableName, Access::WRITE);

					return $group->hasAccess($tableName, Access::WRITE);
				},
				'select' => function($user, $group) {
					$tableName = $this->request->get('select')['from'];

					if (!empty($user))
						return $user->hasAccess($tableName, Access::READ);

					return $group->hasAccess($tableName, Access::READ);
				},
				'search' => function($user, $group) {
					$tableName = $this->request->get('select')['from'];

					if (!empty($user))
						return $user->hasAccess($tableName, Access::READ);

					return $group->hasAccess($tableName, Access::READ);
				},
				'getTables' => null,
			],
			'users' => [
				'setLanguage' => function($user, $group) {
					return $this->request->getPost('id') === $user->id;
				},
				'getUser' => function($user, $group) {
					return $this->request->get('id') === $user->id;
				},
			],
			'field'    => [ '*' => null, ],
			'ext'      => [ '*' => null, ],
			'tview'    => [ '*' => null, ],
		];

		$resources['admin'] = [
			'settings' => ['*'],
			'groups'   => ['*'],
			'tokens'   => ['*'],
			'users'    => ['*'],
			'el'       => ['setTviewSettings'],
		];

		$resources['public'] = ['index', 'auth'];

		return $resources;
	}
	/**
	 * возвращает массив Phalcon\Acl\Role
	 */
	public function getRoles()
	{
		return [
			"guests" => new Phalcon\Acl\Role("Guests", "Unauthorized user"),
			"users"  => new Phalcon\Acl\Role("Users", "Regular user"),
			"admins" => new Phalcon\Acl\Role("Admins", "Super-user"),
		];
	}

	/**
	 * возвращает роль текущего пользователя
	 */
	public function getCurrentUserRole()
	{
		$this->session->remove('token');
		$this->session->set('token', $this->request->get('token', 'string', ''));

		if (!$this->user && !$this->group)
			$role = 'Guests';
		else
		{
			$role = 'Users';

			if (($this->user && $this->user->isAdmin())
				|| $this->group && $this->group->isAdminGroup())
				$role = 'Admins';
		}
		return $role;
	}
	/**
	 * This action is executed before execute any action in the application
	 */
	public function beforeDispatch(Phalcon\Events\Event $event, Phalcon\Mvc\Dispatcher $dispatcher, $exception)
	{
		$role = $this->getCurrentUserRole();

		$controller = $dispatcher->getControllerName();
		$action     = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action, ['user' => $this->user, 'group' => $this->group]);

		if($allowed != Phalcon\Acl::ALLOW)
		{
			echo json_encode(['success' => false, 'message' => 'you dont have access']);
			exit();
		}
	}
}
