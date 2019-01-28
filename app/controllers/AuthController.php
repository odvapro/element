<?php

class AuthController extends ControllerBase
{
	/**
	 * Авторизация пользователя
	 */
	public function indexAction()
	{
		$login    = $this->request->getPost('login');
		$password = $this->request->getPost('password');
		if(empty($login) || empty($password))
			return $this->jsonResult(['success' => false, 'message' => 'No params']);

		$user = EmUsers::find([
			'conditions' => "login = ?0 AND password = ?1",
			'bind'       => [$login, md5($password)]
		]);

		if(!count($user))
			return $this->jsonResult(['success' => false, 'message' => 'Bad credentials']);
		else
		{
			$user = $user[0];
			$this->session->set('auth', $user->id);

			return $this->jsonResult([
				'success' => true,
				'user'    => $user->id
			]);
		}

		return $this->jsonResult(['success' => false, 'message' => 'somathing strange']);
	}

	/**
	 * Проверка на авторизованность пользователя
	 */
	public function isLoggedAction()
	{
		$auth = $this->session->get('auth');
		if(empty($auth))
			return $this->jsonResult(['success' => false, 'message' => 'no auth']);

		return $this->jsonResult(['success' => true, 'userid' => $auth]);
	}

	/**
	 * Выход
	 */
	public function logOutAction()
	{
		$this->session->remove('auth');
		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Регистриация пользователя
	 */
	public function signupAction()
	{
		$name       = $this->request->getPost('name');
		$login      = $this->request->getPost('login');
		$email      = $this->request->getPost('email');
		$password   = $this->request->getPost('password');
		$repassword = $this->request->getPost('repassword');

		if(empty($name) || empty($login) || empty($email) || empty($password) || empty($repassword))
			return $this->jsonResult(['success' => false, 'message' => 'fill all fields']);

		if($password !== $repassword)
			return $this->jsonResult(['success' => false, 'message' => 'fill all fields']);

		// if email exists - false
		$user = EmUsers::find([
			'conditions' => "login = ?0",
			'bind'       => [$login]
		]);
		if(count($user))
			return $this->jsonResult(['success' => false, 'message' => 'user already registered']);

		$user = new EmUsers();

		$user->name      = $name;
		$user->email     = $email;
		$user->login     = $login;
		$user->password  = md5($password);

		if(!$user->save())
			return $this->jsonResult(['success' => false, 'message' => 'something wrong']);

		return $this->jsonResult(['success' => true]);
	}
}
