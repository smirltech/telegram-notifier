<?php

namespace App\Payloads\Github;

class PullRequestPayload implements Payload
{

    public function __construct(
        public ?string $action,
        public ?string $assigneeLogin,
        public ?string $senderAvatarUrl,
        public ?string $senderUrl,
        public ?int    $number,
        public ?string $title,
        public ?string $url,
        public ?string $milestoneTitle,
        public ?string $repositoryName,
        public ?string $merged,
        public ?int    $commits,
        public ?string $body,
        public ?int    $additions,
        public ?int    $changed_files,
        public ?int    $deletions,
    )
    {
    }

    public static function fromArray(array $data): static
    {

        $sender = optional($data)['sender'];
        return new static(
            action: $data['action'],
            assigneeLogin: $sender['login'],
            senderAvatarUrl: $sender['avatar_url'],
            senderUrl: $sender['html_url'],
            number: $data['number'],
            title: $data['pull_request']['title'],
            url: $data['pull_request']['html_url'],
            milestoneTitle: self::removeSpecialChar(optional(['milestone'])['title']),
            repositoryName: self::removeSpecialChar(optional($data['repository'])['name']),
            merged: $data['pull_request']['merged'] ? 'Yes' : 'No',
            commits: $data['pull_request']['commits'],
            body: $data['pull_request']['body'],
            additions: $data['pull_request']['additions'],
            changed_files: $data['pull_request']['changed_files'],
            deletions: $data['pull_request']['deletions'],
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
        $content = "*Pull request* [#{$this->number}]($this->url) ";
        $content .= "*$this->action*\n";
        $content .= "Author : [$this->assigneeLogin]($this->senderUrl)\n\n";
        $content .= "Title: {$this->title} \n";
        $content .= "\n";
        $content .= "Body : _{$this->body}_ \n";
        $content .= "\n";
        $content .= "Commits: {$this->commits} \n";
        $content .= "Changed files  :{$this->changed_files} \n";
        $content .= "Additions : {$this->additions} \n";
        $content .= "Deletions : {$this->deletions} \n\n";
        $content .= "Merged: *{$this->merged}* \n\n";

        return $content;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function image(): string
    {
        return $this->senderAvatarUrl;
    }

    public function shouldNotify(): bool
    {
        return $this->action == 'review_requested' or $this->action == 'closed';
    }
}
