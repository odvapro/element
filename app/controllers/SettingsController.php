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

			// 
		}
		else
			$this->jsonResult(['result'=>'error','msg'=>'wrong config/sysinfo.json file']);
		
	}
}

