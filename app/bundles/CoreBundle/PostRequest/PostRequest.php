<?php
/**
* Class PostRequest responsible for get the files and
* values submited by forms.
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/
namespace EDValidator\bundles\CoreBundle\PostRequest;

use EDValidator\bundles\CoreBundle\Abstracts\Request;
use EDValidator\bundles\CoreBundle\GetFiles\GetFiles;
use EDValidator\bundles\CoreBundle\Exceptions\RequestException;
use EDValidator\bundles\CoreBundle\Models\Log;

class PostRequest extends Request
{
	/**
	 * @var GetFiles
	 */
	private $file;

	/**
	 * @var string
	 */
	private $uploadPath;

	/**
	 * Get a file submited and stored in the Super Globals $_FILES
	 * @param  GetFiles $files Instance of GetFiles to manage the file submited
	 * @return boolean         If the file was moved to the uploads paths it's 
	 *                         return true if it will not return false
	 */
	public function getFiles(GetFiles $files)
	{
		$this->file = $files;
		return $this->file->get($_FILES, $this->getUploadPath());
	}

	/**
	 * Get the value in the super global $_POST with filter
	 * @param  string $key Index of the $_POST
	 * @return mixed       The value if exists
	 */
	public function getValue($key)
	{
		return filter_input(INPUT_POST, $key);
	}

	/**
	 * Set the upload directory
	 * @param string $path Name of the directory
	 * @throws RequestException
	 */
	public function setUploadPath($path)
	{
		try{

			if(!is_dir($path)){
				throw new RequestException('It\'s not directory: "'.$path.'"');
			}
			$this->uploadPath = $path;
		}catch(RequestException $ex){
			Log::register($ex);
		}

	}

	/**
	 * Get the upload directory
	 * @throws RequestException
	 * @return string The path
	 */
	public function getUploadPath()
	{
		try{
			if(!is_null($this->uploadPath)){
				return $this->uploadPath;

			}
			throw new RequestException(
				'Path not defined to move the file, please set using the setUploadPath method of the class '.__CLASS__
			);
		}catch(RequestException $ex){
			Log::register($ex);
		}
	}

	/**
	 * Get the file type Mime or siple extencion
	 * @param  boolean $mime
	 * @return string        If the parammeter $mime is true return 
	 *                       the MIME type of the file, if not, return 
	 *                       the simple file extension
	 */
	public function getFileTypeInfo($mime = false)
	{
		try{
			if($this->issetFiles()){
				return $this->file->getFileType($mime);
			}
		}catch(RequestException $ex){
			Log::register($ex);
		}
	}

	/**
	 * Get the name of file
	 * @throws RequestException
	 * @return string Name of file
	 */
	public function getFileNameInfo()
	{
		try{
			if($this->issetFiles()){
				return $this->file->getFileName();
			}
		}catch(RequestException $ex){
			Log::register($ex);
		}
	}


	/**
	 * Get the size of file in bytes
	 * @throws RequestException
	 * @return string size of file
	 */
	public function getFileSizeInfo()
	{
		try{
			if($this->issetFiles()){
				return $this->file->getFileSize();
			}
		}catch(RequestException $ex){
			Log::register($ex);
		}
	}

	/**
	 * Check if exists an file configured in this class
	 * @throws RequestException
	 * @return boolean True if file is configured
	 */
	private function issetFiles()
	{
		if(isset($this->file)){
			return true;
		}	
		throw new RequestException('No file being archived');
	}
}
