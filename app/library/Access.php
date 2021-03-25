<?php
use Phalcon\Di;

class Access
{
	protected $di;
	const READ  = 1;
	const WRITE = 2;
	const FULL  = self::READ | self::WRITE;
	const ADMINS_GROUP_ID = 1;

	public function __construct($di)
	{
		$this->di = $di;
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
