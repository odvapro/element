<?php

class AuthController extends ControllerBase
{
	public static function checkCurrentLanguage()
	{
		$session = Phalcon\Di::getDefault()->get('session');
		$auth = $session->get('auth');
		if (!empty($auth))
		{
			$user = EmUsers::findFirstById($auth);
			$session->set('currentLanguage', json_decode($user->language)->short);
		}
		elseif (empty($session->get('currentLanguage')) && empty($auth))
			$session->set('currentLanguage', 'en');
	}
	/**
	 * Авторизация пользователя
	 */
	public function indexAction()
	{
		$this->session->remove('auth');

		$login    = $this->request->getPost('login');
		$password = $this->request->getPost('password');

		if(empty($login) || empty($password))
			return $this->jsonResult(['success' => false, 'message' => 'No params']);

		$user = EmUsers::findFirst([
			'conditions' => "password = ?1 AND (login = ?0 OR email = ?0)",
			'bind'       => [$login, md5($password)]
		]);

		if(!$user)
			return $this->jsonResult(['success' => false, 'message' => 'User is not found']);

		$this->session->set('auth', $user->id);

		return $this->jsonResult([
			'success' => true,
			'user'    => [
				'name'     => $user->name,
				'id'       => $user->id,
				'email'    => $user->email,
				'avatar'   => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '&s=40',
				'language' => $user->language
			]
		]);
	}

	/**
	 * Восстановление пароля пользователя
	 */
	public function forgotPassAction()
	{
		$email = $this->request->getPost('email');

		if(empty($email))
			return $this->jsonResult(['success' => false, 'message' => 'No params']);

		$user = EmUsers::findFirst([
			'conditions' => "email = ?0",
			'bind'       => [$email]
		]);

		if(!$user)
			return $this->jsonResult(['success' => false, 'message' => 'Incorrect email']);

		$temporaryPass = uniqid();
		$temporaryPass = substr($temporaryPass, -6);

		$error = !$user->save([
			'password' => md5($temporaryPass)
		]);

		if($error)
			return $this->jsonResult(['success' => false, 'message' => 'Error save']);

		mail($user->email, 'Forgot Password', "You new password: {$temporaryPass}", "From:no-reply@gmail.com");

		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Проверка на авторизованность пользователя
	 */
	public function isLoggedAction()
	{
		$auth = $this->session->get('auth');
		if(empty($auth))
		{
			AuthController::checkCurrentLanguage();
			return $this->jsonResult(['success' => false, 'message' => 'no auth', 'language' => $this->session->get('currentLanguage')]);
		}
		AuthController::checkCurrentLanguage();

		return $this->jsonResult(['success' => true, 'userid' => $auth, 'language' => $this->session->get('currentLanguage')]);
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
