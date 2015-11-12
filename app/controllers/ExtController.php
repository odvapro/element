<?php
class ExtController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();

		// установка названия модуля 
		// название контроллера
		// в блок навигации
		// $extName = 'test';
		// $this->view->setVar('extName',$extName);


	}
	
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
		
		// существование файлов
		$extController     = ucfirst($extController).'EController';
		$extPath           = $config->application->extDir.$extName;
		$extControllerPath = $extPath.'/controllers/'.$extController.'.php';
		$extPath           = $config->application->extDir.$extName;
		if(!is_dir($extPath) || !file_exists($extControllerPath))
		{
			$this->pageNotFound();
			return false;
		}

		$args = func_get_args();
		$actionArgs = array_slice($args, 3);

		require_once($extControllerPath);

		$contr = new $extController();
		call_user_func_array([$contr,$extAction.'Action'],$actionArgs);
		
	}


}

