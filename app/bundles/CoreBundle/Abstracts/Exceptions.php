<?php
/**
* Abstract class Exceptions to create new Exceptions
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator\bundles\CoreBundle\Abstracts;

abstract class Exceptions extends \Exception
{
	/**
	 * Method construct of the class
	 * @param string $message Message to construct the Exception
	 * @param int $code       Error number
	 */
	public function __construct($message, $code = null){
		parent::__construct($message, $code);
	}
}