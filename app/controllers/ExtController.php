<?php
class ExtController extends ControllerBase
{
	/**
	 * Extentions routing
	 * /ext/<extenstion field name>/<controller name>/<action>/<params/.../>
	 */
	public function indexAction($extName,$extController = 'index',$extAction = 'index')
	{
		global $config;
		if(empty($extName) || empty($extController))
		{
			$this->pageNotFound();
			return false;
		}

		//############################################################
		// CONTROLLERS
		$extControllerName = ucfirst($extController).'EController';
		$extPath           = $config->application->extDir.$extName.'/';
		$extControllerPath = $extPath.'controllers/'.$extControllerName.'.php';
		if(!is_dir($extPath) || !file_exists($extControllerPath))
		{
			$this->pageNotFound();
			return false;
		}
		$args       = func_get_args();
		$actionArgs = array_slice($args, 3);
		require_once($extControllerPath);
		//############################################################
		// MODELS
		// регистрируем папку с моделями если она есть
		$extModelsDir  = $extPath.'models/';
		if(is_dir($extModelsDir))
		{
			global $loader;
			$loader->registerDirs([$extModelsDir],true)->register();
		}

		$contr = new $extControllerName();
		call_user_func_array([$contr,$extAction.'Action'],$actionArgs);
	}

	/**
	 * Returns extensions links
	 * @return json
	 */
	public function getLinksAction()
	{
		global $config;
		$links = [];
		$files = scandir($config->application->extDir,1);
		foreach ($files as $ext)
		{
			$infoFile = $config->application->extDir.$ext.'/info.json';
			if(is_dir($config->application->extDir.$ext) && file_exists($infoFile))
			{
				$infoJSON = file_get_contents($infoFile);
				$infoJSON = json_decode($infoJSON,true);
				if(!empty($infoJSON['links']))
					$links = array_merge($links,$infoJSON['links']);
			}
		}
		return  $this->jsonResult(['success'=>true, 'links'=>$links]);
	}
}
