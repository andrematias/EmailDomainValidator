<?php

$GLOBALS['paths'] = array(
	'root' => __DIR__.'/../../',
	'media'=> __DIR__.'/../../media/',
	'uploads'=> __DIR__.'/../../media/files/uploads/',
	'bin'  => __DIR__.'/../../bin',
	'controllers' => __DIR__.'/../bundles/CoreBundle/Controllers/',
	'logs' => '/var/www/EDValidator/logs/'
);

$protocol = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
$serverName = $_SERVER['SERVER_NAME'];
$serverPort = (isset($_SERVER['SERVER_PORT'])) ? ':'.$_SERVER['SERVER_PORT'] : '';
$GLOBALS['site_paths'] = array(
	'hostname' => $protocol.$serverName.$serverPort
);

$GLOBALS['site_paths']['media'] = $GLOBALS['site_paths']['hostname'].'/media/';

$GLOBALS['sets'] = array(
	'debugg_mode' => true
);

if($GLOBALS['sets']['debugg_mode'] === true){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

require_once($GLOBALS['paths']['root'].'vendor/autoload.php');
