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
		$table          = $this->request->getPost('table_name');
		$protocol       = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
		$baseRequestUrl = $protocol . $_SERVER['SERVER_NAME'] . '/element/api';

		$result = [
			"get"    => [
				"code"     =>
					"curl_setopt_array(\$curl, [\n" .
					"  CURLOPT_URL            => '{$baseRequestUrl}/el/select/',\n" .
					"  CURLOPT_RETURNTRANSFER => true,\n" .
					"  CURLOPT_ENCODING       => '',\n" .
					"  CURLOPT_MAXREDIRS      => 10,\n" .
					"  CURLOPT_TIMEOUT        => 0,\n" .
					"  CURLOPT_FOLLOWLOCATION => true,\n" .
					"  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,\n" .
					"  CURLOPT_CUSTOMREQUEST  => 'POST',\n" .
					"  CURLOPT_POSTFIELDS     => http_build_query([\n" .
					"    'token'  => 'token',\n" .
					"    'select' => [\n" .
					"      'from' => '{$table}',\n" .
					"      # 'page'   => 1,\n" .
					"      # 'limit'  => 100,\n" .
					"      # 'where'  => [\n" .
					"      #   'operation' => 'AND',\n" .
					"      #   'fields'    => [\n" .
					"      #     [\n" .
					"      #       'code'      => 'field_code',\n" .
					"      #       'operation' => 'IS',\n" .
					"      #       'value'     => 'field_value',\n" .
					"      #     ],\n" .
					"      #   ],\n" .
					"      # ],\n" .
					"    ],\n" .
					"  ]),\n" .
					"  CURLOPT_HTTPHEADER     => [\n" .
					"    'Content-Type: application/x-www-form-urlencoded',\n" .
					"  ],\n" .
					"]);\n" .
					"\n" .
					"\$response = curl_exec(\$curl);\n" .
					"\n" .
					"curl_close(\$curl);\n" .
					"echo \$response;",
				"response" =>
					"{\n" .
					"  \"success\": true,\n" .
					"  \"result\": {\n" .
					"    \"first\": 1,\n" .
					"    \"before\": 1,\n" .
					"    \"current\": 1,\n" .
					"    \"last\": 1,\n" .
					"    \"next\": 1,\n" .
					"    \"total_pages\": 1,\n" .
					"    \"total_items\": 1,\n" .
					"    \"limit\": 1,\n" .
					"    \"offset\": 1,\n" .
					"    \"items\": [{\n" .
					"      \"field_name\": \"field_value\"\n" .
					"    }]\n" .
					"  }\n" .
					"}",
			],
			"insert" => [
				"code"     =>
					"curl_setopt_array(\$curl, [\n" .
					"  CURLOPT_URL            => '{$baseRequestUrl}/el/insert/',\n" .
					"  CURLOPT_RETURNTRANSFER => true,\n" .
					"  CURLOPT_ENCODING       => '',\n" .
					"  CURLOPT_MAXREDIRS      => 10,\n" .
					"  CURLOPT_TIMEOUT        => 0,\n" .
					"  CURLOPT_FOLLOWLOCATION => true,\n" .
					"  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,\n" .
					"  CURLOPT_CUSTOMREQUEST  => 'POST',\n" .
					"  CURLOPT_POSTFIELDS     => http_build_query([\n" .
					"  'token'  => 'token',\n" .
					"  'insert' => [\n" .
					"    'table'  => '{$table}',\n" .
					"    'values' => [\n" .
					"      'value_1',\n" .
					"      'value_2',\n" .
					"    ],\n" .
					"  ],\n" .
					"  ]),\n" .
					"  CURLOPT_HTTPHEADER     => [\n" .
					"  'Content-Type: application/x-www-form-urlencoded',\n" .
					"  ],\n" .
					"]);\n" .
					"\n\n" .
					"\$response = curl_exec(\$curl);\n" .
					"\n\n" .
					"curl_close(\$curl);\n" .
					"echo \$response;",
				"response" =>
					"{\n" .
					"  \"success\": true,\n" .
					"  \"result\": true,\n" .
					"  \"lastid\": \"17\"\n" .
					"}",
			],
			"delete" => [
				"code"     =>
					"curl_setopt_array(\$curl, [\n" .
					"  CURLOPT_URL            => '{$baseRequestUrl}/el/delete/',\n" .
					"  CURLOPT_RETURNTRANSFER => true,\n" .
					"  CURLOPT_ENCODING       => '',\n" .
					"  CURLOPT_MAXREDIRS      => 10,\n" .
					"  CURLOPT_TIMEOUT        => 0,\n" .
					"  CURLOPT_FOLLOWLOCATION => true,\n" .
					"  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,\n" .
					"  CURLOPT_CUSTOMREQUEST  => 'POST',\n" .
					"  CURLOPT_POSTFIELDS     => http_build_query([\n" .
					"    'token'  => 'token',\n" .
					"    'delete' => [\n" .
					"        'table'  => '{$table}',\n" .
					"        'where'  => [\n" .
					"          'operation' => 'AND',\n" .
					"          'fields'    => [\n" .
					"          [\n" .
					"            'code'      => 'field_code',\n" .
					"            'operation' => 'IS',\n" .
					"            'value'     => 'field_value',\n" .
					"          ],\n" .
					"        ],\n" .
					"      ],\n" .
					"    ],\n" .
					"  ]),\n" .
					"  CURLOPT_HTTPHEADER     => [\n" .
					"    'Content-Type: application/x-www-form-urlencoded',\n" .
					"  ],\n" .
					"]);\n" .
					"\n" .
					"\$response = curl_exec(\$curl);\n" .
					"\n" .
					"curl_close(\$curl);\n" .
					"echo \$response;",
				"response" =>
					"{\n" .
					"  \"success\": true,\n" .
					"  \"result\": true\n" .
					"}",
			],
			"update" => [
				"code"     =>
					"curl_setopt_array(\$curl, [\n" .
					"  CURLOPT_URL            => '{$baseRequestUrl}/el/update/',\n" .
					"  CURLOPT_RETURNTRANSFER => true,\n" .
					"  CURLOPT_ENCODING       => '',\n" .
					"  CURLOPT_MAXREDIRS      => 10,\n" .
					"  CURLOPT_TIMEOUT        => 0,\n" .
					"  CURLOPT_FOLLOWLOCATION => true,\n" .
					"  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,\n" .
					"  CURLOPT_CUSTOMREQUEST  => 'POST',\n" .
					"  CURLOPT_POSTFIELDS     => http_build_query([\n" .
					"    'token'  => 'token',\n" .
					"    'delete' => [\n" .
					"      'table'  => '{$table}',\n" .
					"      'set'    => [\n" .
					"        'field_code' => 'field_value',\n" .
					"      ],\n" .
					"      'where' => [\n" .
					"        'operation' => 'AND',\n" .
					"        'fields'    => [\n" .
					"          [\n" .
					"            'code'      => 'field_code',\n" .
					"            'operation' => 'IS',\n" .
					"            'value'     => 'field_value',\n" .
					"          ],\n" .
					"        ],\n" .
					"      ],\n" .
					"    ],\n" .
					"  ]),\n" .
					"  CURLOPT_HTTPHEADER     => [\n" .
					"    'Content-Type: application/x-www-form-urlencoded',\n" .
					"  ],\n" .
					"));\n" .
					"\n" .
					"\$response = curl_exec(\$curl);\n" .
					"\n" .
					"curl_close(\$curl);\n" .
					"echo \$response;",
				"response" =>
					"{\n" .
					"  \"success\": true,\n" .
					"  \"result\": true\n" .
					"}",
			],
		];

		return $this->jsonResult(['success' => true, 'docs' => $result]);
	}
}
