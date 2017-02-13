<?php
namespace EDValidator\bundles\CoreBundle\Controllers;

class NotFound
{

	function index($requestName)
	{
		printf("Página <b>%s</b> não encontrada.", urldecode($requestName['request']));
	}
}