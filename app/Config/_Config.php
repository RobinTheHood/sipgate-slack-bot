<?php

namespace App\Config;

class Config
{
    // Sipgate Api Token Namen
    public const SIPGATE_API_USERNAME = "token-XXXXXX";

    // Sipgate Api Token Token
    public const SIPGATE_API_PASSWORD = "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX";

    // Slack Api Client Id
    public const SLACK_API_USERNAME = "XXXXXXXXXXXX.XXXXXXXXXXXXX";

    // Slack Api Client Secret
    public const SLACK_API_PASSWORD = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

    // Slack Api Token
    public const SLACK_API_TOKEN = "xoxb-XXX-XXX-XXX";

    // Slack Api Client Id
    public const SLACK_API_VERIFICATION_TOKEN = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

    public const API_CONFIG = [
        [
            'slackUsername' => 'slack_user_name',
            'sipgateDeviceId' => 'e0'
        ]
    ];
}
