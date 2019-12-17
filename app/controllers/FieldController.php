<?php
class FieldController extends ControllerBase
{
	/**
	 * Fields routing
	 * /fld/<имя типа поля>/<имя контроллера>/<имя действия>/<параметры/.../>
	 */
	public function indexAction($fldName, $fldController, $fldAction = 'index')
	{
		global $config;
		if(empty($fldName) || empty($fldController))
		{
			$this->pageNotFound();
			return false;
		}

		//############################################################
		// CONTROLLERS
		$fldControllerName = ucfirst($fldController) . 'FController';
		$fldPath           = $config->application->fldDir . $fldName . '/';
		$fldControllerPath = $fldPath . 'controllers/' . $fldControllerName . '.php';
		if(!is_dir($fldPath) || !file_exists($fldControllerPath))
		{
			$this->pageNotFound();
			return false;
		}

		$args       = func_get_args();
		$actionArgs = array_slice($args, 3);

		require_once($fldControllerPath);

		//############################################################
		// MODELS
		// регистрируем папку с моделями если она есть
		$fldModelsDir  = $fldPath . 'models/';
		if(is_dir($fldModelsDir))
		{
			global $loader;
			$loader->registerDirs([$fldModelsDir])->register();
		}

		$contr = new $fldControllerName();
		call_user_func_array([$contr, $fldAction . 'Action'], $actionArgs);
	}
}
