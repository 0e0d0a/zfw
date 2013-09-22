<?php
error_reporting(E_ALL);
define('zerp', true);
define('zerp_cmd', true);
define('ROOT', realpath(dirname(__FILE__).'/../..').'/');
include ROOT.'System/Core/Root/Application.php';
$app = new Application();
echo $app->runCMD('mkdb');