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
	 * Возвращает все токены
	 * @return json
	 */
	public function getTokensAction()
	{
		if (!$this->access->checkAuthAdmin()) return $this->jsonResult(['success' => false,'message' => 'access denied']);

		$tokens = EmTokens::find()->toArray();
		return $this->jsonResult(['success' => !empty($tokens), 'tokens' => $tokens]);
	}
}