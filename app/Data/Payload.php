<?php

namespace App\Data;

class Payload
{

    public function __construct(
        public string $repository,
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
            sender: $data['sender']['login'],
            url: $data['compare'],
            image: $data['sender']['avatar_url'],
            added: $data['commits']['added'],
            removed: $data['commits']['removed'],
            modified: $data['commits']['modified'],
            message: $data['commits']['message'],
        );
    }

    public function content(): string
    {
        // create a message content from attributes using md
        $content = "Repository: {$this->repository} \n";
        $content .= "Pusher: {$this->sender} \n";
        $content .= "Message: {$this->message} \n";
        $content .= "Added: " . count($this->added) . " \n";
        $content .= "Removed: " . count($this->removed) . " \n";
        $content .= "Modified: " . count($this->modified) . " \n";


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
