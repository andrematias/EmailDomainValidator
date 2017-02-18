<?php
namespace EDValidator\bundles\CoreBundle\Exceptions;

class RequestException extends \Exception
{
	public function __construct($message, $code = NULL)
	{
		parent::__construct($message, $code);
	}
}
