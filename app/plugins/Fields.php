<?php
use \Phalcon\Mvc\Dispatcher as PhDispatcher;
/**
 * Плагин позвляющий добавлять поле любого типа
 */
class Fields extends Phalcon\Mvc\User\Plugin
{
	private $_types;

	public function __construct($dependencyInjector)
	{
		global $loader;
		$this->_dependencyInjector = $dependencyInjector;
		$config = $this->_dependencyInjector->get('config');

		// проходимся по папкам типов полей
		// регистрируем эти папки
		// выносим в переменную содержимое info.json
		$this->_types = [];
		if ($handle = opendir($config->application->fldDir))
		{
			while($fieldName = readdir($handle))
			{
				$field = [];
				$fieldDirPath = $config->application->fldDir . $fieldName;
				if(strpos($fieldName, '.') === false && is_dir($fieldDirPath))
				{
					$loader->registerDirs([$fieldDirPath], true)->register();

					// подгрузка информации о типе поля
					// $infoFilePath = $fieldDirPath.'/info.json';
					// $fieldInfo = [];
					// if(file_exists($infoFilePath))
					// 	$fieldInfo = json_decode(file_get_contents($infoFilePath),true);

					// подготовка имени класса (если есть нижнее подчеркивание)
					$className = explode('_', $fieldName);
					foreach ($className as  &$classNamePart)
						$classNamePart = ucfirst($classNamePart);
					// $className[] = 'Field';
					$className   = implode('', $className);

					// $field = ['code'  => $fieldName, 'class' => $className ];

					$fieldInfo = $className;
					$this->_types[$fieldName] = $fieldInfo;
				}
			}
			closedir($handle);
		}
	}

	/**
	 * [__call description]
	 * @param  string $typeName  field type name
	 * @return object
	 */
	public function __get($typeName)
	{
		static $fieldsObjects = [];
		if(array_key_exists($typeName, $fieldsObjects))
			return $fieldsObjects[$typeName];
		if(array_key_exists($typeName, $this->_types))
		{
			$fieldClassName = $this->_types[$typeName];
			$fieldObject = new $fieldClassName['class']();
			$fieldsObjects[$typeName] = $fieldObject;
			return $fieldObject;
		}
	}
}