<?php
/**
 * Класс для работы с файлами
 */
class FileHelper
{
	public static $tempDir = ROOT . '/public/tmp/';

	public static $imageTypes = [
		'image/jpeg'  => '.jpeg',
		'image/png'   => '.png',
		'image/gif'   => '.gif',
		'image/pjpeg' => '.jpeg',
		'image/webp'  => '.webp',
	];

	public static $fileTypes = [
		'application/msword'            => '.doc',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
		'text/plain'                    => '.txt',
		'application/pdf'               => '.pdf',
		'application/mspowerpoint'      => '.ppt',
		'application/xml'               => '.xml',
		'text/xml'                      => '.xml',
		'application/powerpoint'        => '.ppt',
		'application/vnd.ms-powerpoint' => '.ppt',
		'application/x-mspowerpoint'    => '.ppt',
		'application/plain'             => '.txt',
		'application/zip'               => '.zip',
		'multipart/x-zip'               => '.zip',
		'application/x-zip-compressed'  => '.zip',
		'application/x-compressed'      => '.zip',
		'application/x-compress'        => '.zip'
	];

	/**
	 * Получить разширение файла по mime типу
	 * @param  String $mimeType Mime тип файла
	 * @return String           Разширение файла
	 */
	static function getExtensionByMimeType(String $mimeType)
	{
		if(array_key_exists($mimeType, self::$imageTypes))
			return self::$imageTypes[$mimeType];

		if(array_key_exists($mimeType, self::$fileTypes))
			return self::$fileTypes[$mimeType];

		return false;
	}

	/**
	 * Получить тип файла по mime типу
	 * @param  String $mimeType Mime тип файла
	 * @return String           Тип файла
	 */
	static function getTypeByMimeType(String $mimeType)
	{
		if(array_key_exists($mimeType, self::$imageTypes))
			return 'image';

		return 'file';
	}

	/**
	 * Создать phalcon file по url
	 * @param  string $url адрес файла
	 * @return Phalcon\Http\Request\File      phalcon file
	 */
	static function getFileByUrl($url)
	{
		$tempName   = tempnam(self::$tempDir, 'php');
		$imgRawData = @file_get_contents($url);

		if($imgRawData === false)
			return false;

		$file_info = new finfo(FILEINFO_MIME_TYPE);
		$mime_type = $file_info->buffer($imgRawData);

		$extension = self::getExtensionByMimeType($mime_type);

		if(!$extension)
			return false;

		$filePath = "{$tempName}{$extension}";

		rename($tempName, $filePath);
		$size = file_put_contents($filePath, $imgRawData);

		return self::getFileByPath($filePath);
	}

	/**
	 * Создать phalcon file по адресу
	 * @param  string $path адрес файла
	 * @return Phalcon\Http\Request\File      phalcon file
	 */
	static function getFileByPath($path)
	{
		if(!file_exists($path))
			return false;

		$fileParams = [
			'name'     => basename($path),
			'type'     => mime_content_type($path),
			'tmp_name' => $path,
			'error'    => 0,
			'size'     => strlen(file_get_contents($path))
		];

		$file = new Phalcon\Http\Request\File($fileParams);

		return $file;
	}

	/**
	 * Сохранить файл во временной директории
	 * @param  Phalcon\Http\Request\File $file Файл
	 * @return string                         Путь файла
	 */
	static function saveToTemp(Phalcon\Http\Request\File $file)
	{
		$tempName  = tempnam(self::$tempDir, 'php');
		$extension = self::getExtensionByMimeType($file->getRealType());
		$filePath  = "{$tempName}{$extension}";
		rename($tempName, $filePath);
		$error     = !rename($file->getTempName(), $filePath);

		if($error)
			return false;

		return $filePath;
	}

	/**
	 * Изменить размер изображения
	 * @param  String $filePath   Путь до файла
	 * @param  Array  $imageSize  Массив с параметрами размера
	 * @param  String $saveFolder Путь до папки сохранения
	 * @param  String $fileName   Название для нового файла
	 * @return String             Путь файла
	 */
	static function resize(String $filePath, Array $imageSize, String $saveFolder, String $fileName)
	{
		if(empty($imageSize['width']) || empty($imageSize['height']) || empty($imageSize['name']))
			return false;

		$image = new Image($filePath);

		if($imageSize['width'] == 'auto')
			$imageSize['width'] = $image->getSourceWidth();

		if($imageSize['height'] == 'auto')
			$imageSize['height'] = $image->getSourceHeight();

		$image->crop($imageSize['width'], $imageSize['height']);

		$publicPath = "{$saveFolder}{$imageSize['name']}_{$fileName}";
		$image->save($publicPath);
		return $publicPath;
	}
}
