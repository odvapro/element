<?php
/**
 * Abstract class for field type
 */
abstract class FieldBase extends Phalcon\Mvc\User\Plugin
{
	protected $fieldValue = '';
	protected $settings   = [];
	public function __construct($fieldValue = '', $settings = [])
	{
		$this->fieldValue = $fieldValue;

		if(is_array($settings))
			$this->settings = $settings;
		else
			$this->settings = [];
	}
	abstract function getValue();
	abstract function saveValue();

	public function getSettings()
	{
		$className = get_class($this);
		$classInfo = new ReflectionClass($className);
		$dir       = dirname($classInfo->getFileName());
		$fileInfo  = "{$dir}/info.json";
		$settings  = [];

		if(file_exists($fileInfo))
			$settings = json_decode(file_get_contents($fileInfo), true);

		$settings['fieldComponent'] = $className;
		$settings['code']           = basename($dir);
		$settings                   = array_merge($settings, $this->settings);

		return $settings;
	}
}