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
		'visualEditor' =>'Визуальный редактор'
	];

	public function indexAction()
	{
		$this->view->setVar( 'EmTypes', $this->tableEditor->systemEmTypes );

		// достаем все таблицы
		$detailTables = [];
		if(!empty($this->tables))
		{
			// проход по всем таблицам в базе
			// переопределение типов если они имеются
			foreach ($this->tables as $realTableName => $tableArr)
			{
				$columns = $this->tableEditor->getTableColumns($realTableName);
				$overColumns = $this->tableEditor->getOverTableColumns($realTableName);
				$curTable = array_merge($tableArr,['fields'=>[]]);
				$curTable['fields'] = $this->tableEditor->getOverTable($columns,$overColumns);
				$detailTables[$realTableName] = $curTable;
			}
			$this->view->setVar('detailTables',$detailTables);
		}

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
	 * Сохраняет
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

	/**
	 * Возврощает форму редактирования свойств поля
	 * в зависимости от типа поля
	 */
	public function getFieldFormAction()
	{
		if($this->request->isAjax())
		{
			$tableName = $this->request->getPost('tableName');
			$fieldName = $this->request->getPost('fieldName');

			$this->view->setVar('tableName',$tableName);
			$this->view->setVar('getFieldName',$fieldName);
			
			// определяем текущие настройки поля
			$emType = EmTypes::find([
				'conditions' => 'table = ?0 and field = ?1 ',
				'bind' => [$tableName, $fieldName]
			]);
			if(count($emType))
			{
				$emType = $emType[0];
				$settingFields = [];
				
				// обязательые параметры для всех типов полей
				$settingFields['required'] = $emType->required;
				$settingFields['multiple'] = $emType->multiple;
				$settings = [];
				if(!empty($emType->settings))
				{
					$settings = json_decode($emType->settings,true);
				}
				
				// в зависомости от типа поля достаем разные параметры 
				if($emType->type == 'em_file')
				{
					$settingFields['savePath'] = (!empty($settings['savePath']))?$settings['savePath']:$this->tableEditor->getDefaultFilesSavePath();
					$settingFields['fileTypes'] = (!empty($settings['fileTypes']))?$settings['fileTypes']:[];
					
					// определяем доп переменную для типов файлов
					$this->view->setVar('fileTypes',['jpeg','png','gif','bmp','pdf','doc']);
				}
				elseif($emType->type == 'em_text')
				{
					$settingFields['visualEditor'] = (!empty($settings['visualEditor']))?$settings['visualEditor']:0;

				}
				elseif($emType->type == 'em_node')
				{
					$settingFields['nodeTable'] = (!empty($settings['nodeTable']))?$settings['nodeTable']:false;
					$settingFields['nodeField'] = (!empty($settings['nodeField']))?$settings['nodeField']:false;
					
					// определяем доп переменные для таблиц
					// весь список таблиц и их полей
					// для нужд привязки к ним в данном типе поля
					$resTables = $this->tables;
					foreach ($resTables as $tableRealName => &$tableArr)
					{
						$curCols = $this->tableEditor->getTableColumns($tableRealName);
						$tableArr['fields'] = [];
						foreach ($curCols as $colArr)
							$tableArr['fields'][$colArr['field']] = $colArr['type'];
					}
					$this->view->setVar('tables',$resTables);
					$this->view->setVar('tablesJSON',json_encode($resTables));
				}
				
				$this->view->setVar('settingFields',$settingFields);
				$this->view->setVar('fieldsPulicNames',$this->fieldsPulicNames);

				$this->jsonResult([
                	'result' => 'success',
                	'form' => $this->view->getRender('settings','getFieldForm')
            	]);
			}
			else
				$this->jsonResult(['result'=>'error','msg'=>'Для такого поля нет настроек']);
		}
		else
			$this->jsonResult(['result'=>'error']);
	}

	/**
	 * Сохранение настройки поля таблицы
	 */
	public function saveFieldFormAction()
	{
		if($this->request->isAjax())
		{
			$tableName = $this->request->getPost('tableName');
			$fieldName = $this->request->getPost('fieldName');
			// достаем EmTypes (настройки поля)
			$emType = EmTypes::find([
				'conditions' => "table = ?0 AND field = ?1",
				'bind' => [$tableName,$fieldName],
			]);
			// проходи по всем полям, переопредение всего что есть
			// сохранение
			if(count($emType))
			{
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
					$this->jsonResult(['result'=>'error']);
			}
			else
				$this->jsonResult(['result'=>'error']);

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
		
		if(!empty($sysFileArr['version']))
		{
			// отправляем на сервер обновлений информацию о версии
			// чтобы получить информацию о имеющихся обновлениях
				$jsonRes = file_get_contents('http://element.woorkup.ru/chekUpdates.php?version='.$sysFileArr['version']);
				if(!empty($jsonRes))
					$this->jsonResult(json_decode($jsonRes));
				else
					$this->jsonResult(['result'=>'error','msg'=>'something wrong on the server']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'wrong config/sysinfo.json file']);
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

		if(!empty($sysFileArr['version']))
		{
			// отправляем на сервер обновлений информацию о версии
			// чтобы получить информацию о имеющихся обновлениях
				$jsonNeedUpdateFiles = file_get_contents('http://element.woorkup.ru/update.php?version='.$sysFileArr['version']);
				if(!empty($jsonNeedUpdateFiles))
				{
					$needUpdateFiles = json_decode($jsonNeedUpdateFiles,true);
					if(!empty($needUpdateFiles['result']) && $needUpdateFiles['result'] == 'success')
					{
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
					else
						$this->jsonResult(['result'=>'error','msg'=>'something wrong on the server']);
				}
				else
					$this->jsonResult(['result'=>'error','msg'=>'something wrong on the server']);
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'wrong config/sysinfo.json file']);
	}

	/**
	 * Форма редактирования пользователя
	 */
	public function userAction($userId = false)
	{
		if(!empty($userId))
		{
			$user = EmUsers::findFirst($userId);
			if($user)
			{
				$this->view->setVar('user',$user);
			}
			else
				$this->pageNotFound();
		}
		else
			$this->pageNotFound();
	}

	/**
	 * Сохранение пользователя
	 * @return json
	 */
	public function saveUserAction($userId = false)
	{
		if($this->request->isAjax())
		{
			$name     = $this->request->getPost('name');
			$login    = $this->request->getPost('login');
			$email    = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			if(!empty($name) && !empty($login) && !empty($email) && !empty($password) && !empty($userId))
			{
				$user = EmUsers::findFirst($userId);
				if($user)
				{
					if($user->password == md5($password))
					{
						$user->name  = $name;
						$user->login = $login;
						$user->email = $email;

						$newPassword = $this->request->getPost('newpassword');
						$repassword  = $this->request->getPost('repassword');
						if(!empty($newPassword) && !empty($repassword))
						{
							if($newPassword == $repassword)
							{
								$user->password = md5($newPassword);
							}
							else
							{
								$this->jsonResult(['result'=>'error', 'msg'=>'Пароли не совпадают']);
								return false;
							}
						}
						
						if($user->save())
							$this->jsonResult(['result'=>'success', 'msg'=>'Настройки сохранены']);
					}
					else
						$this->jsonResult(['result'=>'error', 'msg'=>'Пароль не совпадает']);
				}
				else
					$this->jsonResult(['result'=>'error', 'msg'=>'пользователь не найден']);
			}
			else
				$this->jsonResult(['result'=>'error', 'msg'=>'не все поля заполнены']);
		}
		else
			$this->jsonResult(['result'=>'error', 'msg'=>'only ajax']);
	}

}

