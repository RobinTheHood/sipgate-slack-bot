<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
restore_error_handler();
restore_exception_handler();

require_once '../vendor/autoload.php';

function testCron()
{
    $cronjobController = new App\Classes\CronjobController();
    $cronjobController->run();
}

function testSipgateApi()
{
    $sipgateApi = new App\Classes\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    //$sipgateApi->call();
    $sipgateApi->getAllHistoryEntries();
}

function testSlackApi()
{
    $slackApi = new App\Classes\SlackApi(
        App\Config\Config::SLACK_API_TOKEN
    );

    $slackApi->sentMessage();
}

testCron();
//testSipgateApi();
//testSlackApi();
