<?php
/**
* Class Home, responsible to load the bundle Input File
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/
namespace EDValidator\bundles\CoreBundle\Controllers;

use EDValidator\bundles\InputFileBundle\InputFileBundle;
use EDValidator\bundles\InputFileBundle\Controllers\InputFile;
use EDValidator\bundles\CoreBundle\Abstracts\Controller;

class Home extends Controller
{

	public function index()
	{
		$inputFileBundle = new InputFileBundle();
		$inputFileBundle->loadController('InputFile', 'index', array());
		$inputFileBundle->callController();
	}

}
