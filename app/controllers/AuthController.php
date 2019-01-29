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
}
