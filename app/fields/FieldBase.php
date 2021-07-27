<?php
/**
 * Abstract class for field type
 */
abstract class FieldBase extends Phalcon\Mvc\User\Plugin
{
	protected $fieldValue = '';
	protected $row        = [];
	protected $settings   = [];
	protected $table      = '';
	public function __construct($fieldValue = '', array $settings = [], array $row = [], $table = '')
	{
		$this->fieldValue = $fieldValue;
		$this->settings   = $settings;
		$this->row        = $row;
		$this->table      = $table;
	}
	abstract function getValue();
	abstract function saveValue();

	/**
	 * Gets collations
	 * @return array
	 */
	public function getCollations()
	{
		$locales = json_decode($this->getLocales());
		return [
			["name"=>$locales->is_not_empty,    "code"=>"IS NOT EMPTY"],
			["name"=>$locales->is_empty,        "code"=>"IS EMPTY"],
			["name"=>$locales->is,              "code"=>"IS"],
			["name"=>$locales->is_not,          "code"=>"IS NOT"],
			["name"=>$locales->contains,        "code"=>"CONTAINS"],
			["name"=>$locales->does_not_contain,"code"=>"DOES NOT CONTAIN"],
			["name"=>$locales->start_with,      "code"=>"START WITH"],
			["name"=>$locales->ends_with,       "code"=>"ENDS WITH"],
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
				return $whereArray['code'] . ' = :value:';
				break;

			case 'IS NOT':
				return $whereArray['code'] . ' <> :value:';
				break;

			case 'CONTAINS':
				return $whereArray['code'] . ' LIKE :value:';
				break;

			case 'DOES NOT CONTAIN':
				return $whereArray['code'] . ' NOT LIKE :value:';
				break;

			case 'START WITH':
				return $whereArray['code'] . ' LIKE :value:';
				break;

			case 'ENDS WITH':
				return $whereArray['code'] . ' LIKE :value:';
				break;

			case 'IS EMPTY':
				return "{$whereArray['code']} IS NULL OR {$whereArray['code']} = \"\" ";
				break;

			case 'IS NOT EMPTY':
				return "{$whereArray['code']} IS NOT NULL OR {$whereArray['code']} <> \"\" ";
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
	 * Gets translate for files
	 * @return string
	 */
	public function getLocales()
	{
		$lang = 'en';

		if (!empty($this->di->get('user')))
			$lang = $this->di->get('user')->language;

		$fieldFile = "{$this->getFieldFolderPath()}/locale/{$lang}.json";
		if(file_exists($fieldFile))
			return file_get_contents($fieldFile);

		$fieldFile = __DIR__ . "/locale/{$lang}.json";
		return file_get_contents($fieldFile);
	}
}
