<?php
namespace EDValidator\bundles\CoreBundle\GetFiles;

class GetFiles
{
	private static $savePath;

	private static $fileName;

	private static $tmpName;

	private static $fileType;

	private static $fileSize;


	public static function get(Array $fileInfo, $savePath)
	{
		foreach($fileInfo as $values){
			self::$fileName = $values['name'];
			self::$fileType = $values['type'];
			self::$tmpName  = $values['tmp_name'];
			self::$savePath = $savePath;
			self::$fileSize = $values['size'];
		}

		return self::moveFile(self::$tmpName, self::$savePath.self::$fileName);
	}

	private static function moveFile($tmpName, $newPath)
	{
		if(move_uploaded_file($tmpName, $newPath)){
			return true;
		}
		return false;
	}

	public static function getFileName()
	{
		return self::$fileName;
	}

	public static function getFileType(bool $mime = false)
	{
		if(isset(self::$fileType) && $mime === true){
			return self::$fileType;
		}
		return pathinfo(self::path())['extension'];
	}

	public static function path()
	{
		return self::$savePath.self::$fileName;

	}

	public static function getFileSize()
	{
		return self::$fileSize;
	}
}
