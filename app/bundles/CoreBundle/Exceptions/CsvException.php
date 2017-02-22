<?php
/**
* Class CsvException for exceptions of the CSVReader 
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/
namespace EDValidator\bundles\CoreBundle\Exceptions;
use EDValidator\bundles\CoreBundle\Abstracts\Exceptions;

class CsvException extends Exceptions
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
