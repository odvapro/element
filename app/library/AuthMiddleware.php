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
			foreach($actions as $actionName => $action)
			{
				$acl->allow('Admins', $resource, $actionName);

				if ( isset($action['allowFunction']) )
					$acl->allow('Users', $resource, $actionName, $action['allowFunction']);
				else
					$acl->allow('Users', $resource, $actionName);
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

		return $acl;
	}

	public function getResources()
	{
		$resources = [];
		$resources['private'] = [
			'el' => [
				'delete' => [
					'allowFunction' => function($user) {
						$tableName = $this->request->getPost('delete')['table'];
						return $user->hasAccess($tableName, Access::WRITE);
					}
				],
				'duplicate' => [
					'allowFunction' => function($user) {
						$tableName = $this->request->getPost('duplicate')['from'];
						return $user->hasAccess($tableName, Access::WRITE);
					}
				],
				'insert' => [
					'allowFunction' => function($user) {
						$tableName = $this->request->getPost('insert')['table'];
						return $user->hasAccess($tableName, Access::WRITE);
					}
				],
				'update' => [
					'allowFunction' => function($user) {
						$tableName = $this->request->getPost('update')['table'];
						return $user->hasAccess($tableName, Access::WRITE);
					}
				],
				'select' => [
					'allowFunction' => function($user) {
						$tableName = $this->request->get('select')['from'];
						return $user->hasAccess($tableName, Access::READ);
					}
				],
				'setTviewSettings' => ['allowFunction' => function($user){return $user->isAdmin();}],
				'getTables' => [],
			],
			'users' => [
				'setLanguage'=> [
					'allowFunction' => function($user) {
						return $this->request->getPost('id') === $user->id;
					}
				],
				'getUser'=> [
					'allowFunction' => function($user) {
						return $this->request->get('id') === $user->id;
					}
				],
				'*'  => ['allowFunction'=>function($user){return $user->isAdmin();}],
			],
			'settings' => [ '*' => ['allowFunction'=>function($user){return $user->isAdmin();}] ],
			'groups'   => [ '*' => ['allowFunction'=>function($user){return $user->isAdmin();}] ],
			'tokens'   => [ '*' => ['allowFunction'=>function($user){return $user->isAdmin();}] ],
			'field'    => [ '*' => [] ],
			'ext'      => [ '*' => [] ],
			'tview'    => [ '*' => [] ],
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
		if (!$this->user)
			$role = 'Guests';
		else
		{
			$role = 'Users';
			if ($this->user->isAdmin())
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
		$allowed = $acl->isAllowed($role, $controller, $action, ['user'=>$this->user]);
		if($allowed != Phalcon\Acl::ALLOW)
		{
			echo json_encode(['success' => false, 'message' => 'you dont have access']);
			exit();
		}
	}
}
