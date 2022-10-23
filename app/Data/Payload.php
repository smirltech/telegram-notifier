<?php

namespace App\Data;

class Payload
{

    public function __construct(
        public string $repository,
        public string $repositoryUrl,
        public string $repositoryDescription,
        public string $sender,
        public string $url,
        public string $image,
        public array  $added,
        public array  $removed,
        public array  $modified,
        public string $message,
    )
    {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            repository: $data['repository']['full_name'],
            repositoryUrl: $data['repository']['html_url'],
            repositoryDescription: $data['repository']['description'],
            sender: $data['sender']['login'],
            url: $data['compare'],
            image: $data['sender']['avatar_url'],
            added: $data['head_commit']['added'],
            removed: $data['head_commit']['removed'],
            modified: $data['head_commit']['modified'],
            message: $data['head_commit']['message'],
        );
    }

    public function content(): string
    {
        // create a message content from attributes using md
        $content = "*$this->sender* pushed to [$this->repository]($this->repositoryUrl)\n";
        $content .= "Message: {$this->message} \n";
        $content .= "Added: " . count($this->added) . "\n";
        $content .= "Modified: " . count($this->modified) . "\n";
        $content .= "Removed: " . count($this->removed) . "\n";

        return $content;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function image(): string
    {
        return $this->image;
    }
}
