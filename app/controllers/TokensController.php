<?php

class TokensController extends ControllerBase
{
	/**
	 * запись токена для группы
	 * @return json
	 */
	public function createTokenAction()
	{

		$groupId = $this->request->getPost('group_id');
		if (empty($groupId))
			return $this->jsonResult(['success' => false, 'msg' => 'empty id']);

		$group = EmGroups::findFirst($groupId);
		if (empty($group))
			return $this->jsonResult(['success' => false, 'msg' => 'no such group']);

		$tokenStr = Access::generateAccessToken();

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
		$tokens = [];

		foreach (EmTokens::find() as $token)
			$tokens[] = $token->toArray();

		return $this->jsonResult(['success' => !empty($tokens), 'tokens' => $tokens]);
	}
	public function getApiDocsAction()
	{
		$table = $this->request->getPost('table_name');
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
		$baseRequestUrl     = $protocol . $_SERVER['SERVER_NAME'] . '/element/api';

		$result = [
			"get"    => [
				"code"     => "curl {$baseRequestUrl}/el/select/?select[from]={$table}&select[page]=1",
				"response" => "{\"success\": true}",
			],
			"insert" => [
				"code"     => "curl {$baseRequestUrl}/el/insert/ -d \"insert[table]=field_types&insert[values][type]=test&insert[values][title]=test title\"",
				"response" => "{\"success\":true,\"result\":true,\"lastid\":\"17\"}",
			],
			"delete" => [
				"code"     => "curl {$baseRequestUrl}/el/delete/ -d \"delete[table]={$table}&delete[where][operation]=and&delete[where][fields][0][code]=`#id field title`&delete[where][fields][0][operation]=IS&delete[where][fields][0][value]=`#id field value`\"",
				"response" => "{\"success\": true}",
			],
			"update" => [
				"code"     => "curl {$baseRequestUrl}/el/update/ -d \"update[table]={$table}&update[set][code]=your value&update[set][keywords]=your value&update[set][description]=your value&update[set][title]=your value&update[set][pagetitle]=your value&update[set][form]=your value&update[where][operation]=and&update[where][fields][0][code]=id&update[where][fields][0][operation]=IS&update[where][fields][0][value]=`#id field value`\"",
				"response" => "{\"success\": true}",
			],
		];

		return $this->jsonResult(['success' => true, 'docs' => $result]);
	}
}
