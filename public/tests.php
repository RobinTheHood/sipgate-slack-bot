<?php

use App\Classes\Slack\SlackMessageFormater;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
restore_error_handler();
restore_exception_handler();

require_once '../vendor/autoload.php';

function testCron()
{
    $cronjobController = new App\Classes\Controller\CronjobController();
    $cronjobController->runOnce();
}

function testSipgateApi()
{
    $sipgateApi = new App\Classes\Sipgate\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    $historyEntries = $sipgateApi->getAllHistoryEntries();

    foreach ($historyEntries as $historyEntry) {
        $slackMessageFormater = new SlackMessageFormater();
        $message = $slackMessageFormater->formatHistoryEntry($historyEntry);
        var_dump($historyEntry);
        echo json_encode($message);
    }
}

function testSipgateApi5()
{
    $sipgateApi = new App\Classes\Sipgate\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    $historyEntries = $sipgateApi->getAllHistoryEntries();

    foreach ($historyEntries as $historyEntry) {
        var_dump($historyEntry->getHistoryEntryData()['endpoints']);
        var_dump($historyEntry->getSourceContact());
    }
}

function testSipgateApi4()
{
    $sipgateApi = new App\Classes\Sipgate\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    $historyEntries = $sipgateApi->getAllHistoryEntries();

    foreach ($historyEntries as $historyEntry) {
        var_dump($historyEntry->getCreated());
        $date = $historyEntry->getCreated()->setTimeZone(new DateTimeZone('Europe/Berlin'));
        var_dump($historyEntry->getCreated()->format('d.m.Y H:i'));
        var_dump($date->format('d.m.Y H:i'));
    }
}

function testSipgateApi2()
{
    $sipgateApi = new App\Classes\Sipgate\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    $contacts = $sipgateApi->getContactsByNumber('4942029919196');
    var_dump($contacts);
}

function testSipgateApi3()
{
    $sipgateApi = new App\Classes\Sipgate\SipgateApi(
        App\Config\Config::SIPGATE_API_USERNAME,
        App\Config\Config::SIPGATE_API_PASSWORD
    );

    $historyEntries = $sipgateApi->getAllHistoryEntries();

    foreach ($historyEntries as $historyEntry) {
        var_dump($historyEntry);
        var_dump($historyEntry->getAgeAfterHangUp());
    }
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

//testApiController();
//testCron();
//testSipgateApi();
//testSipgateApi2();
//testSlackApi();
