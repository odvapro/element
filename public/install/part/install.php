<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Установка | Element v 0.1</title>
		<!--[if lt IE 9]>
			<script src="../js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="../css/enterform.css" />
		<link rel="stylesheet" href="../css/enterform.css" />
	</head>
	<body>
		<div id="enterForm">
			<div class="logoLine">
				<span class="logo"></span>
			</div>
		    <!-- <p class="error">{{ error }}</p> -->
			<form onsubmit="return el.install(this)" action="" method="post">
				<label>
					<input required type="text" name="host" placeholder="Хост" value="localhost" />
				</label>
				<label>
					<input required type="text" name="user" placeholder="Пользователь" value="" />
				</label>
				<label>
					<input required type="text" name="password" placeholder="Пароль" value="" />
				</label>
				<label>
					<input required type="text" name="dbname" placeholder="Имя базы данных" value="" />
				</label>
				<label>
					<input required type="text" name="baseuri" placeholder="Путь к панели" value="/element/" />
				</label>
				<label>
					<input type="submit" name="enter" value="Установить" />
				</label>
			</form>
		</div>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="js/install.js"></script>
	</body>
</html>