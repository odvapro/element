<?php
/**
 * Abstract class for field type
 */
abstract class FieldBase extends Phalcon\Mvc\User\Plugin
{
	protected $fieldValue = '';
	protected $row        = [];
	protected $settings   = [];
	public function __construct($fieldValue = '', array $settings = [], array $row = [])
	{
		$this->fieldValue = $fieldValue;
		$this->settings   = $settings;
		$this->row        = $row;
	}
	abstract function getValue();
	abstract function saveValue();

	/**
	 * Gets collations
	 * @return array
	 */
	public function getCollations()
	{
		return [
			['name'=>'Is Not Empty','code'=>'IS NOT EMPTY'],
			['name'=>'Is Empty','code'=>'IS EMPTY'],
			['name'=>'Is','code'=>'IS'],
			['name'=>'Is Not','code'=>'IS NOT'],
			['name'=>'Contains','code'=>'CONTAINS'],
			['name'=>'Does Not Contain','code'=>'DOES NOT CONTAIN'],
			['name'=>'Start With','code'=>'START WITH'],
			['name'=>'Ends With','code'=>'ENDS WITH'],
		];
	}

	/**
	 * Return collation SQL Where
	 * @var $whereArray = ['code' => id, 'operation' => IS_NOT_EMPTY 'value' =>]
	 * @return string
	 */
	public function getCollationSql($whereArray)
	{
		switch ($whereArray['operation']) {
			case 'IS':
				return $whereArray['code'] . ' = ' . "'" . $whereArray['value'] . "'";
				break;

			case 'IS NOT':
				return $whereArray['code'] . ' <> ' . "'" . $whereArray['value'] . "'";
				break;

			case 'CONTAINS':
				return $whereArray['code'] . ' LIKE ' . "'%" . $whereArray['value'] . "%'";
				break;

			case 'DOES NOT CONTAIN':
				return $whereArray['code'] . ' NOT LIKE ' . "'%" . $whereArray['value'] . "%'";
				break;

			case 'START WITH':
				return $whereArray['code'] . ' LIKE ' . "'" . $whereArray['value'] . "%'";
				break;

			case 'ENDS WITH':
				return $whereArray['code'] . ' LIKE ' . "'%" . $whereArray['value'] . "'";
				break;

			case 'IS EMPTY':
				return $whereArray['code'] . ' = ' . '""';
				break;

			case 'IS NOT EMPTY':
				return $whereArray['code'] . ' <> ' . '""';
				break;
		}
		return '';
	}

	/**
	 * Gets field path
	 * @return string
	 */
	public function getFieldFolderPath()
	{
		$className = get_class($this);
		$classInfo = new ReflectionClass($className);
		return dirname($classInfo->getFileName());
	}

	/**
	 * Gets field settings
	 * @return array
	 */
	public function getSettings()
	{
		$dir      = $this->getFieldFolderPath();
		$fileInfo = "{$dir}/info.json";
		$settings = [];

		if(file_exists($fileInfo))
			$settings = json_decode(file_get_contents($fileInfo), true);

		$settings['fieldComponent'] = get_class($this);
		$settings['code']           = basename($dir);
		if(is_array($this->settings))
			$settings = array_merge($settings, $this->settings);

		return $settings;
	}

	/**
	 * Gets field VueJs code
	 * @return string
	 */
	public function getFieldJs()
	{
		$dir = $this->getFieldFolderPath();
		$fieldFile = "{$dir}/Field.js";
		if(file_exists($fieldFile))
			return file_get_contents($fieldFile);
		return false;
	}

	/**
	 * Gets field VueJs code
	 * @return string
	 */
	public function getSettingsJs()
	{
		$dir = $this->getFieldFolderPath();
		$fieldFile = "{$dir}/Settings.js";
		if(file_exists($fieldFile))
			return file_get_contents($fieldFile);
		return false;
	}

	/**
	 * Gets field style css file
	 * @return string
	 */
	public function getStylesCss()
	{
		$dir = $this->getFieldFolderPath();
		$fieldFile = "{$dir}/style.css";
		if(file_exists($fieldFile))
			return file_get_contents($fieldFile);
		return false;
	}
}