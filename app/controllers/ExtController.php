<?php
class ExtController extends ControllerBase
{	
	/**
	 * Роутинг расширений
	 * /ext/<имя папки расширения>/<имя контроллера>/<имя действия>/<параметры/.../>
	 */
	public function indexAction($extName,$extController,$extAction = 'index')
	{
		global $config;
		// установленность значений
		if(empty($extName) || empty($extController))
		{
			// страница не найдена
			$this->pageNotFound();
			return false;
		}
		
		//############################################################
		// CONTROLLERS
		// существование файлов
		$extControllerName     = ucfirst($extController).'EController';
		$extPath           = $config->application->extDir.$extName.'/';
		$extControllerPath = $extPath.'controllers/'.$extControllerName.'.php';

		if(!is_dir($extPath) || !file_exists($extControllerPath))
		{
			$this->pageNotFound();
			return false;
		}

		$args = func_get_args();
		$actionArgs = array_slice($args, 3);

		require_once($extControllerPath);

		//############################################################
		// MODELS
		// регистрируем папку с моделями если она есть
		$extModelsDir  = $extPath.'models/';
		if(is_dir($extModelsDir))
		{
			global $loader;
			$loader->registerDirs([$extModelsDir])->register();
		}

		//############################################################
		// VIEWS
		// определяем view по умолчанию
		// <адрес расширения>/views/<имя контроллера>/<имя действия>.volt
		$extController = strtolower($extController);
		$extViewsPath  = $extPath.'views/'.$extController.'/';
		$extTplPath    = $extViewsPath.$extAction;
		if(!is_dir($extViewsPath) || !file_exists($extTplPath.'.volt'))
			$extTplPath = '';
		$extTplPathPublic = str_replace($config->application->appDir, '', $extTplPath);
		$this->view->setVar('extTplPath',$extTplPathPublic);


		// установка активных ссылко в левом меню
		$this->_setActiveExtansionLinks($extName,strtolower($extController),$extAction);

		$contr = new $extControllerName();
		call_user_func_array([$contr,$extAction.'Action'],$actionArgs);

	}

	/*HELPERS*/
	/**
	 * Активирует нужные ссылки расширения
	 * @return bool
	 */
	private function _setActiveExtansionLinks($extName,$extControllerName,$extActionName = 'index')
	{
		// активируем нужную таблицу и переопределяем все таблицы в шаблоне
		$extActionName = ($extActionName == 'index')?'':$extActionName.'/';
		$link = 'ext/'.$extName.'/'.$extControllerName.'/'.$extActionName;
		foreach ($this->extenLinks as &$exLink)
		{
			if($exLink['link'] == $link)
				$exLink['classes'] = 'act';
		}
		$this->view->setVar('extenLinks',$this->extenLinks);
	}

}

