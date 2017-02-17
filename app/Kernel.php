<?php
/**
* Class Kernel.
* Used for set routes and bundles necessary for application
* @author André Matias <dev.andrematias@gmail.com>
* @version 0.0.1
* @license GPL3
* @package EDValidator
*/

namespace EDValidator;
use EDValidator\bundles\CoreBundle\Router\Router;
use EDValidator\bundles\CoreBundle\RouterCollector\RouterCollector;
use EDValidator\bundles\CoreBundle\Controllers\NotFound;
use EDValidator\bundles\CoreBundle\CoreBundle;


class Kernel
{
	/**
	* Method execute.
	* Execute the routes with bundles configured
	* @return void
	*/
	public function execute()
	{
		$collector = new RouterCollector();

		$route = new Router($collector);

		$route->add('/', new CoreBundle);
		$route->add('Home/', new CoreBundle);

		$traced = $route->trace();

		if(!$traced){
			$notFound = new NotFound();
			$notFound->index(array('request' => $collector->getUri()));
		}
	}
}
