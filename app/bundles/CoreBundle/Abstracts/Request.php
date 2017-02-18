<?php
/**
* Abstract Class Request
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator\bundles\CoreBundle\Abstracts;
use EDValidator\bundles\CoreBundle\GetFiles\GetFiles;
abstract class Request
{
	abstract public function getFiles(GetFiles $files);
	abstract public function getValue($key);
}
