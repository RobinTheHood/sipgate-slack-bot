<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
restore_error_handler();
restore_exception_handler();

require_once __DIR__ . '/../vendor/autoload.php';

$cronjobController = new App\Classes\Controller\CronjobController();
$cronjobController->runAsCron();
