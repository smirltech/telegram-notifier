<?php

namespace App\Data;

class Payload
{

    public function __construct(
        public string $repository,
        public string $pusher,
        public string $url,
        public string $image,
        public array  $commits,
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
            pusher: $data['pusher']['name'],
            url: $data['compare'],
            image: $data['repository']['sender']['avatar_url'],
            commits: $data['commits'],
            added: $data['head_commit']['added'],
            removed: $data['head_commit']['removed'],
            modified: $data['head_commit']['modified'],
            message: $data['head_commit']['message'],
        );
    }

    public function content(): string
    {
        // create a message content from attributes using md
        $content = "Repository: {$this->repository} \n";
        $content .= "Pusher: {$this->pusher} \n";
        $content .= "Message: {$this->message} \n";
        $content .= "Commits: " . count($this->commits) . " \n";
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
