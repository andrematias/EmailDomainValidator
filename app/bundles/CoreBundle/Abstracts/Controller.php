<?php
namespace EDValidator\bundles\CoreBundle\Abstracts;
use EDValidator\bundles\CoreBundle\Abstracts\View;

abstract class Controller
{
	abstract public function index();

	protected static function view(View $view)
	{
		$view->render();
	}
}
