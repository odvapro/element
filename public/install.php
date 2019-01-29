<?php
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

if(file_exists(__DIR__ . "/../app/config/config.php"))
{
	echo json_encode(['success' => false, 'message' => 'Конфигурационный файл уже существует', 'code' => 1]);
	exit();
}
if (!empty($_POST))
{
	if (!empty($_POST['adapter']) && !empty($_POST['host']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['dbname']) && !empty($_POST['baseUrl']))
	{
		try{

			$password = preg_replace("/[\\\]/", '', $_POST['password']);

			$connection = new DbAdapter(array(
				'adapter'  => $_POST['adapter'],
				'host'     => $_POST['host'],
				'username' => $_POST['username'],
				'password' => $password,
				'dbname'   => $_POST['dbname']
			));

			exec("mysql --user={$_POST['username']} --password={$_POST['password']} {$_POST['dbname']} < ../dump.sql");

			$config = file_get_contents(__DIR__ . "/../app/config/configSample.php");

			$config = preg_replace('/#adapter#/', $_POST['adapter'], $config);
			$config = preg_replace('/#username#/', $_POST['username'], $config);
			$config = preg_replace('/#host#/', $_POST['host'], $config);
			$config = preg_replace('/#dbname#/', $_POST['dbname'], $config);
			$config = preg_replace('/#password#/', $password, $config);
			$config = preg_replace('/#baseuri#/', $_POST['baseUrl'], $config);

			if (!is_dir('../app/config') or !is_writable('../app/config'))
			{
			  echo json_encode(['success' => false, 'message' => 'Дериктория ' . __DIR__ . '/../app/config не доступна для записи']);
			  exit();
			}
			file_put_contents('../app/config/config.php', $config);

			echo json_encode(['success' => true]);

			exit();

		} catch (Exception $e) {
			echo json_encode(['success' => false, 'message' => 'Доступы не верны', 'error' => $e, 'code' => 2]);
			exit();
		}
	}
}
echo json_encode(['success' => false, 'message' => 'Не заполнены обязательные поля', 'code' => 3]);