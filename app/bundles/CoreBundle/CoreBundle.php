<?php
/**
* Class CoreBundle responsible for the loading of the bundle Core
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/
namespace EDValidator\bundles\CoreBundle;

use EDValidator\bundles\CoreBundle\Abstracts\Bundles;

 class CoreBundle extends Bundles
 {
 	/**
	* Name of the Controller
	* @var string
	*/
 	protected $controller;
	
	/**
	* Name of the Method for the Controller
	* @var string
	*/
 	protected $method = 'index';

	/**
	* Parammeters for the Method of the Controller
	* @var array
	*/
 	protected $parammeters = array();

	/**
	* Method for load of names for the Controller, Method and Parammeters for the instance this class
	* @param $controller string	Name for the Controller class
	* @param $method string		Name for the Method of the Controller
	* @param $parammeter array	Vector with arguments names
	* @return void
	*/
 	public function loadController($controller, $method, Array $parammeter)
 	{

 		if($this->isValidController($controller)){
 			$this->controller = 'EDValidator\bundles\CoreBundle\Controllers\\'.$controller;
 			if(!is_null($method) && method_exists($this->controller, $method)){
 				$this->method = $method;
 			}

 			$this->parammeters = $parammeter;

 		}else if(is_null($controller)){
 			$this->controller = 'EDValidator\bundles\CoreBundle\Controllers\Home';
 		}else{
 			$this->controller = 'EDValidator\bundles\CoreBundle\Controllers\NotFound';
 			$this->parammeters['request'] = $controller;
 		}
 	}

	/**
	* Method for execute the Controller
	* @return void
	*/
 	public function callController()
 	{
 		if(!is_null($this->controller)){

	 		call_user_func_array(
	 			array(
	 				$this->controller,
	 				$this->method
	 			),
	 			array($this->parammeters)
	 		);

	 	}
 	}
	
	/**
	* Method for check if the controller is valid
	* @param $controller string	Name for the controller for check
	* @return boolean
	*/
 	private function isValidController($controller)
 	{
 		if(!file_exists(__DIR__.'/Controllers/'.$controller.'.php')){
 			return false;
 		}

 		return true;
 	}
 }
