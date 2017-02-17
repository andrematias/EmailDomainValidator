<?php
/**
* Class RouterCollector responsible for the catch REQUEST URI of the
* Server and set the Controller, Method of the Controller and your
* parammeters.
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator\bundles\CoreBundle\RouterCollector;

use EDValidator\bundles\CoreBundle\Abstracts\Collector;

class RouterCollector extends Collector
{
	/**
	* Controller Name
	* @var string
	*/
	private $controller;

	/**
	* Method name for the controller class
	* @var string
	*/
	private $method;

	/**
	* Parammeters for the method of the controller class
	* @var array
	*/
	private $parammeters;

	/**
	* Uri request of the server
	* @var string
	*/
	private $uri;

	/**
	* Method Construct for the this class
	* Set the uri request and manage results
	* @return void
	*/
	public function __construct()
	{
		$this->uri = explode('?', $_SERVER['REQUEST_URI']);
		unset($this->uri[1]);
		$this->uri = explode('/', trim($this->uri[0], '/'));

		if(!empty($this->uri[0])){
			$this->controller = $this->replaceHyphen($this->uri[0]);
			unset($this->uri[0]);
		}

		if(!empty($this->uri[1])){
			$this->method = $this->uri[1];
			unset($this->uri[1]);
		}

		$this->parammeters = (isset($this->uri[2])) ? array_values($this->uri) : array();

	}

	/**
	* Method replaceHyphen
	* Replace hyphen for First letter of the words to upper case
	* @param $strWithHyphen string
	*/
	private function replaceHyphen($strWithHyphen)
	{
		$strOutput = str_replace('-',' ', $strWithHyphen );
		return str_replace(' ', '', ucwords($strOutput));
	}

	/**
	* Method getController
	* @return string
	*/
	public function getController()
	{
		return $this->controller;
	}

	/**
	* Method getMethod
	* @return string
	*/
	public function getMethod()
	{
		return $this->method;
	}

	/**
	* Method getParammeters
	* @return array
	*/
	public function getParammeters()
	{
		return $this->parammeters;
	}

	/**
	* Method getUri
	* @return string
	*/
	public function getUri()
	{
		return $_SERVER['REQUEST_URI'];
	}
}
