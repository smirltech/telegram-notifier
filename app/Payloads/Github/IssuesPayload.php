<?php

namespace App\Payloads\Github;

class IssuesPayload implements Payload
{

    public function __construct(
        public ?string $action,
        public ?string $assigneeLogin,
        public ?string $assigneeAvatarUrl,
        public ?string $assigneeUrl,
        public ?int    $issueNumber,
        public ?string $issueTitle,
        public ?string $issueUrl,
        public ?string $milestoneTitle,
        public ?string $repositoryName,
        public ?string $state,
    )
    {
    }

    public static function fromArray(array $data): static
    {

        $assignee = optional($data)['assignee'] ?? $data['issue']['assignee'];
        return new static(
            action: $data['action'],
            assigneeLogin: $assignee['login'],
            assigneeAvatarUrl: $assignee['avatar_url'],
            assigneeUrl: $assignee['html_url'],
            issueNumber: $data['issue']['number'],
            issueTitle: $data['issue']['title'],
            issueUrl: $data['issue']['html_url'],
            milestoneTitle: self::removeSpecialChar(optional($data['issue']['milestone'])['title']),
            repositoryName: self::removeSpecialChar(optional($data['repository'])['name']),
            state: $data['issue']['state'],
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
        $content = "*Issue* [#{$this->issueNumber}]($this->issueUrl) ";
        $content .= "*$this->action*\n";
        $content .= "Assignee : [$this->assigneeLogin]($this->assigneeUrl)\n\n";
        $content .= "Title: {$this->issueTitle} \n";
        $content .= "\n";
        $content .= "Milstone: _{$this->milestoneTitle}_ \n";
        $content .= "\n";
        $content .= "Status: *{$this->state}* \n\n";

        return $content;
    }

    public function url(): string
    {
        return $this->issueUrl;
    }

    public function image(): string
    {
        return $this->assigneeAvatarUrl;
    }

}
