<?php
/**
* Abstract Class Bundles
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/
namespace EDValidator\bundles\CoreBundle\Abstracts;

abstract class Bundles
{
	/**
	* Abstract Method loadController
	* @param $controller string
	* @param $method string
	* @param $parammeters array
	*/
	abstract public  function  loadController($controller, $method, Array $parameters);

	/**
	* Abstract Method callController
	*/
	abstract public function callController();
}
