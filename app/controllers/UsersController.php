<?php


class UsersController extends ControllerBase
{
	/**
	 * Достать всех пользователей из БД
	 * @return json
	 */
	public function getUsersAction()
	{
		$users = EmUsers::find();

		return $this->jsonResult(['success' => true, 'users' => $users]);
	}

	/**
	 * Достать пользователя по id
	 * @return json
	 */
	public function getUserAction()
	{
		$id = $this->request->get('id');

		if (empty($id))
			return $this->jsonResult(['success' => false, 'message' => 'id is require param']);

		$user = EmUsers::findFirstById($id);

		return $this->jsonResult(['success' => true, 'user' => $user]);
	}

	/**
	 * Обновить пользователя
	 * @return json
	 */
	public function updateUserAction()
	{
		$id         = $this->request->getPost('id');
		$login      = $this->request->getPost('login');
		$name       = $this->request->getPost('name');
		$password   = $this->request->getPost('password');

		if (empty($id))
			return $this->jsonResult(['success' => false, 'message' => 'id is require param']);

		$user = EmUsers::findFirstById($id);

		if (empty($user))
			return $this->jsonResult(['success' => false, 'message' => 'user not found']);

		if (!empty($password))
			$user->password = md5($password);

		$user->name      = !empty($name) ? $name : $user->name;
		$user->email     = !empty($email) ? $email : $user->email;
		$user->login     = !empty($login) ? $login : $user->login;

		if(!$user->save())
			return $this->jsonResult(['success' => false, 'message' => 'something wrong']);

		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Удалить пользователя
	 * @return json
	 */
	public function deleteUserAction()
	{
		$id = $this->request->getPost('id');

		if (empty($id))
			return $this->jsonResult(['success' => false, 'message' => 'id is require param']);

		$user = EmUsers::findFirstById($id);

		if (empty($user))
			return $this->jsonResult(['success' => false, 'message' => 'user not found']);

		if(!$user->delete())
			return $this->jsonResult(['success' => false, 'message' => 'something wrong']);

		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Добавить пользователя
	 * @param json
	 */
	public function addUserAction()
	{
		$name       = $this->request->getPost('name');
		$login      = $this->request->getPost('login');
		$email      = $this->request->getPost('email');
		$password   = $this->request->getPost('password');

		if (empty($name) || empty($login) || empty($email) || empty($password))
			return $this->jsonResult(['success' => false, 'message' => 'fill all fields']);

		// if email exists - false
		$user = EmUsers::find([
			'conditions' => "login = ?0",
			'bind'       => [$login]
		]);
		if (count($user))
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