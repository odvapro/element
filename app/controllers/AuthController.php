<?php

class AuthController extends ControllerBase
{
	public function indexAction()
	{
		// обработка входа в админку
		$errors = [];
		if($this->request->isPost())
		{
			$login    = $this->request->getPost('login');
			$password = $this->request->getPost('password');
			if(empty($login)) $errors[] = 'Введите логин';
			if(empty($password)) $errors[] = 'Введите пароль';
			if(!count($errors))
			{
				$user = EmUsers::findFirst("login = '$login'");
				if($user)
				{
					if($user->password == MD5($password))
					{
						// make auth
						$this->_registerSession($user);
						
						$this->response->redirect('');
						$this->view->disable();
						return;
					}
					else
						$errors[] = 'Неправильный пароль';
				}
				else
					$errors[] = 'Пользователь не найден';
			}
		}
		
		$this->view->setVar('login',$login);
		$this->view->setVar('password',$password);
		$this->view->setVar('errors',$errors);
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

