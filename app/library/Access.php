<?php
use Phalcon\Di;

class Access
{
	protected $di;
	const READ  = 1;
	const WRITE = 2;
	const FULL  = self::READ | self::WRITE;
	const ADMIN_ID = 1;

	public function __construct($di)
	{
		$this->di = $di;
	}

	/**
	 * возвращает массив доступов таблицы [{access,group_id}]
	 * @param  string $tableName название таблицы
	 * @return array
	 */
	public static function getAccessTable($tableName)
	{
		$accessData = EmGroupsTables::find([
			'conditions' => 'table_name = ?0',
			'bind'       => [$tableName],
			'columns'    => 'group_id, access, table_name'
		])->toArray();

		return $accessData;
	}

	/**
	 * генерация токена
	 * @return string
	 */
	public static function generateAccessToken()
	{
		$token = md5("elementodva" . rand(0, 9999999999) . "accesstoken");
		return $token;
	}
}