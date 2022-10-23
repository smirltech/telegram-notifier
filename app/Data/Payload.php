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
        return new static($data);
    }

    public function content()
    {

    }

    public function url()
    {

    }
}
