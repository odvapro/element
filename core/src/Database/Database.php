<?php

namespace Element\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class Database
{
	public static function connect(array $params): Connection
    {
        return DriverManager::getConnection($params);
    }
}
