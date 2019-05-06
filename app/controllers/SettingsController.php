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
		$fieldsTypes = $this->element->getEmTypes();

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
			'bind' => [
				$tableName, $columnName
			]
		]);
		if (!$field)
			$field = new EmTypes();

		$field->field    = $columnName;
		$field->table    = $tableName;
		$field->type     = $fieldType;

		$field->save();

		return $this->jsonResult(['success' => true, 'settings' => $field]);
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

		if (array_key_exists('path', $settings))
			if (!is_dir(ROOT . $settings['path']))
				return $this->jsonResult(['success' => false, 'message' => "directory {$settings['path']} does not exist"]);

		$field = EmTypes::findFirst([
			'table = ?0 and field = ?1',
			'bind' => [
				$tableName, $columnName
			]
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

		$field->settings = $settings;

		if ($field->save() === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'settings' => $field]);
	}


	/**
	 * Проверка на наличие обновления
	 * @return json
	 */
	public function checkVersionAction()
	{
		$composerJson = file_get_contents(ROOT."/composer.json");
	}

	/**
	 * Обновление элемента
	 * @return  json
	 */
	public function updateAction()
	{
		// список тегов
		// https://api.github.com/repos/dzantiev/element/tags

		// разница межу разными версиями
		// https://api.github.com/repos/dzantiev/element/compare/v0.1.5...v0.1.9
		// row
		// https://github.com/dzantiev/element/blob/c3f091dbd5a6ab1f917303e6bc5740eda93623c2/.gitattributes
		// при обновлении осключать лишниые файлы - export ignore
	}
}