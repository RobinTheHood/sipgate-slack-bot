<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
restore_error_handler();
restore_exception_handler();

require_once '../vendor/autoload.php';

$apiController = new ApiController();
$apiController->invoke();