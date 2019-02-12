<?php

class SettingsController extends ControllerBase
{
	/**
	 * Изменить имя таблицы
	 */
	public function changeNameAction()
	{
		$this->request->getPost('tableName');
	}
}