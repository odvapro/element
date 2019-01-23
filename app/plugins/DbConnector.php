<?php

use Phalcon\Db\Adapter\Pdo\Factory as Factory;

/**
 * Адаптер для подключения к разным базам данных
 */
class DbConnector
{
	private $config;

	/**
	 * Конструктор, принимает конфигурацию бд
	 */
	function __construct($config = [])
	{
		$this->config = $config;
	}
	/**
	 * Проверка подключения к бд
	 * @return [array]
	 */
	public function dbConnection()
	{
		try {
			$options = [
				"host"     => $this->config['host'],
				"dbname"   => $this->config['dbname'],
				"port"     => 3306,
				"username" => $this->config['username'],
				"password" => $this->config['password'],
				"adapter"  => $this->config['db']
			];

			$db = Factory::load($options);
			return json_encode(['success' => true, 'message' => 'Успешно']);
		} catch (Exception $e) {
			$error = 'Доступы не верны';
			return json_encode(['success' => false, 'message' => $error]);
		}
	}
}