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
        $content .= "\n";
        $content .= "Message: {$this->message} \n";
        $content .= "Added: " . count($this->added) . ", ";
        $content .= "Modified: " . count($this->modified) . ", ";
        $content .= "Removed: " . count($this->removed) . "\n\n";
        $content .= "$this->repositoryDescription";
        $content .= "\n\n";
        $content .= $this->hashtag();

        return $content;
    }

    public function hashtag(): string
    {
        return '#' . str_replace('-', '', explode('/', $this->repository)[1]);
    }

    public function url(): string
    {
        return $this->url;
    }

    //hashtag() is a method that returns the hashtag from the repository name

    public function image(): string
    {
        return $this->image;
    }

}
