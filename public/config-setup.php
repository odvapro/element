<?php
	use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

	$error = '';

	if (!empty($_POST))
	{
		if (!empty($_POST['host']) || !empty($_POST['username']) || !empty($_POST['password']) || !empty($_POST['dbname']))
		{
			try {
				$connection = new DbAdapter(array(
					'host' => $_POST['host'],
					'username' => $_POST['username'],
					'password' => $_POST['password'],
					'dbname' => $_POST['dbname']
				));

				$password = escapeshellcmd($_POST['password']);

				exec("mysql --user={$_POST['username']} --password={$password} {$_POST['dbname']} < ../dump.sql");

				$config = file_get_contents(__DIR__ . "/../app/config/configSample.php");

				$config = preg_replace('/#username#/', $_POST['username'], $config);
				$config = preg_replace('/#host#/', $_POST['host'], $config);
				$config = preg_replace('/#dbname#/', $_POST['dbname'], $config);
				$config = preg_replace('/#password#/', $_POST['password'], $config);
				$config = preg_replace('/#baseuri#/', '/', $config);

				file_put_contents('../app/config/config.php', $config);
				header('location: index.php');
			} catch (Exception $e) {
				$error = 'Доступы не верны';
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<div><?= $error ?></div>
	<div class="wrapper">
		<form action="" method="post">
			<div class="logoLine">
				<span class="logo">Данные БД</span>
			</div>
			<div>
				<input name="host" type="text" value="<?= !empty($_POST['host']) ? $_POST['host'] : 'localhost'?>">
			</div>
			<div>
				<input name="dbname" placeholder="dbname" type="text" value="<?=!empty($_POST['dbname']) ? $_POST['dbname'] : '' ?>">
			</div>
			<div>
				<input name="username" type="text" placeholder="username" value="<?=!empty($_POST['username']) ? $_POST['username'] : '' ?>">
			</div>
			<div>
				<input name="password" type="text" placeholder="password" value="<?=!empty($_POST['password']) ? $_POST['password'] : '' ?>">
			</div>
			<button>Отправить</button>
		</form>
	</div>
</body>
<style>
	body
	{
		height: 100vh;
	}
	.wrapper
	{
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		height: 100%;
		justify-content: center;
		-ms-align-items: center;
		align-items: center;
	}
	.logoLine
	{
		padding: 15px;
	}
	.logo
	{
		font-size: 40px;
		color: #fff;
	}
	form
	{
		padding: 15px;
		width: 393px;
		background-color: #fedede;
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
		-webkit-flex-direction: column;
		-moz-flex-direction: column;
		-ms-flex-direction: column;
		-o-flex-direction: column;
		flex-direction: column;
		justify-content: center;
		-ms-align-items: center;
		background: #531F5D;
		align-items: center;
	}
	input
	{
		width: 354px;
		height: 35px;
		line-height: 35px;
		border: 0px;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		display: block;
		margin: 0 auto;
		font-size: 16px;
		color: #000;
		text-align: center;
		outline: 0px;
		margin-bottom: 15px;
	}
	button
	{
		width: 123px;
		height: 35px;
		background: #733A7E;
		border: 0px;
		text-align: center;
		-webkit-border-radius: 37px;
		border-radius: 37px;
		line-height: 35px;
		font-size: 16px;
		color: #FFFFFF;
		display: block;
		float: right;
		cursor: pointer;
	}
</style>
</html>