<?php
/**
* Abstract Class Request
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator\bundles\CoreBundle\Abstracts;

abstract class Request
{
	abstract public function getFiles();
	abstract public function getValue($key);
}
