<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
restore_error_handler();
restore_exception_handler();

require_once '../vendor/autoload.php';

function testCron()
{
    $cronjobController = new App\Classes\Controller\CronjobController();
    $cronjobController->run();
}

function testSipgateApi()
{
    $sipgateApi = new App\Classes\Sipgate\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    //$sipgateApi->call();
    $sipgateApi->getAllHistoryEntries();
}

function testSlackApi()
{
    $slackApi = new App\Classes\Slack\SlackApi(
        App\Config\Config::SLACK_API_TOKEN
    );

    //$slackApi->sentMessage();
}

function testApiController()
{
    $apiController = new App\Classes\Controller\ApiController();
    $apiController->invoke();
}

testApiController();
//testCron();
//testSipgateApi();
//testSlackApi();
