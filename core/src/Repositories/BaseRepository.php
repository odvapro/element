<?php

namespace Element\Repositories;

use Doctrine\DBAL\Connection;

class BaseRepository
{
	public static Connection $conn;

	public static function setConnetion(Connection $conn)
	{
		self::$conn = $conn;
	}

	public function db()
	{
		return self::$conn;
	}
}