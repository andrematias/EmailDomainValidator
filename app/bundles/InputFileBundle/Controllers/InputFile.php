<?php
namespace EDValidator\bundles\InputFileBundle\Controllers;
use EDValidator\bundles\CoreBundle\Abstracts\Controller;
use EDValidator\bundles\InputFileBundle\Views\InputFile\InputFile AS InputFileView;

class InputFile extends Controller
{

	public function index()
	{
		parent::view(new InputFileView());
	}

}