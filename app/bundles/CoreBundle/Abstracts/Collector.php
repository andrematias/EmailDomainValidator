<?php
/**
* Abstract Class Collector
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator\bundles\CoreBundle\Abstracts;

abstract class Collector
{
	abstract public  function  getController();
	abstract public  function  getMethod();
	abstract public  function  getParammeters();
}
