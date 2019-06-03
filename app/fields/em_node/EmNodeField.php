<?php

class EmNodeField extends FieldBase
{
	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return strval(strip_tags($this->fieldValue));

		/*$eldb = $this->di->get('db');
		$config  = $this->di->get('config');
		$baseUri = $config->application->baseUri;
		$nodeElement = [];

		$whereSql = $this->settings['bindField'] . " IN (" . $this->fieldValue . ")";

		$tableResult = $eldb->fetchAll(
			"SELECT * FROM " . $this->settings['bindTable'] . " WHERE  $whereSql ",
			Phalcon\Db::FETCH_ASSOC
		);

		foreach ($tableResult as $tableValue)
		{
			$nodeElement         = [];
			$nodeElement['id']   = $tableValue[$this->settings['bindField']];
			$nodeElement['name'] = $tableValue[$this->settings['searchField']];
			$nodeElement['url']  = "{$baseUri}table/{$this->settings['bindTable']}/edit/{$tableValue[$this->settings['bindField']]}";
		}
*/
		// return $nodeElement;
	}

	/**
	 * Сохранить значение
	 */
	public function saveValue()
	{
		return $this->fieldValue;
	}
}