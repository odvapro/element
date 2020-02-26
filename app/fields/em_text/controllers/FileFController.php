<?php

class FileFController extends ControllerBase
{
	private $savedPath = ROOT . '/public/upload/editor/';
	public function uploadAction()
	{
		$file = $this->request->getUploadedFiles();

		if (count($file) == 0)
			return $this->jsonResult(['success' => false, 'message' => 'file not found']);

		$file = $file[0];

		$fileType = FileHelper::getTypeByMimeType($file->getRealType());
		if(empty($fileType))
			return $this->jsonResult(['success' => false, 'message' => 'invalid file type']);

		$extension = FileHelper::$imageTypes[$file->getRealType()];
		$fullPath  = $this->savedPath . md5($file->getName() . time()) . $extension;
		$file->moveTo($fullPath);
		$fileName  = str_replace(ROOT, '', $fullPath);
		return $this->jsonResult(['success' => true, 'fileName' => $fileName]);
	}
}