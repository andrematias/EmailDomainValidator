<?php

$GLOBALS['paths'] = array(
	'root' => __DIR__.'/../../',
	'media'=> __DIR__.'/../../media/',
	'bin'  => __DIR__.'/../../bin',
	'controllers' => __DIR__.'/../bundles/CoreBundle/Controllers/'
);

$protocol = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
$serverName = $_SERVER['SERVER_NAME'];
$serverPort = (isset($_SERVER['SERVER_PORT'])) ? ':'.$_SERVER['SERVER_PORT'] : '';
$GLOBALS['site_paths'] = array(
	'hostname' => $protocol.$serverName.$serverPort
);

$GLOBALS['site_paths']['media'] = $GLOBALS['site_paths']['hostname'].'/media/';


require_once($GLOBALS['paths']['root'].'vendor/autoload.php');
