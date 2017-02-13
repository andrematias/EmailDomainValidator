<?php
require_once(__DIR__.'/app/config/configs.php');

use EDValidator\Kernel;

$kernel = new Kernel();
$kernel->execute();