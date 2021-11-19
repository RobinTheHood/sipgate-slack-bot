<?php

declare(strict_types = 1);

namespace App\Classes\Controller;

use App\Config\Config;
use App\Classes\Sipgate\SipgateApi;
use App\Classes\Slack\InteractiveRequest;

class ApiController
{
    private $request;

    public function invoke()
    {
        $payload = $_POST['payload'] ?? '';
        if (!$payload) {
            return;
        }

        $this->request = new InteractiveRequest($payload);

        if ($this->request->hasAccess(Config::SLACK_API_VERIFICATION_TOKEN)) {
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
        $slackUsername = $this->request->getUsername();

        $config = $this->getConfigBy('slackUsername', $slackUsername);
        if (!$config) {
            echo 'No Access 4';
            return;
        }

        $action = $this->request->getActionById('call');
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
        if (!$this->request->hasActionId('call')) {
            return '';
        }
        return 'call';
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
}
