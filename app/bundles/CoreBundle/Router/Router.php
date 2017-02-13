<?php
/**
* Class Router responsible for direct of the server requests
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @package EDValidator
* @license GPL3
*/

namespace EDValidator\bundles\CoreBundle\Router;
use EDValidator\bundles\CoreBundle\Abstracts\Request;
use EDValidator\bundles\CoreBundle\Abstracts\Bundles;

class Router
{
	/**
	* Array with request uri names
	* @var array
	*/
	private $uri = array();

	/**
	* Object instance of class Request
	* @var EDValidator\bundles\CoreBundle\Abstract\Request
	*/
	private $request;

	/**
	* Controller Name
	* @var string
	*/
	private $controller;

	/**
	* Method name for the Controller Class
	* @var string
	*/
	private $method;

	/**
	* Parameters for method of the controller class
	* @var array
	*/
	private $parammeters;

	/**
	* Construct method
	* @param $request EDValidator\bundles\CoreBundle\Abstract\Request
	* @return void
	*/
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	* Method add for add new uri and bundle corresponding
	* @param $uri string							Request name
	* @param $bundle EDValidator\bundles\CoreBundle\Abstract\Bundles	Object instance of Bundles for execute
	* @return void
	*/
	public function add($uri, Bundles $bundle)
	{
		$this->uri[$uri] = $bundle;
	}

	/**
	* Method trace, Execute the routes configured
	*/
	public function trace()
	{

		$uriRequest = $this->request->getController().'/'.$this->request->getMethod();

		$bundle = &$this->uri[$uriRequest];

		if($bundle){

			$this->controller  = $this->request->getController();
			$this->method      = $this->request->getMethod();
			$this->parammeters = $this->request->getParammeters();

			$this->loadBundle($bundle);

			return true;
		}

		return false;
	}
	
	/**
	* Method loadBundle, Loads the controller into the corresponding bundle and executes
	* @param $bundle EDValidator\bundles\CoreBundle\Abstract\Bundles
	* @return void
	*/
	protected function loadBundle(Bundles $bundle)
	{
		$bundle->loadController($this->controller, $this->method, $this->parammeters);
		$bundle->callController();
	}
}
