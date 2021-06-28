<?php

class AuthController extends ControllerBase
{
	/**
	 * Авторизация пользователя
	 */
	public function indexAction()
	{
		$this->session->remove('auth');

		$login    = $this->request->getPost('login');
		$password = $this->request->getPost('password');

		if(empty($login) || empty($password))
			return $this->jsonResult(['success' => false, 'message' => 'No params', 'code' => 1]);

		$user = EmUsers::findFirst([
			'conditions' => "password = ?1 AND (login = ?0 OR email = ?0)",
			'bind'       => [$login, md5($password)]
		]);

		if(!$user)
			return $this->jsonResult(['success' => false, 'message' => 'User is not found', 'code' => 3]);

		$this->session->set('auth', $user->id);
		$result = [
			'success' => true,
			'user'    => [
				'name'     => $user->name,
				'id'       => $user->id,
				'email'    => $user->email,
				'avatar'   => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '&s=40',
				'language' => $user->language
			]
		];

		$result['user']['is_admin'] = $user->isAdmin();

		return $this->jsonResult($result);
	}

	/**
	 * Восстановление пароля пользователя
	 */
	public function forgotPassAction()
	{
		$email = $this->request->getPost('email');

		if(empty($email))
			return $this->jsonResult(['success' => false, 'message' => 'No params', 'code' => 1]);

		$user = EmUsers::findFirst([
			'conditions' => "email = ?0",
			'bind'       => [$email]
		]);

		if(!$user)
			return $this->jsonResult(['success' => false, 'message' => 'Incorrect email', 'code' => 3]);

		$temporaryPass = uniqid();
		$temporaryPass = substr($temporaryPass, -6);

		$error = !$user->save([
			'password' => md5($temporaryPass)
		]);

		if($error)
			return $this->jsonResult(['success' => false, 'message' => 'Error save', 'code' => 2]);

		mail($user->email, 'Forgot Password', "You new password: {$temporaryPass}", "From:no-reply@gmail.com");

		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Проверка на авторизованность пользователя
	 */
	public function isLoggedAction()
	{
		if(!$this->user && empty($this->user))
			return $this->jsonResult(['success' => false, 'message' => 'no auth', 'code' => 4]);

		return $this->jsonResult(['success' => true, 'userid' => $this->user->id]);
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
