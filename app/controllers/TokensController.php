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
				"code"     => "curl_setopt_array(\$curl, array(
  CURLOPT_URL            => '{$baseRequestUrl}/el/select/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING       => '',
  CURLOPT_MAXREDIRS      => 10,
  CURLOPT_TIMEOUT        => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST  => 'POST',
  CURLOPT_POSTFIELDS     => http_build_query(array(
    'token'  => #token#,
    'select' => array(
      'from' => {$table},
      'page' => 1,
    ),
  )),
  CURLOPT_HTTPHEADER     => array(
  	'Content-Type: text/plain'
  ),
));

\$response = curl_exec(\$curl);

curl_close(\$curl);
echo \$response;",
				"response" => "{
  \"success\": true,
  \"result\": {
    \"first\": 1,
    \"before\": 1,
    \"current\": 1,
    \"last\": 1,
    \"next\": 1,
    \"total_pages\": 1,
    \"total_items\": 1,
    \"limit\": 1,
    \"offset\": 1,
    \"items\": [{
      \"field_name\": \"field_value\"
    }]
  }
}",
			],
			"insert" => [
				"code"     => "curl_setopt_array(\$curl, array(
  CURLOPT_URL            => '{$baseRequestUrl}/el/insert/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING       => '',
  CURLOPT_MAXREDIRS      => 10,
  CURLOPT_TIMEOUT        => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST  => 'POST',
  CURLOPT_POSTFIELDS     => http_build_query(array(
    'token'  => #token#,
    'insert' => array(
      'table'  => {$table},
      'values' => array(
        # ...
      ),
    ),
  )),
  CURLOPT_HTTPHEADER     => array(
  	'Content-Type: text/plain'
  ),
));

\$response = curl_exec(\$curl);

curl_close(\$curl);
echo \$response;",
				"response" => "{
  \"success\": true,
  \"result\": true,
  \"lastid\": \"17\"
}",
			],
			"delete" => [
				"code"     => "curl_setopt_array(\$curl, array(
  CURLOPT_URL            => '{$baseRequestUrl}/el/delete/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING       => '',
  CURLOPT_MAXREDIRS      => 10,
  CURLOPT_TIMEOUT        => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST  => 'POST',
  CURLOPT_POSTFIELDS     => http_build_query(array(
    'token'  => #token#,
    'delete' => array(
      'table'  => {$table},
      'where'  => array(
        'operation' => 'AND',
        'fields'    => array(
          [
            'code'      => #field_code#,
            'operation' => 'IS',
            'value'     => #field_value#,
          ],
        ),
      ),
    ),
  )),
  CURLOPT_HTTPHEADER     => array(
  	'Content-Type: text/plain'
  ),
));

\$response = curl_exec(\$curl);

curl_close(\$curl);
echo \$response;",
				"response" => "{
  \"success\": true,
  \"result\": true
}",
			],
			"update" => [
				"code"     => "curl_setopt_array(\$curl, array(
  CURLOPT_URL            => '{$baseRequestUrl}/el/update/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING       => '',
  CURLOPT_MAXREDIRS      => 10,
  CURLOPT_TIMEOUT        => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST  => 'POST',
  CURLOPT_POSTFIELDS     => http_build_query(array(
    'token'  => #token#,
    'delete' => array(
      'table'  => {$table},
      'set'    => array(
         'field_code' => 'field_value',
         # ...
       ),
       'where' => array(
        'operation' => 'AND',
        'fields'    => array(
          [
            'code'      => #field_code#,
            'operation' => 'IS',
            'value'     => #field_value#,
          ],
       ),
      ),
    ),
  )),
  CURLOPT_HTTPHEADER     => array(
  	'Content-Type: text/plain'
  ),
));

\$response = curl_exec(\$curl);

curl_close(\$curl);
echo \$response;",
				"response" => "{
  \"success\": true,
  \"result\": true
}",
			],
		];

		return $this->jsonResult(['success' => true, 'docs' => $result]);
	}
}
