<?php
$configDirPath = __DIR__.'/../../app/config/';
$installed = file_exists($configDirPath.'config.php');


// ===== AJAX обработка
if(!$installed && !empty($_REQUEST['ajax']) && $_REQUEST['ajax'] == 'install')
{
	// проверка на обязательность полей
	$reqFields = ['host','user','password','dbname','baseuri'];
	$fields = [];
	foreach ($_REQUEST['formData'] as $fieldArray)
		if(in_array($fieldArray['name'], $reqFields) && empty($fieldArray['value']))
		{
			echo json_encode(['success'=>false]);
			die();
		}
		else
			$fields[$fieldArray['name']] = $fieldArray['value'];
		

	// достаем sample
	// заменяем нужные участки 
	// сохроняем в config.php
	

	$smapleConfig = file_get_contents($configDirPath.'configSample.php');
	$smapleConfig = str_replace(
        ['#host#','#username#','#password#','#dbname#','#baseuri#'],
        [$fields['host'],$fields['user'],$fields['password'],$fields['dbname'],$fields['baseuri']],
        $smapleConfig);

	file_put_contents($configDirPath.'config.php', $smapleConfig);
	echo json_encode(['success'=>true]);
	die();
}
// ===== 



// проверка - создан ли файл //config.php
// если есть файл, значит движок установлен
if(!$installed)
	include 'part/install.php';
else
	include 'part/succeess.php';
?>