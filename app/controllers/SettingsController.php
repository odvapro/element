<?php

class SettingsController extends ControllerBase
{
	/**
	 * Изменить имя таблицы
	 */
	public function changeNameAction()
	{
		$table = $this->request->getPost('tableName');
		$field = $this->request->getPost('field');
		$name  = $this->request->getPost('name');
		$type  = $this->request->getPost('type');

		if (empty($table) || empty($field))
			return $this->jsonResult(['success' => false, 'message' => 'required fields is not found']);

		if (empty($type))
			$type = 'em_string';

		$emTypes = EmTypes::findFirst([
			'conditions' => 'table = ?0 and field = ?1',
			'bind' => [
				$table, $field
			]
		]);

		if (!$emTypes)
			$emTypes = new EmTypes();

		$emTypes->name  = $name;
		$emTypes->field = $field;
		$emTypes->table = $table;
		$emTypes->type  = $type;
		$emTypes->save();

		return $this->jsonResult(['success' => true]);
	}
	/**
	 * Достать все типы полей
	 * @return json
	 */
	public function getFiledTypesAction()
	{
		$fieldsTypes = $this->element->emTypes;

		return $this->jsonResult(['success' => true, 'types' => $fieldsTypes]);
	}

	/**
	 * Изменить тип поля
	 * @return json
	 */
	public function changeFieldTypeAction()
	{
		$tableName  = $this->request->getPost('tableName');
		$columnName = $this->request->getPost('columnName');
		$fieldType  = $this->request->getPost('fieldType');

		if (empty($tableName) || empty($columnName) || empty($fieldType))
			return $this->jsonResult(['success' => false, 'message' => 'required fields is not found']);

		$field = EmTypes::findFirst([
			'table = ?0 and field = ?1',
			'bind' => [$tableName, $columnName]
		]);
		if (!$field)
			$field = new EmTypes();

		$field->field    = $columnName;
		$field->table    = $tableName;
		$field->type     = $fieldType;

		if ($field->save() === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		if(empty($this->element->emTypes[$fieldType]))
			return $this->jsonResult(['success' => false, 'message' => 'no such field']);

		$emType     = $this->element->emTypes[$fieldType];
		$fieldClass = new $emType['fieldComponent']('', $field->getSettings());
		$emSettings = [
			'type'      => $field->type,
			'name'      => $field->name === $field->field ? "" : $field->name,
			'type_info' => $this->element->emTypes[$field->type],
			'settings'  => $fieldClass->getSettings(),
			'required'  => $field->getRequired()
		];

		return $this->jsonResult(['success' => true, 'settings' => $emSettings]);
	}

	/**
	 * Задать настройки для филда
	 */
	public function setFieldSettingsAction()
	{
		$tableName  = $this->request->getPost('tableName');
		$columnName = $this->request->getPost('columnName');
		$fieldType  = $this->request->getPost('fieldType');
		$settings   = $this->request->getPost('settings');
		if (empty($tableName) || empty($columnName) || empty($fieldType))
			return $this->jsonResult(['success' => false, 'message' => 'required fields is not found']);

		if(!array_key_exists($fieldType, $this->element->emTypes))
			return $this->jsonResult(['success' => false, 'message' => 'incorrect field type']);

		$field = EmTypes::findFirst([
			'table = ?0 and field = ?1',
			'bind' => [$tableName, $columnName ]
		]);
		if (!$field)
			$field = new EmTypes();

		$field->field    = $columnName;
		$field->table    = $tableName;
		$field->type     = $fieldType;

		if (isset($settings['required']))
			$field->required = $settings['required'] === 'true' ? 1 : 0;
		else
			$field->required = 0;

		$field->settings = (object) $settings;

		if ($field->save() === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		$emType     = $this->element->emTypes[$fieldType];
		$fieldClass = new $emType['fieldComponent']('', $field->settings);
		$settings   = $fieldClass->getSettings();

		return $this->jsonResult(['success' => true, 'settings' => $settings]);
	}

	/**
	 * Определяет последнюю версию системы
	 * @return json
	 */
	public function getCurrentVersionAction()
	{
		$composerJson   = file_get_contents(ROOT."/composer.json");
		$composerJson   = json_decode($composerJson,true);
		$currentVersion = $composerJson['verstion'];
		return $this->jsonResult(['success'=>true, 'version'=>$currentVersion]);
	}

	/**
	 * Проверка на наличие обновления
	 * @return json
	 */
	public function checkVersionAction()
	{
		$composerJson   = file_get_contents(ROOT."/composer.json");
		$composerJson   = json_decode($composerJson,true);
		$currentVersion = $composerJson['verstion'];

		$opts        = ['http' => ['method' => 'GET', 'header' => ['User-Agent: PHP'] ] ];
		$context     = stream_context_create($opts);
		$tagsList    = file_get_contents("https://api.github.com/repos/odvapro/element/tags", false, $context);
		$tagsList    = json_decode($tagsList,true);
		$lastVersion = reset($tagsList);

		if($lastVersion['name'] != $currentVersion)
			return $this->jsonResult(['success'=>true,'result'=>true, 'new_version'=>$lastVersion['name']]);
		else
			return $this->jsonResult(['success'=>true,'result'=>false]);
	}

	/**
	 * Обновление элемента
	 * @return  json
	 */
	public function updateAction()
	{
		set_time_limit(0);
		// достаем разницу тегов
		// проходимся по файлам
		// фильтруем нужные
		// остальные - добавляем/удаляем/обновляем
		$composerJson   = file_get_contents(ROOT."/composer.json");
		$composerJson   = json_decode($composerJson,true);
		$currentVersion = $composerJson['verstion'];

		$opts        = ['http' => ['method' => 'GET', 'header' => ['User-Agent: PHP'] ] ];
		$context     = stream_context_create($opts);
		$tagsList    = file_get_contents("https://api.github.com/repos/odvapro/element/tags", false, $context);
		$tagsList    = json_decode($tagsList,true);
		$lastVersion = reset($tagsList);
		if($lastVersion['name'] == $currentVersion)
			return $this->jsonResult(['success' => false,'msg'=>'You have latest version!']);

		$diffUrl = "https://api.github.com/repos/odvapro/element/compare/{$currentVersion}...{$lastVersion['name']}";
		$diffJson = file_get_contents($diffUrl, false, $context);
		$diffJson = json_decode($diffJson,true);

		// определение файлов  для игнорирования
		$gitAttributes = file(ROOT."/.gitattributes");
		foreach ($gitAttributes as &$gitAttributeLine)
			$gitAttributeLine = trim(str_replace(' export-ignore', '', $gitAttributeLine));

		foreach ($diffJson['files'] as $fileArr)
		{
			$needIgnore = array_reduce($gitAttributes, function($carry, $item) use ($fileArr)
			{
				if(strpos($fileArr['filename'], $item) === 0)
					$carry = true;
				return $carry;
			},false);
			if($needIgnore) continue;

			switch ($fileArr['status'])
			{
				case 'modified':
					$fileContent = file_get_contents($fileArr['raw_url']);
					@file_put_contents(ROOT.'/'.$fileArr['filename'], $fileContent);
				break;
				case 'added':
					preg_match_all('/(.*?)\//', $fileArr['filename'], $pathToFile);
					$pathToFile = ROOT . '/' . implode($pathToFile[0]);
					if (!is_dir($pathToFile))
						mkdir($pathToFile, 0755, true);

					$fileContent = file_get_contents($fileArr['raw_url']);
					@file_put_contents(ROOT.'/'.$fileArr['filename'], $fileContent);
				break;
				case 'renamed':
					$fileContent = file_get_contents($fileArr['raw_url']);
					@rename(ROOT.'/'.$fileArr['previous_filename'], ROOT.'/'.$fileArr['filename']);
					@file_put_contents(ROOT.'/'.$fileArr['filename'], $fileContent);
				break;
				case 'removed':
					@unlink(ROOT.'/'.$fileArr['filename']);
				break;
			}
		}

		return $this->jsonResult(['success'=>true]);
	}
}
