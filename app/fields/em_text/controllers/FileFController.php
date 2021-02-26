<?php

class FileFController extends ControllerBase
{
	private $savedPath = ROOT . '/public/upload/';
	public function uploadAction()
	{
		$config = $this->di->get('config');
		$file = $this->request->getUploadedFiles();

		if (count($file) == 0)
			return $this->jsonResult(['success' => false, 'message' => 'file not found']);

		$file = $file[0];

		$fileType = FileHelper::getTypeByMimeType($file->getRealType());
		$extension = FileHelper::$imageTypes[$file->getRealType()] ?? null;

		if(empty($fileType) || empty($extension))
			return $this->jsonResult(['success' => false, 'message' => 'invalid file type']);

		$fullPath  = $this->savedPath . md5($file->getName() . time()) . $extension;
		$file->moveTo($fullPath);
		$fileName  = trim(str_replace(ROOT, '', $fullPath),'/');

		if(isset($_SERVER))
			$domain = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}";
		else
			$domain = '';
		return $this->jsonResult(['success' => true, 'path' => $domain.$config->application->baseUri.$fileName]);
	}
}
