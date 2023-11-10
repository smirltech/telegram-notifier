<?php

namespace App\Payloads\Github;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class MilestonePayload implements Payload
{

    public function __construct(
        public ?string $action,
        public ?int    $milestoneNumber,
        public ?string $milestoneTitle,
        public ?string $milestoneUrl,
        public ?int    $openIssues,
        public ?string $closedIssues,
        public Carbon  $dueOn,
    )
    {
    }

    public static function fromArray(array $data): static
    {

        return new static(
            action: $data['action'],
            milestoneNumber: $data['milestone']['number'],
            milestoneTitle: $data['milestone']['title'],
            milestoneUrl: $data['milestone']['html_url'],
            openIssues: $data['milestone']['open_issues'],
            closedIssues: $data['milestone']['closed_issues'],
            dueOn: Date::parse($data['milestone']['due_on']),
        );
    }

    public static function removeSpecialChar(?string $str): null|array|string
    {
        if ($str == null) return null;

        // Returning the result
        return str_replace(array('_'), '-', $str);
    }

    public function content(): string
    {
        // create a message content from attributes using md
        $content = "*Milestone* [#{$this->milestoneNumber}]($this->milestoneUrl) ";
        $content .= "*$this->action*\n\n";
        $content .= "Title: {$this->milestoneTitle} \n\n";

        $content .= "*Due on*: {$this->dueOn->format('d/m/Y')} \n";

        if ($this->dueOn->isPast())
            $content .= "*Delay*: {$this->dueOn->diffForHumans()} \n";


        $content .= "\n";
        $content .= "Open issues: *{$this->openIssues}* \n";
        $content .= "Closed issues: *{$this->closedIssues}* \n";
        $content .= "\n";
        $content .= "Complete: *{$this->complete()}*% \n";

        return $content;
    }

    private function complete(): float
    {
        return round($this->closedIssues / ($this->openIssues + $this->closedIssues) * 100, 2);
    }

    public function url(): string
    {
        return $this->milestoneUrl;
    }

    public function image(): string
    {
        return url('milestone.png');
    }

}
