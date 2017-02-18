<?php
/**
* TODO to documentation and implements the class Log of the CoreBundle to
* register the exceptions messages catched.
*/
namespace EDValidator\bundles\CoreBundle\PostRequest;

use EDValidator\bundles\CoreBundle\Abstracts\Request;
use EDValidator\bundles\CoreBundle\GetFiles\GetFiles;
use EDValidator\bundles\CoreBundle\Exceptions\RequestException;

class PostRequest extends Request
{

	private $file;

	private $uploadPath;

	public function getFiles(GetFiles $files)
	{
		$this->file = $files;
		return $this->file->get($_FILES, $this->getUploadPath());
	}

	public function getValue($key)
	{
		return filter_input(INPUT_POST, $key);
	}

	public function setUploadPath($path)
	{
		try{

			if(!is_dir($path)){
				throw new RequestException('It\'s not directory: "'.$path.'"');
			}
			$this->uploadPath = $path;
		}catch(RequestException $ex){
			echo $ex->getMessage();
		}

	}

	public function getUploadPath()
	{
		try{
			if(!is_null($this->uploadPath)){
				return $this->uploadPath;

			}
			throw new RequestException('Path not defined, please set using the setUploadPath method of this class');
		}catch(RequestException $ex){
			echo $ex->getMessage();

		}
	}

	public function getFileTypeInfo($mime = false)
	{
		try{
			if($this->issetFiles()){
				return $this->file->getFileType($mime);
			}
		}catch(RequestException $ex){
			echo $ex->getMessage();

		}
	}

	
	public function getFileNameInfo()
	{
		try{
			if($this->issetFiles()){
				return $this->file->getFileName();
			}
		}catch(RequestException $ex){
			echo $ex->getMessage();

		}
	}

	public function getFileSizeInfo()
	{
		try{
			if($this->issetFiles()){
				return $this->file->getFileSize();
			}
		}catch(RequestException $ex){
			echo $ex->getMessage();
		}
	}

	private function issetFiles()
	{
		if(isset($this->file)){
			return true;
		}	
		throw new RequestException('No file being archived');
	}
}
