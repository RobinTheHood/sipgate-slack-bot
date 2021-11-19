<?php

declare(strict_types = 1);

namespace App\Classes\Slack;

class InteractiveRequest
{
    private $request = [];
    private $actions = [];

    public function __construct(string $payload)
    {
        $this->request = json_decode($_POST['payload'], true);
        $this->actions = $this->request['actions'] ?? [];
    }

    public function getUsername(): string
    {
        $username = $this->request['user']['username'] ?? '';
        return $username;
    }

    public function hasAccess(string $verificationToken, string $apiAppId = ''): bool
    {
        if ($$apiAppId) {
            $requestApiAppId = $this->request['api_app_id'] ?? '';
            if ($requestApiAppId  != $apiAppId) {
                return false;
            }
        }

        $requestVerificationToken = $this->request['token'] ?? '';
        if ($requestVerificationToken != $verificationToken) {
            return false;
        }

        return true;
    }

    public function hasActionId(string $id): bool
    {
        if ($this->getActionById($id)) {
            return true;
        }
        return false;
    }

    public function getActionById(string $id): array
    {
        foreach ($this->actions as $action) {
            if ($action['action_id'] == $id) {
                return $action;
            }
        }
        return [];
    }

    public function hasActionValue(string $value): bool
    {
        foreach ($this->actions as $action) {
            if ($action['value'] == $value) {
                return true;
            }
        }
        return false;
    }
}
