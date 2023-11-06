<?php

namespace App\Data;

use Illuminate\Support\Str;

class Payload
{

    public function __construct(
        public ?string $repository,
        public ?string $repositoryUrl,
        public ?string $repositoryDescription,
        public ?string $branch,
        public ?string $sender,
        public ?string $url,
        public ?string $image,
        public ?array  $commits,
        public ?array  $added,
        public ?array  $removed,
        public ?array  $modified,
        public ?string $message,
    )
    {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            repository: $data['repository']['full_name'],
            repositoryUrl: $data['repository']['html_url'],
            repositoryDescription: self::removeSpecialChar(optional($data['repository'])['description']),
            branch: self::removeSpecialChar(str_replace('refs/heads/', '', $data['ref'])),
            sender: $data['sender']['login'],
            url: $data['compare'],
            image: $data['sender']['avatar_url'],
            commits: $data['commits'],
            added: optional($data['head_commit'])['added'],
            removed: optional($data['head_commit'])['removed'],
            modified: optional($data['head_commit'])['modified'],
            message: self::removeSpecialChar(optional($data['head_commit'])['message'])
        );
    }

    public static function removeSpecialChar(?string $str): null|array|string
    {
        if ($str==null) return null;

        $res = str_replace(array('_'), '-', $str);

        // Returning the result
        return $res;
    }

    public function content(): string
    {
        // create a message content from attributes using md
        $content = "*{$this->sender}* on [$this->repository]($this->repositoryUrl)\n";
        $content .= "\n";
        $content .= "_{$this->message}_ \n\n";
        $content .= "Commits: " . count($this->commits??[]) . ", ";
        $content .= "Added: " . count($this->added??[]) . ", ";
        $content .= "Modified: " . count($this->modified??[]) . ", ";
        $content .= "Removed: " . count($this->removed??[]) . "\n\n";
        if ($this->repositoryDescription) {
            $content .= "$this->repositoryDescription";
            $content .= "\n\n";
        }
        $content .= $this->hashtag() . "\n";
        $content .= "#{$this->branch()}";

      //  dd($content);

        return $content;
    }

    public function hashtag(): string
    {
        return '#' . Str::replace('-', '', explode('/', $this->repository)[1]);
    }

    //hashtag() is a method that returns the hashtag from the repository name

    private function branch(): array|string
    {
        return Str::replace(['-', '/'], '', $this->branch);
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
