<?php

class TokensController extends ControllerBase
{
	/**
	 * запись токена для группы
	 * @return json
	 */
	public function createTokenAction()
	{
		if (!$this->access->checkAuthAdmin()) return $this->jsonResult(['success' => false,'message' => 'access denied']);

		$groupId = $this->request->getPost('group_id');
		if (empty($groupId))
			return $this->jsonResult(['success' => false, 'msg' => 'empty id']);

		$group = EmGroups::findFirst($groupId);
		if (empty($group))
			return $this->jsonResult(['success' => false, 'msg' => 'no such group']);

		$tokenStr = $this->access->generateAccessToken();

		$token = new EmTokens();
		$token->value    = $tokenStr;
		$token->group_id = $group->id;
		$token->date     = date("Y-m-d H:i");

		$token->save();
		$token->refresh();

		return $this->jsonResult(['success' => true, 'token' => $token->toArray()]);
	}

	/**
	 * удаление токена по его id
	 * @return json
	 */
	public function removeTokenAction()
	{
		if (!$this->access->checkAuthAdmin()) return $this->jsonResult(['success' => false,'message' => 'access denied']);

		$tokenId = $this->request->getPost('token_id');
		if (empty($tokenId))
			return $this->jsonResult(['success' => false, 'msg' => 'empty id']);

		$token = EmTokens::findFirst($tokenId);
		if (empty($token))
			return $this->jsonResult(['success' => false, 'msg' => 'no such token']);

		$deleteResult = $token->delete();
		return $this->jsonResult(['success' => $deleteResult]);
	}

	/**
	 * изменение токена по его id
	 * @return json
	 */
	public function changeTokenAction()
	{
		if (!$this->access->checkAuthAdmin()) return $this->jsonResult(['success' => false,'message' => 'access denied']);

		$tokenId = $this->request->getPost('token_id');
		$groupId = $this->request->getPost('group_id');

		if (empty($tokenId) || empty($groupId))
			return $this->jsonResult(['success' => false, 'msg' => 'empty parameter']);

		$token = EmTokens::findFirst($tokenId);
		if (empty($token))
			return $this->jsonResult(['success' => false, 'msg' => 'no such token']);

		$group = EmGroups::findFirst($groupId);
		if (empty($group))
			return $this->jsonResult(['success' => false, 'msg' => 'no such group']);

		$token->group_id = $group->id;
		$saveResult = $token->save();
		return $this->jsonResult(['success' => $saveResult, 'token' => $token->toArray()]);
	}

	/**
	 * Возвращает все токены
	 * @return json
	 */
	public function getTokensAction()
	{
		if (!$this->access->checkAuthAdmin()) return $this->jsonResult(['success' => false,'message' => 'access denied']);
		$tokens = [];

		foreach (EmTokens::find() as $token)
			$tokens[] = $token->toArray();

		return $this->jsonResult(['success' => !empty($tokens), 'tokens' => $tokens]);
	}
}