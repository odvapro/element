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

		if (empty($table) || empty($field) || empty($name))
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
}