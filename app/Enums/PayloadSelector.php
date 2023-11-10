<?php

namespace App\Enums;

use App\Payloads\Github\IssuesPayload;
use App\Payloads\Github\MilestonePayload;
use App\Payloads\Github\Payload;
use App\Payloads\Github\PullRequestPayload;
use App\Payloads\Github\PushPayload;

enum PayloadSelector: string
{
    case push = 'push';
    case issues = 'issues';
    case pull_request = 'pull_request';
    case milestone = 'milestone';

    public function getPayload($data): ?Payload
    {
        return match ($this) {
            self::push => PushPayload::fromArray($data),
            self::issues => IssuesPayload::fromArray($data),
            self::pull_request => PullRequestPayload::fromArray($data),
            self::milestone => MilestonePayload::fromArray($data),
        };
    }
}
