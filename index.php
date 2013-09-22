<?php
error_reporting(E_ALL);
define('zerp', true);
define('ROOT', realpath(dirname(__FILE__)).'/');
include ROOT.'System/Core/Root/Application.php';
Application::getInstance()->run();