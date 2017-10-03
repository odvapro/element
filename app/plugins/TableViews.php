<?php
use \Phalcon\Mvc\Dispatcher as PhDispatcher;
/**
 * Плагин позвляющий добавлять отображение
 */
class TableViews extends Phalcon\Mvc\User\Plugin
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
		if ($handle = opendir($config->application->tViewsDir))
		{
			while($viewName = readdir($handle))
			{
				$view = [];
				$viewDirPath = $config->application->tViewsDir.$viewName;
				if(strpos($viewName,'.') === false && is_dir($viewDirPath))
				{
					$loader->registerDirs([$viewDirPath],true)->register();

					// подгрузка информации о типе поля
					$infoFilePath = $viewDirPath.'/info.json';
					$viewInfo = [];
					if(file_exists($infoFilePath))
						$viewInfo = json_decode(file_get_contents($infoFilePath),true);

					// подготовка имени класса (если есть нижнее подчеркивание)
					$className = explode('_',$viewName);
					foreach ($className as  &$classNamePart)
						$classNamePart = ucfirst($classNamePart);
					$className[] = 'View';
					$className   = implode('',$className);

					$tview = ['code'  => $viewName, 'class' => $className ];

					$viewInfo = array_merge($viewInfo,$tview);
					$this->_types[$viewName] = $viewInfo;
				}
			}
			closedir($handle);
		}
	}

	/**
	 * @param  string $typeName
	 * @return object
	 */
	public function getTypeObject($typeName)
	{
		if(array_key_exists($typeName, $this->_types))
		{
			$viewClassName = $this->_types[$typeName];
			return new $viewClassName['class']();
		}
	}

}