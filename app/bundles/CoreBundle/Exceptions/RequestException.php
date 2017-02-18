<?php
/**
* Class RequestException for exceptions of the requests 
* as the classes PostRequest and GetRequest.
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/
namespace EDValidator\bundles\CoreBundle\Exceptions;
use EDValidator\bundles\CoreBundle\Abstracts\Exceptions;

class RequestException extends Exceptions
{
	/**
	 * Construction method of the class
	 * @param string $message Message to a exception
	 * @param int $code       Error Number
	 */
	public function __construct($message, $code = NULL)
	{
		parent::__construct($message, $code);
	}
}
