<?php
/**
* log class responsible for register the exceptions catched
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator\bundles\CoreBundle\Models;
use EDValidator\bundles\CoreBundle\Abstracts\Exceptions;

class Log
{
	/**
	 * String to message
	 * @var string
	 */
	protected static $message;

	/**
	 * Method static for register the log in file
	 * @param  Exceptions $exception Exceptions catched
	 * @return void
	 */
	public static final function register(Exceptions $exception)
	{
		self::$message = self::getExceptionInfos($exception);
		self::persistFile(self::$message);

	}

	/**
	 * Collect the infos from the exception
	 * @param  Exceptions $exception Exception catched
	 * @return string                The message for save in file
	 */
	protected static function getExceptionInfos(Exceptions $exception)
	{
		$currentDate = date('Y-m-d H:i:s');

		$out = "[ {$currentDate} ] \n\r";
		$out .= "- Message: {$exception->getMessage()}\n\r";
		$out .= "- Cause of Error: Method {$exception->getTrace()[1]['function']} of the class {$exception->getTrace()[1]['class']}\n\r";
		$out .= "- Source of error: {$exception->getFile()}::{$exception->getLine()}\n\r\n\r";

		return $out;
	}

	/**
	 * Save message in file
	 * @param  string $string Message to save
	 * @return void
	 */
	private static final function persistFile($string)
	{
		$handle = fopen($GLOBALS['paths']['logs'].'log-'.date('Y-m-d', time()).'.txt', "a");
		fwrite($handle, $string);
		fclose($handle);
	}
}