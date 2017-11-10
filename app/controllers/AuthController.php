<?php

class AuthController extends ControllerBase
{
	/**
	 * Autharization
	 * @return void
	 */
	public function indexAction()
	{
		$login    = $this->request->getPost('login');
		$password = $this->request->getPost('password');
		$this->view->setVar('login',$login);
		$this->view->setVar('password',$password);
		$this->view->setVar('errors',[]);
		if($this->request->isPost())
		{
			if(empty($login))
				return $this->view->setVar('errors',['Введите логин']);
			if(empty($password))
				return $this->view->setVar('errors',['Введите пароль']);

			$user = EmUsers::findFirst("login = '$login'");
			if(!$user)
				return $this->view->setVar('errors',['Пользователь не найден']);

			if($user->password != MD5($password))
				return $this->view->setVar('errors',['Неправильный пароль']);

			// make auth
			$this->_registerSession($user);

			$this->response->redirect('');
			$this->view->disable();
			return;
		}
	}

	public function logoutAction()
	{
		$this->session->remove('auth');
		$this->response->redirect('');
		$this->view->disable();
		return;
	}

	// выполнить вход
	private function _registerSession($user)
	{
		$this->session->set('auth', array(
			'id'   => $user->id,
			'name' => $user->name
		));
	}

}
