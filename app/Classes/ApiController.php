<?php

namespace App\Classes;

use App\Config\Config;

class ApiController
{
    private $request = [];
    private $actions = [];

    public function __construct()
    {
        $payload = $_POST['payload'] ?? '';
        if (!$payload) {
            return;
        }

        $this->request = json_decode($_POST['payload'], true);
        $this->actions = $this->request['actions'] ?? [];
    }

    public function invoke()
    {
        if ($this->hasAccess()) {
            return;
        }

        $action = $this->getAction();
        if ($action == 'call') {
            $this->invokeCall();
        }

        die();
    }

    private function invokeCall()
    {
        $slackUsername = $this->request['user']['username'] ?? [];

        $config = $this->getConfigBy('slackUsername', $slackUsername);
        if (!$config) {
            echo 'No Access 4';
            return;
        }

        $action = $this->getActionById('call');
        $number = $action['value'];

        if (!$number) {
            return;
        }

        $sipgateDeviceId = $config['sipgateDeviceId'] ?? '';

        echo 'Slack user ' . $slackUsername . ' wants to call ' . $number . ' on device ' . $sipgateDeviceId . "\n";

        $sipgateApi = new SipgateApi(
            Config::SIPGATE_API_USERNAME,
            Config::SIPGATE_API_PASSWORD
        );
        $sipgateApi->call($sipgateDeviceId, $number);
    }

    private function getAction(): string
    {
        if (!$this->hasActionId('call')) {
            return '';
        }
        return 'call';
    }

    private function hasAccess(): bool
    {
        // $slackAppId = $this->request['api_app_id'] ?? '';
        // if ($slackAppId  != Config::SLACK_API_APP_ID) {
        //     echo 'No Access 1';
        //     return false;
        // }

        $token = $this->request['token'] ?? '';
        if ($token != Config::SLACK_API_VERIFICATION_TOKEN) {
            echo 'No Access 2';
            return false;
        }

        return true;
    }

    private function getConfigBy(string $field, string $value): array
    {
        foreach (Config::API_CONFIG as $entry) {
            if ($entry[$field] == $value) {
                return $entry;
            }
        }
        return [];
    }

    private function hasActionId(string $id): bool
    {
        if ($this->getActionById($id)) {
            return true;
        }
        return false;
    }

    private function getActionById(string $id): array
    {
        foreach ($this->actions as $action) {
            if ($action['action_id'] == $id) {
                return $action;
            }
        }
        return [];
    }

    private function hasActionValue(string $value): bool
    {
        foreach ($this->actions as $action) {
            if ($action['value'] == $value) {
                return true;
            }
        }
        return false;
    }
}
