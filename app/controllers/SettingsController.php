<?php

class SettingsController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->tableEditor =  $this->di->get('tableEditor');
	}

	/**
	 * Публичные названия полей настроек
	 */
	public $fieldsPulicNames = 
	[
		'required'     =>'Обязательное',
		'multiple'     =>'Множественное',
		'savePath'     =>'Где хранить файлы',
		'fileTypes'    =>'Типы файлов',
		'nodeTable'    =>'Таблица привязки',
		'nodeField'    =>'Поле привязки',
		'nodeSearch'   =>'Поле поиска',
		'visualEditor' =>'Визуальный редактор'
	];

	public function indexAction()
	{
		$emTypes = $this->tableEditor->getFeieldTypes();
		$this->view->setVar('EmTypes', $emTypes );
		$this->view->setVar('EmTypesCodes', array_keys($emTypes) );

		// достаем все таблицы
		$detailTables = [];
		if(!empty($this->tables))
		{
			// проход по всем таблицам в базе
			// переопределение типов если они имеются
			foreach ($this->tables as $realTableName => $tableArr)
			{
				$curTable                     = $tableArr;
				$curTable['fields']           = $curTable['fields'] = $this->tableEditor->getTableFilelds($realTableName);
				$detailTables[$realTableName] = $curTable;
			}
		}
		$this->view->setVar('detailTables',$detailTables);

		// достаем тещую версию системы
		$sysFile = $this->di->get('config')->application->configDir.'sysinfo.json';
		$sysFileArr = json_decode(file_get_contents($sysFile),true);
		$this->view->setVar('currentSystemVersion',$sysFileArr['version']);

		// список пользователей
		$users = EmUsers::find();
		$user_avatars = [];
		foreach ($users as $key => $user)
		{
			$user_avatars[$user->email] = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?d=" . urlencode( 'mm' ) . "&s=50";
		}
		$this->view->setVar('users',$users);
		$this->view->setVar('user_avatars',$user_avatars);
	}

	/**
	 * Сохраняет настройки
	 * - настройки типов таблиц
	 * - настройки пользователей
	 */
	public function saveAction()
	{
		if($this->request->isAjax())
		{
			$tablesInfo = $this->request->getPost('tables');
			$errors = false;
			foreach ($tablesInfo as $tableRealName => $tableArr)
			{
				$changes = $this->tableEditor->getTableChanges($tableRealName,$tableArr);
				if($changes !== false)
				{
					if($this->tableEditor->applyTableChanges($tableRealName,$changes) === false)
					{
						$errors = true;
						break;
					}
				}
			}
			if(!$errors)
				$this->jsonResult(['result'=>'success']);
			else
				$this->jsonResult(['result'=>'error']);
		}
		else
			$this->jsonResult(['result'=>'error']);
	}

	public function saveFieldNameAction()
	{
		if(!$this->request->isAjax())
			$this->jsonResult(['result'=>'error']);
		$tableName    = $this->request->getPost('tableName');
		$fieldName    = $this->request->getPost('fieldName');
		$fieldNewName = $this->request->getPost('fieldNewName');

		$fieldObj = EmNames::findFirst(['conditions'=>'table = ?0 AND field = ?1','bind'=>[$tableName,$fieldName]]);
		if(!$fieldObj)
		{
			$fieldObj        = new EmNames();
			$fieldObj->table = $tableName;
			$fieldObj->field = $fieldName;
		}
		$fieldObj->name = $fieldNewName; 
		if($fieldObj->save())
			return $this->jsonResult(['result'=>'success','data'=>$fieldObj->toArray()]);
		else
			return $this->jsonResult(['result'=>'error']);
	}

	/**
	 * Возврощает форму редактирования свойств поля
	 * в зависимости от типа поля
	 */
	public function getFieldFormAction()
	{
		if($this->request->isAjax())
			return $this->jsonResult(['result'=>'error']);

		$tableName = $this->request->getPost('tableName');
		$fieldName = $this->request->getPost('fieldName');

		$this->view->setVar('tableName',$tableName);
		$this->view->setVar('getFieldName',$fieldName);
		
		// определяем текущие настройки поля
		$emType = EmTypes::find([
			'conditions' => 'table = ?0 and field = ?1 ',
			'bind' => [$tableName, $fieldName]
		]);
		if(!count($emType))
			return $this->jsonResult(['result'=>'error','msg'=>'Для такого поля нет настроек']);
		
		$emType        = $emType[0];
		$settingFields = [];
		
		// обязательые параметры для всех типов полей
		$settingFields['required'] = $emType->required;
		$settingFields['multiple'] = $emType->multiple;
		$settings = [];
		if(!empty($emType->settings))
			$settings = json_decode($emType->settings,true);

		$this->view->setVar('settings',$settings);
		$this->view->setVar('settingFields',$settingFields);
		$this->view->setVar('fieldsPulicNames',$this->fieldsPulicNames);
		$this->view->setVar('fieldFormType',$emType->type);

		if(!is_null($this->fields->{$emType->type}))
			$this->fields->{$emType->type}->getSettings($settings, ['tables' => $this->tables,'settingFields'=>$settingFields]);

		$this->jsonResult([
			'result' => 'success',
			'form'   => $this->view->getRender('settings','getFieldForm')
    	]);
	}

	public function getFieldNameEditFormAction()
	{
		$tableName    = $this->request->getPost('tableName');
		$fieldName    = $this->request->getPost('fieldName');
		$fieldNewName = $this->request->getPost('fieldNewName');
		$this->view->setVar('tableName',$tableName);
		$this->view->setVar('fieldName',$fieldName);
		$this->view->setVar('fieldNewName',$fieldNewName);
		$this->jsonResult([
			'result' => 'success',
			'form'   => $this->view->getRender('settings','fieldNameEditForm')
    	]);
	}		

	/**
	 * Сохранение настройки поля таблицы
	 */
	public function saveFieldFormAction()
	{
		if(!$this->request->isAjax())
			return $this->jsonResult(['result'=>'only xhttp requests']);
		$tableName = $this->request->getPost('tableName');
		$fieldName = $this->request->getPost('fieldName');
		// достаем EmTypes (настройки поля)
		$emType = EmTypes::find([
			'conditions' => "table = ?0 AND field = ?1",
			'bind' => [$tableName,$fieldName],
		]);
		// проходи по всем полям, переопредение всего что есть
		// сохранение
		if(!count($emType))
			return $this->jsonResult(['result'=>'error']);
		
		$emType = $emType[0];
		$settings = $this->request->getPost('set');

		$emType->required = (!empty($settings['required']))?1:0;
		$emType->multiple = (!empty($settings['multiple']))?1:0;
		
		$settingsArr = [];
		foreach ($settings as $settingKey => $settingVal)
		{
			if(!in_array($settingKey, ['required','multiple']))
				$settingsArr[$settingKey] = $settingVal;
		}
		if(!empty($settingsArr))
			$emType->settings = json_encode($settingsArr);
		
		if($emType->save())
			$this->jsonResult(['result'=>'success']);
		else
		{
			foreach ($emType->getMessages() as $message) {
		        echo $message, "\n";
		    }
		    exit();
		}

	}

	/**
	 * Проверяет на обновления 
	 */
	public function checkUpdatesAction()
	{
		// достаем тещую версию системы
		$sysFile = $this->di->get('config')->application->configDir.'sysinfo.json';
		$sysFileArr = json_decode(file_get_contents($sysFile),true);
		
		if(empty($sysFileArr['version']))
			$this->jsonResult(['result'=>'error','msg'=>'wrong config/sysinfo.json file']);
		// отправляем на сервер обновлений информацию о версии
		// чтобы получить информацию о имеющихся обновлениях
			$jsonRes = file_get_contents('http://element.woorkup.ru/chekUpdates.php?version='.$sysFileArr['version']);
			if(!empty($jsonRes))
				$this->jsonResult(json_decode($jsonRes));
			else
				$this->jsonResult(['result'=>'error','msg'=>'something wrong on the server']);
	}

	/**
	 * Производит обновление системы до последней версии
	 */
	public function updateSystemAction()
	{
		// достаем тещую версию системы
		$sysFile = $this->di->get('config')->application->configDir.'sysinfo.json';
		$sysFileArr = json_decode(file_get_contents($sysFile),true);
		
		$preambula = ROOT.'/'.ltrim($this->di->get('config')->application->baseUri,'/');

		if(empty($sysFileArr['version']))
			return $this->jsonResult(['result'=>'error','msg'=>'wrong config/sysinfo.json file']);
		
		// отправляем на сервер обновлений информацию о версии
		// чтобы получить информацию о имеющихся обновлениях
		$jsonNeedUpdateFiles = file_get_contents('http://element.woorkup.ru/update.php?version='.$sysFileArr['version']);
		if(empty($jsonNeedUpdateFiles))
			return $this->jsonResult(['result'=>'error','msg'=>'something wrong on the server']);
			
		$needUpdateFiles = json_decode($jsonNeedUpdateFiles,true);
		if(empty($needUpdateFiles['result']) || $needUpdateFiles['result'] != 'success')
			return $this->jsonResult(['result'=>'error','msg'=>'something wrong on the server']);
		
		$newVersion = '';
		foreach($needUpdateFiles['files'] as $filePath => $fileArr)
		{
			if($fileArr['type'] == 'update')
			{
				$newCont = @file_get_contents($fileArr['path']);
				if(!empty($newCont))
					@file_put_contents($preambula.$filePath, $newCont);
			}
			elseif($fileArr['type'] == 'add')
			{
				$newCont = @file_get_contents($fileArr['path']);
				if(!empty($newCont))
					@file_put_contents($preambula.$filePath, $newCont);
			}
			elseif($fileArr['type'] == 'delete')
			{
				if(is_file($preambula.$filePath))
				{
					@unlink($preambula.$filePath);
				}
			}
			elseif($fileArr['type'] == 'version')
			{
				// обновление версии системы
				$sysFileArr['version'] = $fileArr['value'];
				$newVersion = $fileArr['value'];
				@file_put_contents($sysFile, json_encode($sysFileArr,JSON_PRETTY_PRINT));
			}
		}
		$this->jsonResult(['result'=>'success','msg'=>'Система обновлена до версии - '.$newVersion]);
	}

	/**
	 * Форма редактирования пользователя
	 */
	public function userAction($userId = false)
	{
		if(empty($userId))
			return $this->pageNotFound();
		$user = EmUsers::findFirst($userId);
		if($user)
			$this->view->setVar('user',$user);
		else
			$this->pageNotFound();
		
	}

	/**
	 * Сохранение пользователя
	 * @return json
	 */
	public function saveUserAction($userId = false)
	{
		if(!$this->request->isAjax())
			return $this->jsonResult(['result'=>'error', 'msg'=>'only ajax']);

		$name     = $this->request->getPost('name');
		$login    = $this->request->getPost('login');
		$email    = $this->request->getPost('email');
		$password = $this->request->getPost('password');
		
		if(empty($name) || empty($login) || empty($email) || empty($password) || empty($userId))
			return $this->jsonResult(['result'=>'error', 'msg'=>'не все поля заполнены']);
		
		$user = EmUsers::findFirst($userId);
		if(!$user)
			return $this->jsonResult(['result'=>'error', 'msg'=>'пользователь не найден']);
		
		if($user->password != md5($password))
			return $this->jsonResult(['result'=>'error', 'msg'=>'Пароль не совпадает']);

		$user->name  = $name;
		$user->login = $login;
		$user->email = $email;

		$newPassword = $this->request->getPost('newpassword');
		$repassword  = $this->request->getPost('repassword');
		if(!empty($newPassword) && !empty($repassword))
			if($newPassword == $repassword)
				$user->password = md5($newPassword);
			else
			{
				$this->jsonResult(['result'=>'error', 'msg'=>'Пароли не совпадают']);
				return false;
			}

		if($user->save())
			$this->jsonResult(['result'=>'success', 'msg'=>'Настройки сохранены']);

	}

}

