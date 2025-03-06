<?php
use Phalcon\Db\Adapter\PdoFactory as DbAdapter;

if(file_exists(__DIR__ . "/../app/config/config.php"))
{
	echo json_encode([
		'success' => false,
		'message' => ' Config file already exists',
		'code'    => 1
	]);
	exit();
}

if (empty($_POST) ||
    empty($_POST['adapter']) ||
    empty($_POST['host']) ||
    empty($_POST['username']) ||
    empty($_POST['password']) ||
    empty($_POST['dbname']) ||
    empty($_POST['baseUrl'])
)
{
	echo json_encode([
		'success' => false,
		'message' => 'Compleate all fields',
		'code'    => 3
	]);
	exit();
}

try
{
	$_POST['password'] = preg_replace("/[\\\]/", '', $_POST['password']);

	$dbConfig = [
		'host'     => $_POST['host'],
		'username' => $_POST['username'],
		'password' => $_POST['password'],
		'dbname'   => $_POST['dbname'],
		'port'     => $_POST['port'],
	];

	$dbAdapter = 'Phalcon\\Db\\Adapter\Pdo\\'. ucfirst($_POST['adapter']);
	$connection = new $dbAdapter($dbConfig);


	if (!is_dir('../app/config') or !is_writable('../app/config'))
	{
		echo json_encode([
			'success' => false,
			'message' => 'Folder ' . __DIR__ . '/../app/config/ must be writable'
		]);
		exit();
	}

	if ($_POST['isCheck'] == 'true')
	{
		echo json_encode(['success' => true, 'message' => 'Correct']);
		exit();
	}

	$installFileName = sprintf('/install_%s.sql', $_POST['adapter']);

	$installSql = file_get_contents(__DIR__ . $installFileName);

	$connection->execute($installSql);

	$config = file_get_contents(__DIR__."/../app/config/configSample.php");
	$config = preg_replace('/#adapter#/', $_POST['adapter'], $config);
	$config = preg_replace('/#username#/', $_POST['username'], $config);
	$config = preg_replace('/#host#/', $_POST['host'], $config);
	$config = preg_replace('/#dbname#/', $_POST['dbname'], $config);
	$config = preg_replace('/#password#/', $_POST['password'], $config);
	$config = preg_replace('/#port#/', $_POST['port'], $config);
	$config = preg_replace('/#baseuri#/', $_POST['baseUrl'], $config);

	file_put_contents('../app/config/config.php', $config);
	echo json_encode(['success' => true]);
	exit();

}
catch (Exception $e)
{
	echo json_encode([
		'success' => false,
		'message' => 'Wrong credentials',
		'error'   => $e,
		'code'    => 2
	]);
	exit();
}
