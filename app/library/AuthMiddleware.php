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

			//Register roles
			$roles = [
				'users'  => new Phalcon\Acl\Role('Users',"Super-User role"),
				'guests' => new Phalcon\Acl\Role('Guests')
			];
			foreach($roles as $role)
				$acl->addRole($role);

			//Private area resources
			$privateResources = [
				'el'  => ['*'],
				'users' => ['*'],
				'settings' => ['*']
			];

			foreach($privateResources as $resource => $actions)
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);

			//Public area resources
			$publicResources = [
				'index' => ['*'],
				'auth'  => ['*']
			];

			foreach($publicResources as $resource => $actions)
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);

			//Grant access to public areas to both users and guests
			foreach($roles as $role)
			{
				foreach($publicResources as $resource => $actions)
					$acl->allow($role->getName(), $resource, '*');
			}

			//Grant acess to private area to role Users
			foreach($privateResources as $resource => $actions)
			{
				foreach($actions as $action)
					$acl->allow('Users', $resource, $action);
			}

			$this->_acl = $acl;
		}
		return $this->_acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 */
	public function beforeDispatch(Phalcon\Events\Event $event, Phalcon\Mvc\Dispatcher $dispatcher, $exception)
	{
		$auth = $this->session->get('auth');
		if (!$auth)
			$role = 'Guests';
		else
			$role = 'Users';

		$controller = $dispatcher->getControllerName();
		$action     = $dispatcher->getActionName();

		$acl = $this->getAcl();
		$allowed = $acl->isAllowed($role, $controller, $action);
		if($allowed != Phalcon\Acl::ALLOW)
		{
			echo json_encode(['success' => false, 'message' => 'you need to auth']);
			exit();
		}
	}
}